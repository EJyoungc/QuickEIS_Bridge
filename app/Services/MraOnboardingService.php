<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\Tenant;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MraOnboardingService
{
    private const BASE_URL = 'https://dev-eis-api.mra.mw/api/v1';

    /**
     * Execute the full two-step MRA terminal activation flow.
     *
     * Step 1: POST /onboarding/activate-terminal
     *   - Sends the TAC, receives jwtToken + secretKey + terminalId
     * Step 2: POST /onboarding/terminal-activated-confirmation
     *   - Sends terminalId with x-signature header (HMAC-SHA512 of TAC using secretKey)
     *
     * @return array{success: bool, message: string}
     */
    public function activate(Tenant $tenant): array
    {
        try {
            // ── Step 1: Activate Terminal ────────────────────────────────────
            // Exact field names from swagger Platform schema:
            // required: osName, osVersion | optional: osBuild, macAddress | NO 'name' field
            $activatePayload = [
                'terminalActivationCode' => $tenant->activation_code,
                'environment' => [
                    'platform' => [
                        'osName'     => php_uname('s'),         // e.g. "Windows NT"
                        'osVersion'  => php_uname('r'),         // e.g. "10.0"
                        'osBuild'    => php_uname('v'),         // e.g. "build 26100"
                        'macAddress' => $this->getMacAddress(), // e.g. "1C-83-41-C3-72-39"
                    ],
                    'pos' => [
                        'productID'      => 'EISBridge',
                        'productVersion' => '1.0',
                    ],
                ],
            ];

            $this->logRequest($tenant, 'outbound_mra', 'rest_request', $activatePayload);

            $activateResponse = Http::timeout(30)
                ->acceptJson()
                ->post(self::BASE_URL . '/onboarding/activate-terminal', $activatePayload);

            $this->logResponse($tenant, 'inbound_mra', 'rest_response', $activateResponse);

            // MRA returns HTTP 200 even for business errors — always check the body statusCode
            $activateBody = $activateResponse->json();
            $mraStatus = $activateBody['statusCode'] ?? 0;

            if (! $activateResponse->successful() || $mraStatus < 0) {
                $remark = $activateBody['remark'] ?? $activateResponse->json('errors.0.errorMessage') ?? 'MRA activation failed.';

                if (str_contains(strtolower($remark), 'already activated')) {
                    $remark .= ' — This TAC has already been used. Please request a new Terminal Activation Code from MRA, or use the "Request New Token" button if the terminal was previously activated successfully.';
                }

                return ['success' => false, 'message' => $remark];
            }

            // ── Parse TerminalActivationResponse (swagger schema) ─────────────
            // data → TerminalActivationResponse
            //   data.activatedTerminal → ActivatedTerminal
            //     .terminalId
            //     .terminalCredentials.jwtToken
            //     .terminalCredentials.secretKey
            //   data.configuration → Configuration
            //     .terminalConfiguration.terminalSite.siteId
            $responseData      = $activateBody['data'] ?? null;
            $activatedTerminal = $responseData['activatedTerminal'] ?? null;

            $terminalId = $activatedTerminal['terminalId'] ?? null;
            $jwtToken   = $activatedTerminal['terminalCredentials']['jwtToken'] ?? null;
            $secretKey  = $activatedTerminal['terminalCredentials']['secretKey'] ?? null;
            $siteId     = $responseData['configuration']['terminalConfiguration']['terminalSite']['siteId'] ?? null;

            if (! $terminalId || ! $jwtToken || ! $secretKey) {
                return [
                    'success' => false,
                    'message' => 'MRA returned success but credentials are missing. activatedTerminal: ' . json_encode($activatedTerminal),
                ];
            }

            // ── Persist credentials NOW (before step 2) so they are never lost ──
            // Status = pending_confirmation until step 2 confirms.
            $tenant->update([
                'status'         => 'pending_confirmation',
                'terminal_id'    => $terminalId,
                'site_id'        => $siteId,
                'jwt_token'      => $jwtToken,
                'secret_key'     => $secretKey,
                'terminal_token' => null,
                'activated_at'   => now(),
            ]);

            Log::info('MRA Step 1 complete — credentials saved, sending confirmation', [
                'tenant'      => $tenant->id,
                'terminal_id' => $terminalId,
            ]);

            // ── Step 2: Confirm Activation ────────────────────────────────────
            return $this->confirmActivation($tenant, $terminalId, $jwtToken, $secretKey);

        } catch (ConnectionException $e) {
            Log::error('MRA Onboarding: Connection failed', ['tenant' => $tenant->id, 'error' => $e->getMessage()]);

            return [
                'success' => false,
                'message' => 'Could not connect to MRA API. Check your internet connection and try again.',
            ];
        } catch (\Throwable $e) {
            Log::error('MRA Onboarding: Unexpected error', ['tenant' => $tenant->id, 'error' => $e->getMessage()]);

            return [
                'success' => false,
                'message' => 'Unexpected error during onboarding: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Step 2 — Confirm terminal activation with MRA.
     *
     * x-signature = HMAC-SHA512(terminalActivationCode, secretKey)
     * Auth = Bearer {jwtToken from step 1}
     * Body = { terminalId: "..." }
     *
     * If this call fails, credentials are already saved (status=pending_confirmation).
     * The user can retry via the Onboard button or manually trigger confirmation.
     *
     * @return array{success: bool, message: string}
     */
    public function confirmActivation(
        Tenant $tenant,
        string $terminalId,
        string $jwtToken,
        string $secretKey
    ): array {
        try {
            $confirmPayload = ['terminalId' => $terminalId];
            $tac            = $tenant->activation_code;

            // Compute signature as base64-encoded raw binary HMAC-SHA512
            // Note: The third argument of hash_hmac is true to output raw binary, which is then base64 encoded.
            $signature = base64_encode(hash_hmac('sha512', $tac, $secretKey, true));

            $this->logRequest(
                $tenant,
                'outbound_mra',
                'rest_request',
                $confirmPayload,
                ['Authorization' => 'Bearer ***', 'x-signature' => substr($signature, 0, 20) . '...']
            );

            $response = Http::timeout(30)
                ->acceptJson()
                ->withHeader('Authorization', 'Bearer ' . $jwtToken)
                ->withHeader('x-signature', $signature)
                ->post(self::BASE_URL . '/onboarding/terminal-activated-confirmation', $confirmPayload);

            $this->logResponse($tenant, 'inbound_mra', 'rest_response', $response);

            $body   = $response->json();
            $status = $body['statusCode'] ?? 0;
            $data   = $body['data'] ?? null;

            Log::info("MRA Step 2 Response", [
                'tenant'     => $tenant->id,
                'httpStatus' => $response->status(),
                'mraStatus'  => $status,
                'data'       => $data,
                'remark'     => $body['remark'] ?? null,
            ]);

            if (! $response->successful() || $status < 0 || $data === false) {
                $remark = $body['remark'] ?? $body['errors'][0]['errorMessage'] ?? 'MRA confirmation failed.';
                return [
                    'success' => false,
                    'message' => "Step 2 (confirmation) failed: {$remark} — Credentials are saved. Use 'Confirm' button or 'Request New Token' to retry.",
                ];
            }

            Log::info("MRA Step 2 succeeded", ['tenant' => $tenant->id]);

            // ── Mark as fully active ──────────────────────────────────────────
            $tenant->update(['status' => 'active']);

            Log::info('MRA Onboarding fully complete', ['tenant' => $tenant->id, 'terminal_id' => $terminalId]);

            return [
                'success' => true,
                'message' => "Terminal '{$tenant->name}' activated successfully. Terminal ID: {$terminalId}",
            ];

        } catch (ConnectionException $e) {
            Log::error('MRA Confirmation: Connection failed', ['tenant' => $tenant->id, 'error' => $e->getMessage()]);

            return [
                'success' => false,
                'message' => 'Could not reach MRA during confirmation — credentials are saved. Use "Request New Token" to retry.',
            ];
        } catch (\Throwable $e) {
            Log::error('MRA Confirmation: Unexpected error', ['tenant' => $tenant->id, 'error' => $e->getMessage()]);

            return ['success' => false, 'message' => 'Confirmation error: ' . $e->getMessage()];
        }
    }

    /**
     * Request a new terminal token via POST /configuration/request-new-terminal-token.
     *
     * Attempts the call with the stored JWT if available.
     * If no JWT is stored, sends the request without auth so MRA can respond
     * with whatever it needs (some dev endpoints work with just the TAC or vendor key).
     *
     * @return array{success: bool, message: string}
     */
    public function refreshToken(Tenant $tenant): array
    {
        try {
            $this->logRequest($tenant, 'outbound_mra', 'rest_request', [
                'action'     => 'request-new-terminal-token',
                'has_jwt'    => ! empty($tenant->jwt_token),
                'terminal_id' => $tenant->terminal_id,
            ]);

            // Build request — attach JWT if we have one, otherwise send bare
            $http = Http::timeout(30)->acceptJson();

            if (! empty($tenant->jwt_token)) {
                $http = $http->withHeader('Authorization', 'Bearer ' . $tenant->jwt_token);
            }

            // Also send the TAC and terminal_id in case MRA uses them for identification
            if (! empty($tenant->terminal_id)) {
                $http = $http->withHeader('X-Terminal-Id', $tenant->terminal_id);
            }

            $response = $http->post(self::BASE_URL . '/configuration/request-new-terminal-token');

            $this->logResponse($tenant, 'inbound_mra', 'rest_response', $response);

            $body      = $response->json();
            $mraStatus = $body['statusCode'] ?? 0;

            if (! $response->successful() || $mraStatus < 0) {
                $remark = $body['remark']
                    ?? $body['errors'][0]['errorMessage']
                    ?? 'Request failed (HTTP ' . $response->status() . ')';

                // Surface the full raw response so we can see what MRA needs
                return [
                    'success' => false,
                    'message' => $remark . ' | Raw: ' . $response->body(),
                ];
            }

            // MRA may return the token directly as data string, or nested
            $newToken = is_string($body['data'] ?? null)
                ? $body['data']
                : ($body['data']['jwtToken'] ?? $body['data']['token'] ?? null);

            if (! $newToken) {
                return [
                    'success' => false,
                    'message' => 'MRA responded OK but no token found. Raw: ' . $response->body(),
                ];
            }

            $tenant->update([
                'jwt_token' => $newToken,
                'status'    => 'active',
            ]);

            return [
                'success' => true,
                'message' => "New JWT token received and saved for '{$tenant->name}'.",
            ];

        } catch (ConnectionException $e) {
            return ['success' => false, 'message' => 'Connection failed: ' . $e->getMessage()];
        } catch (\Throwable $e) {
            return ['success' => false, 'message' => 'Token request error: ' . $e->getMessage()];
        }
    }

    /** @param array<string, mixed> $payload */
    private function logRequest(Tenant $tenant, string $direction, string $type, array $payload, array $extraMeta = []): void
    {
        AuditLog::create([
            'tenant_id'   => $tenant->id,
            'direction'   => $direction,
            'type'        => $type,
            'payload'     => json_encode(
                array_merge($payload, $extraMeta ? ['_headers' => $extraMeta] : []),
                JSON_PRETTY_PRINT
            ),
            'status_code' => null,
        ]);
    }

    private function logResponse(Tenant $tenant, string $direction, string $type, \Illuminate\Http\Client\Response $response): void
    {
        AuditLog::create([
            'tenant_id'   => $tenant->id,
            'direction'   => $direction,
            'type'        => $type,
            'payload'     => $response->body(),
            'status_code' => $response->status(),
        ]);
    }

    /**
     * Retrieve the first physical MAC address of the server's network interface.
     * Falls back to a zeroed-out placeholder if the OS command fails.
     */
    private function getMacAddress(): string
    {
        try {
            if (PHP_OS_FAMILY === 'Windows') {
                $output = shell_exec('getmac /fo csv /nh 2>NUL');
                if ($output) {
                    preg_match('/([0-9A-F]{2}-[0-9A-F]{2}-[0-9A-F]{2}-[0-9A-F]{2}-[0-9A-F]{2}-[0-9A-F]{2})/i', $output, $matches);

                    return $matches[1] ?? '00-00-00-00-00-00';
                }
            } else {
                $output = shell_exec("cat /sys/class/net/$(ip route show default | awk '/default/ {print $5}')/address 2>/dev/null");
                if ($output) {
                    return trim(strtoupper(str_replace(':', '-', $output)));
                }
            }
        } catch (\Throwable) {
            // Silently fall through to placeholder
        }

        return '00-00-00-00-00-00';
    }
}
