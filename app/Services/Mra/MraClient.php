<?php

namespace App\Services\Mra;

class MraClient
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.mra.base_url', 'https://dev-eis-api.mra.mw');
    }

    /**
     * Retrieves the device latest configurations and returns them
     * Path: /api/v1/configuration/get-latest-configs
     */
    public function getLatestConfigs(array $data = [], string $secretKey, string $accessKey): array
    {
        $url = $this->baseUrl . '/api/v1/configuration/get-latest-configs';
        $payload = $data;
        $jsonPayload = json_encode($payload);
        
        // Sign payload
        $signature = base64_encode(hash_hmac('sha512', $jsonPayload, $secretKey, true));
        
        $response = $this->postRequest($url, $payload, $signature, $accessKey);
        
        if ($response->failed()) {
            throw new \Exception('MRA API Error (' . $response->status() . '): ' . $response->body());
        }
        
        return $response->json();
    }

    /**
     * Retrieves new terminal token
     * Path: /api/v1/configuration/request-new-terminal-token
     */
    public function requestNewTerminalToken(array $data = [], string $secretKey, string $accessKey): array
    {
        $url = $this->baseUrl . '/api/v1/configuration/request-new-terminal-token';
        $payload = $data;
        $jsonPayload = json_encode($payload);
        
        // Sign payload
        $signature = base64_encode(hash_hmac('sha512', $jsonPayload, $secretKey, true));
        
        $response = $this->postRequest($url, $payload, $signature, $accessKey);
        
        if ($response->failed()) {
            throw new \Exception('MRA API Error (' . $response->status() . '): ' . $response->body());
        }
        
        return $response->json();
    }

    /**
     * Activates a terminal
     * Path: /api/v1/onboarding/activate-terminal
     */
    public function activateTerminal(\App\DTOs\Mra\UnActivatedTerminal $request, string $secretKey, string $accessKey): array
    {
        $url = $this->baseUrl . '/api/v1/onboarding/activate-terminal';
        $payload = $request->toArray();
        $jsonPayload = json_encode($payload);
        
        // Sign payload
        $signature = base64_encode(hash_hmac('sha512', $jsonPayload, $secretKey, true));
        
        $response = $this->postRequest($url, $payload, $signature, $accessKey);
        
        if ($response->failed()) {
            throw new \Exception('MRA API Error (' . $response->status() . '): ' . $response->body());
        }
        
        return $response->json();
    }

    /**
     * Confirmation that the terminal activation response was fully processed 
by the terminal and device should be flagged as activated
     * Path: /api/v1/onboarding/terminal-activated-confirmation
     */
    public function terminalActivatedConfirmation(\App\DTOs\Mra\ActivatedTerminalConfirmation $request, string $secretKey, string $accessKey): array
    {
        $url = $this->baseUrl . '/api/v1/onboarding/terminal-activated-confirmation';
        $payload = $request->toArray();
        $jsonPayload = json_encode($payload);
        
        // Sign payload
        $signature = base64_encode(hash_hmac('sha512', $jsonPayload, $secretKey, true));
        
        $response = $this->postRequest($url, $payload, $signature, $accessKey);
        
        if ($response->failed()) {
            throw new \Exception('MRA API Error (' . $response->status() . '): ' . $response->body());
        }
        
        return $response->json();
    }

    /**
     * Returns the last submitted online Transaction
     * Path: /api/v1/sales/last-submitted-online-transaction
     */
    public function lastSubmittedOnlineTransaction(array $data = [], string $secretKey, string $accessKey): array
    {
        $url = $this->baseUrl . '/api/v1/sales/last-submitted-online-transaction';
        $payload = $data;
        $jsonPayload = json_encode($payload);
        
        // Sign payload
        $signature = base64_encode(hash_hmac('sha512', $jsonPayload, $secretKey, true));
        
        $response = $this->postRequest($url, $payload, $signature, $accessKey);
        
        if ($response->failed()) {
            throw new \Exception('MRA API Error (' . $response->status() . '): ' . $response->body());
        }
        
        return $response->json();
    }

    /**
     * Extracts the last submitted offline Transaction
     * Path: /api/v1/sales/last-submitted-offline-transaction
     */
    public function lastSubmittedOfflineTransaction(array $data = [], string $secretKey, string $accessKey): array
    {
        $url = $this->baseUrl . '/api/v1/sales/last-submitted-offline-transaction';
        $payload = $data;
        $jsonPayload = json_encode($payload);
        
        // Sign payload
        $signature = base64_encode(hash_hmac('sha512', $jsonPayload, $secretKey, true));
        
        $response = $this->postRequest($url, $payload, $signature, $accessKey);
        
        if ($response->failed()) {
            throw new \Exception('MRA API Error (' . $response->status() . '): ' . $response->body());
        }
        
        return $response->json();
    }

    /**
     * Generates a credit note or debit note from an existing invoice.
If the updated VAT or total is higher than the original invoice, a debit note is generated.
If the updated VAT or total is lower than the original invoice, a credit note is generated.
     * Path: /api/v1/sales/process-credit-debit-note
     */
    public function processCreditDebitNote(\App\DTOs\Mra\InvoiceAdjustmentRequest $request, string $secretKey, string $accessKey): array
    {
        $url = $this->baseUrl . '/api/v1/sales/process-credit-debit-note';
        $payload = $request->toArray();
        $jsonPayload = json_encode($payload);
        
        // Sign payload
        $signature = base64_encode(hash_hmac('sha512', $jsonPayload, $secretKey, true));
        
        $response = $this->postRequest($url, $payload, $signature, $accessKey);
        
        if ($response->failed()) {
            throw new \Exception('MRA API Error (' . $response->status() . '): ' . $response->body());
        }
        
        return $response->json();
    }

    /**
     * Fetches a submitted invoice by invoice number, including validation URL and levy details.
     * Path: /api/v1/sales/get-invoice-by-number
     */
    public function getInvoiceByNumber(\App\DTOs\Mra\InvoiceLookupRequest $request, string $secretKey, string $accessKey): array
    {
        $url = $this->baseUrl . '/api/v1/sales/get-invoice-by-number';
        $payload = $request->toArray();
        $jsonPayload = json_encode($payload);
        
        // Sign payload
        $signature = base64_encode(hash_hmac('sha512', $jsonPayload, $secretKey, true));
        
        $response = $this->postRequest($url, $payload, $signature, $accessKey);
        
        if ($response->failed()) {
            throw new \Exception('MRA API Error (' . $response->status() . '): ' . $response->body());
        }
        
        return $response->json();
    }

    /**
     * submits Sales Transaction
     * Path: /api/v1/sales/submit-sales-transaction
     */
    public function submitSalesTransaction(\App\DTOs\Mra\SalesInvoice $request, string $secretKey, string $accessKey): array
    {
        $url = $this->baseUrl . '/api/v1/sales/submit-sales-transaction';
        $payload = $request->toArray();
        $jsonPayload = json_encode($payload);
        
        // Sign payload
        $signature = base64_encode(hash_hmac('sha512', $jsonPayload, $secretKey, true));
        
        $response = $this->postRequest($url, $payload, $signature, $accessKey);
        
        if ($response->failed()) {
            throw new \Exception('MRA API Error (' . $response->status() . '): ' . $response->body());
        }
        
        return $response->json();
    }

    /**
     * Transfers inventory in bulk. Only one transfer type per request is allowed.
Types: Warehouse->Site, Site->Site.
     * Path: /api/v1/stock/transfer-inventory
     */
    public function transferInventory(\App\DTOs\Mra\InventoryTransferRequest $request, string $secretKey, string $accessKey): array
    {
        $url = $this->baseUrl . '/api/v1/stock/transfer-inventory';
        $payload = $request->toArray();
        $jsonPayload = json_encode($payload);
        
        // Sign payload
        $signature = base64_encode(hash_hmac('sha512', $jsonPayload, $secretKey, true));
        
        $response = $this->postRequest($url, $payload, $signature, $accessKey);
        
        if ($response->failed()) {
            throw new \Exception('MRA API Error (' . $response->status() . '): ' . $response->body());
        }
        
        return $response->json();
    }

    /**
     * Retrieves warehouse inventory for the authenticated taxpayer.
Limits: 
- Default page size: 50 items
- Maximum page size: 200 items 
- Minimum page number: 1
     * Path: /api/v1/stock/warehouse-inventory
     */
    public function warehouseInventory(array $data = [], string $secretKey, string $accessKey): array
    {
        $url = $this->baseUrl . '/api/v1/stock/warehouse-inventory';
        $payload = $data;
        $jsonPayload = json_encode($payload);
        
        // Sign payload
        $signature = base64_encode(hash_hmac('sha512', $jsonPayload, $secretKey, true));
        
        $response = $this->postRequest($url, $payload, $signature, $accessKey);
        
        if ($response->failed()) {
            throw new \Exception('MRA API Error (' . $response->status() . '): ' . $response->body());
        }
        
        return $response->json();
    }

    /**
     * Retrieves raw material warehouse inventory for the authenticated taxpayer.
Limits: 
- Default page size: 50 items
- Maximum page size: 200 items 
- Minimum page number: 1
     * Path: /api/v1/raw-material/get-raw-material
     */
    public function getRawMaterial(array $data = [], string $secretKey, string $accessKey): array
    {
        $url = $this->baseUrl . '/api/v1/raw-material/get-raw-material';
        $payload = $data;
        $jsonPayload = json_encode($payload);
        
        // Sign payload
        $signature = base64_encode(hash_hmac('sha512', $jsonPayload, $secretKey, true));
        
        $response = $this->postRequest($url, $payload, $signature, $accessKey);
        
        if ($response->failed()) {
            throw new \Exception('MRA API Error (' . $response->status() . '): ' . $response->body());
        }
        
        return $response->json();
    }

    /**
     * This method saves stocks that were purchased from traders not in EIS. This is subject to approval.
     * Path: /api/v1/stock/submit-informal-purchase
     */
    public function submitInformalPurchase(\App\DTOs\Mra\GoodsReceivingModel $request, string $secretKey, string $accessKey): array
    {
        $url = $this->baseUrl . '/api/v1/stock/submit-informal-purchase';
        $payload = $request->toArray();
        $jsonPayload = json_encode($payload);
        
        // Sign payload
        $signature = base64_encode(hash_hmac('sha512', $jsonPayload, $secretKey, true));
        
        $response = $this->postRequest($url, $payload, $signature, $accessKey);
        
        if ($response->failed()) {
            throw new \Exception('MRA API Error (' . $response->status() . '): ' . $response->body());
        }
        
        return $response->json();
    }

    /**
     * Adds a new production record
     * Path: /api/v1/raw-material/submit-conversion
     */
    public function submitConversion(\App\DTOs\Mra\RawmaterialConversion $request, string $secretKey, string $accessKey): array
    {
        $url = $this->baseUrl . '/api/v1/raw-material/submit-conversion';
        $payload = $request->toArray();
        $jsonPayload = json_encode($payload);
        
        // Sign payload
        $signature = base64_encode(hash_hmac('sha512', $jsonPayload, $secretKey, true));
        
        $response = $this->postRequest($url, $payload, $signature, $accessKey);
        
        if ($response->failed()) {
            throw new \Exception('MRA API Error (' . $response->status() . '): ' . $response->body());
        }
        
        return $response->json();
    }

    /**
     * Submits a stock adjustment request (Increase or Decrease) for a given product barcode.
     * Path: /api/v1/stock/submit-adjustment
     */
    public function submitAdjustment(\App\DTOs\Mra\StockAdjustmentRequestDto $request, string $secretKey, string $accessKey): array
    {
        $url = $this->baseUrl . '/api/v1/stock/submit-adjustment';
        $payload = $request->toArray();
        $jsonPayload = json_encode($payload);
        
        // Sign payload
        $signature = base64_encode(hash_hmac('sha512', $jsonPayload, $secretKey, true));
        
        $response = $this->postRequest($url, $payload, $signature, $accessKey);
        
        if ($response->failed()) {
            throw new \Exception('MRA API Error (' . $response->status() . '): ' . $response->body());
        }
        
        return $response->json();
    }

    /**
     * Retrieves the list of possible stock adjustment reasons.
Requires a valid authorization token.
     * Path: /api/v1/stock/getStockAdjustmentReasons
     */
    public function getStockAdjustmentReasons(array $data = [], string $secretKey, string $accessKey): array
    {
        $url = $this->baseUrl . '/api/v1/stock/getStockAdjustmentReasons';
        $payload = $data;
        $jsonPayload = json_encode($payload);
        
        // Sign payload
        $signature = base64_encode(hash_hmac('sha512', $jsonPayload, $secretKey, true));
        
        $response = $this->postRequest($url, $payload, $signature, $accessKey);
        
        if ($response->failed()) {
            throw new \Exception('MRA API Error (' . $response->status() . '): ' . $response->body());
        }
        
        return $response->json();
    }

    /**
     * Retrieves a list of all suppliers.
Requires a valid authorization token.
     * Path: /api/v1/stock/get-suppliers
     */
    public function getSuppliers(array $data = [], string $secretKey, string $accessKey): array
    {
        $url = $this->baseUrl . '/api/v1/stock/get-suppliers';
        $payload = $data;
        $jsonPayload = json_encode($payload);
        
        // Sign payload
        $signature = base64_encode(hash_hmac('sha512', $jsonPayload, $secretKey, true));
        
        $response = $this->postRequest($url, $payload, $signature, $accessKey);
        
        if ($response->failed()) {
            throw new \Exception('MRA API Error (' . $response->status() . '): ' . $response->body());
        }
        
        return $response->json();
    }

    /**
     * Adds a new product to master products and creates zero-quantity warehouse inventory for the taxpayer.
     * Path: /api/v1/stock/add-product
     */
    public function addProduct(\App\DTOs\Mra\AddProductApiRequest $request, string $secretKey, string $accessKey): array
    {
        $url = $this->baseUrl . '/api/v1/stock/add-product';
        $payload = $request->toArray();
        $jsonPayload = json_encode($payload);
        
        // Sign payload
        $signature = base64_encode(hash_hmac('sha512', $jsonPayload, $secretKey, true));
        
        $response = $this->postRequest($url, $payload, $signature, $accessKey);
        
        if ($response->failed()) {
            throw new \Exception('MRA API Error (' . $response->status() . '): ' . $response->body());
        }
        
        return $response->json();
    }

    /**
     * Retrieves a list of HS codes for use when adding products.
     * Path: /api/v1/stock/get-hs-codes
     */
    public function getHsCodes(array $data = [], string $secretKey, string $accessKey): array
    {
        $url = $this->baseUrl . '/api/v1/stock/get-hs-codes';
        $payload = $data;
        $jsonPayload = json_encode($payload);
        
        // Sign payload
        $signature = base64_encode(hash_hmac('sha512', $jsonPayload, $secretKey, true));
        
        $response = $this->postRequest($url, $payload, $signature, $accessKey);
        
        if ($response->failed()) {
            throw new \Exception('MRA API Error (' . $response->status() . '): ' . $response->body());
        }
        
        return $response->json();
    }

    /**
     * Retrieves active units of measure for use when adding products.
     * Path: /api/v1/stock/get-units-of-measure
     */
    public function getUnitsOfMeasure(array $data = [], string $secretKey, string $accessKey): array
    {
        $url = $this->baseUrl . '/api/v1/stock/get-units-of-measure';
        $payload = $data;
        $jsonPayload = json_encode($payload);
        
        // Sign payload
        $signature = base64_encode(hash_hmac('sha512', $jsonPayload, $secretKey, true));
        
        $response = $this->postRequest($url, $payload, $signature, $accessKey);
        
        if ($response->failed()) {
            throw new \Exception('MRA API Error (' . $response->status() . '): ' . $response->body());
        }
        
        return $response->json();
    }

    /**
     * Validates the authenticity and validity of a VAT 5 Certificate.
     * Path: /api/v1/utilities/validate-vat5-certificate
     */
    public function validateVat5Certificate(\App\DTOs\Mra\Vat5CertificateValidationRequest $request, string $secretKey, string $accessKey): array
    {
        $url = $this->baseUrl . '/api/v1/utilities/validate-vat5-certificate';
        $payload = $request->toArray();
        $jsonPayload = json_encode($payload);
        
        // Sign payload
        $signature = base64_encode(hash_hmac('sha512', $jsonPayload, $secretKey, true));
        
        $response = $this->postRequest($url, $payload, $signature, $accessKey);
        
        if ($response->failed()) {
            throw new \Exception('MRA API Error (' . $response->status() . '): ' . $response->body());
        }
        
        return $response->json();
    }

    /**
     * Retrieves the blocking message for a terminal, if it is currently blocked
     * Path: /api/v1/utilities/get-terminal-blocking-message
     */
    public function getTerminalBlockingMessage(\App\DTOs\Mra\TerminalBlockRequest $request, string $secretKey, string $accessKey): array
    {
        $url = $this->baseUrl . '/api/v1/utilities/get-terminal-blocking-message';
        $payload = $request->toArray();
        $jsonPayload = json_encode($payload);
        
        // Sign payload
        $signature = base64_encode(hash_hmac('sha512', $jsonPayload, $secretKey, true));
        
        $response = $this->postRequest($url, $payload, $signature, $accessKey);
        
        if ($response->failed()) {
            throw new \Exception('MRA API Error (' . $response->status() . '): ' . $response->body());
        }
        
        return $response->json();
    }

    /**
     * Checks if a terminal has been unblocked
     * Path: /api/v1/utilities/check-terminal-unblock-status
     */
    public function checkTerminalUnblockStatus(\App\DTOs\Mra\TerminalBlockRequest $request, string $secretKey, string $accessKey): array
    {
        $url = $this->baseUrl . '/api/v1/utilities/check-terminal-unblock-status';
        $payload = $request->toArray();
        $jsonPayload = json_encode($payload);
        
        // Sign payload
        $signature = base64_encode(hash_hmac('sha512', $jsonPayload, $secretKey, true));
        
        $response = $this->postRequest($url, $payload, $signature, $accessKey);
        
        if ($response->failed()) {
            throw new \Exception('MRA API Error (' . $response->status() . '): ' . $response->body());
        }
        
        return $response->json();
    }

    /**
     * Checks whether a TIN requires a purchase authorization code.
     * Path: /api/v1/utilities/check-tin-authorization-requirement
     */
    public function checkTinAuthorizationRequirement(\App\DTOs\Mra\TinAuthorizationRequirementRequest $request, string $secretKey, string $accessKey): array
    {
        $url = $this->baseUrl . '/api/v1/utilities/check-tin-authorization-requirement';
        $payload = $request->toArray();
        $jsonPayload = json_encode($payload);
        
        // Sign payload
        $signature = base64_encode(hash_hmac('sha512', $jsonPayload, $secretKey, true));
        
        $response = $this->postRequest($url, $payload, $signature, $accessKey);
        
        if ($response->failed()) {
            throw new \Exception('MRA API Error (' . $response->status() . '): ' . $response->body());
        }
        
        return $response->json();
    }

    /**
     * This checks the authenticity and validity of an authorization code
     * Path: /api/v1/utilities/validate-authorization-code
     */
    public function validateAuthorizationCode(\App\DTOs\Mra\UnValidatedAuthorizationCode $request, string $secretKey, string $accessKey): array
    {
        $url = $this->baseUrl . '/api/v1/utilities/validate-authorization-code';
        $payload = $request->toArray();
        $jsonPayload = json_encode($payload);
        
        // Sign payload
        $signature = base64_encode(hash_hmac('sha512', $jsonPayload, $secretKey, true));
        
        $response = $this->postRequest($url, $payload, $signature, $accessKey);
        
        if ($response->failed()) {
            throw new \Exception('MRA API Error (' . $response->status() . '): ' . $response->body());
        }
        
        return $response->json();
    }

    /**
     * Checks if a client is able to communicate with the server
     * Path: /api/v1/utilities/ping
     */
    public function ping(array $data = [], string $secretKey, string $accessKey): array
    {
        $url = $this->baseUrl . '/api/v1/utilities/ping';
        $payload = $data;
        $jsonPayload = json_encode($payload);
        
        // Sign payload
        $signature = base64_encode(hash_hmac('sha512', $jsonPayload, $secretKey, true));
        
        $response = $this->postRequest($url, $payload, $signature, $accessKey);
        
        if ($response->failed()) {
            throw new \Exception('MRA API Error (' . $response->status() . '): ' . $response->body());
        }
        
        return $response->json();
    }

    /**
     * Checks the status of a product as to where it ti mapped to UNSPSC at MRA side
     * Path: /api/v1/utilities/product-status
     */
    public function productStatus(\App\DTOs\Mra\ProductIdentifier $request, string $secretKey, string $accessKey): array
    {
        $url = $this->baseUrl . '/api/v1/utilities/product-status';
        $payload = $request->toArray();
        $jsonPayload = json_encode($payload);
        
        // Sign payload
        $signature = base64_encode(hash_hmac('sha512', $jsonPayload, $secretKey, true));
        
        $response = $this->postRequest($url, $payload, $signature, $accessKey);
        
        if ($response->failed()) {
            throw new \Exception('MRA API Error (' . $response->status() . '): ' . $response->body());
        }
        
        return $response->json();
    }

    /**
     * Creates a void request for an existing invoice (receipt).
     * Path: /api/v1/sales/cancel-receipt
     */
    public function cancelReceipt(\App\DTOs\Mra\VoidReceiptCreateDto $request, string $secretKey, string $accessKey): array
    {
        $url = $this->baseUrl . '/api/v1/sales/cancel-receipt';
        $payload = $request->toArray();
        $jsonPayload = json_encode($payload);
        
        // Sign payload
        $signature = base64_encode(hash_hmac('sha512', $jsonPayload, $secretKey, true));
        
        $response = $this->postRequest($url, $payload, $signature, $accessKey);
        
        if ($response->failed()) {
            throw new \Exception('MRA API Error (' . $response->status() . '): ' . $response->body());
        }
        
        return $response->json();
    }

    /**
     * Retrieves void (cancelled receipt) requests.
     * Path: /api/v1/sales/get-void-receipts
     */
    public function getVoidReceipts(\App\DTOs\Mra\VoidReceiptFilterDto $request, string $secretKey, string $accessKey): array
    {
        $url = $this->baseUrl . '/api/v1/sales/get-void-receipts';
        $payload = $request->toArray();
        $jsonPayload = json_encode($payload);
        
        // Sign payload
        $signature = base64_encode(hash_hmac('sha512', $jsonPayload, $secretKey, true));
        
        $response = $this->postRequest($url, $payload, $signature, $accessKey);
        
        if ($response->failed()) {
            throw new \Exception('MRA API Error (' . $response->status() . '): ' . $response->body());
        }
        
        return $response->json();
    }

    /**
     * Retrieves the latest products for a terminal site
     * Path: /api/v1/utilities/get-terminal-site-products
     */
    public function getTerminalSiteProducts(\App\DTOs\Mra\InventoryRequest $request, string $secretKey, string $accessKey): array
    {
        $url = $this->baseUrl . '/api/v1/utilities/get-terminal-site-products';
        $payload = $request->toArray();
        $jsonPayload = json_encode($payload);
        
        // Sign payload
        $signature = base64_encode(hash_hmac('sha512', $jsonPayload, $secretKey, true));
        
        $response = $this->postRequest($url, $payload, $signature, $accessKey);
        
        if ($response->failed()) {
            throw new \Exception('MRA API Error (' . $response->status() . '): ' . $response->body());
        }
        
        return $response->json();
    }

    /**
     * Handles the initial inventory upload for a taxpayer, supporting phased uploads (up to 50 products per batch) and a last batch indicator.
If not the last batch, stores items in staging table. If last batch, processes all items from staging table.
     * Path: /api/v1/utilities/taxpayer-initial-inventory-upload
     */
    public function taxpayerInitialInventoryUpload(\App\DTOs\Mra\TaxpayerInitialInventoryUploadRequest $request, string $secretKey, string $accessKey): array
    {
        $url = $this->baseUrl . '/api/v1/utilities/taxpayer-initial-inventory-upload';
        $payload = $request->toArray();
        $jsonPayload = json_encode($payload);
        
        // Sign payload
        $signature = base64_encode(hash_hmac('sha512', $jsonPayload, $secretKey, true));
        
        $response = $this->postRequest($url, $payload, $signature, $accessKey);
        
        if ($response->failed()) {
            throw new \Exception('MRA API Error (' . $response->status() . '): ' . $response->body());
        }
        
        return $response->json();
    }

    /**
     * Helper to send POST requests with dynamic access-key/Bearer token logic.
     */
    protected function postRequest(string $url, array $payload, string $signature, string $accessKey): \Illuminate\Http\Client\Response
    {
        $headers = [
            'x-signature' => $signature,
            'Content-Type' => 'application/json',
        ];

        if (str_starts_with($accessKey, 'eyJ')) {
            $headers['Authorization'] = 'Bearer ' . $accessKey;
        } else {
            $headers['x-access-key'] = $accessKey;
        }

        return \Illuminate\Support\Facades\Http::withHeaders($headers)->post($url, $payload);
    }

}
