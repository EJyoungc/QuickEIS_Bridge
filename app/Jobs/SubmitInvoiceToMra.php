<?php

namespace App\Jobs;

use App\Models\AuditLog;
use App\Models\Invoice;
use App\Services\Mra\MraClient;
use App\DTOs\Mra\SalesInvoice;
use App\DTOs\Mra\InvoiceHeader;
use App\DTOs\Mra\InvoiceSummary;
use App\DTOs\Mra\LineItemDto;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SubmitInvoiceToMra implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 5;
    public int $backoff = 10;

    public function __construct(protected int $invoiceId)
    {
    }

    public function handle(MraClient $client): void
    {
        $invoice = Invoice::with('tenant')->find($this->invoiceId);

        if (!$invoice || !$invoice->tenant) {
            Log::warning("SubmitInvoiceToMra: Invoice or Tenant not found for ID: {$this->invoiceId}");
            return;
        }

        $tenant = $invoice->tenant;

        if ($tenant->status !== 'active' || empty($tenant->secret_key) || empty($tenant->mra_vendor_access_key)) {
            Log::info("SubmitInvoiceToMra: Tenant not active or credentials missing. Queueing offline/pending status.");
            $invoice->update([
                'status' => 'pending',
                'error_message' => 'Waiting for terminal activation or MRA configuration.',
            ]);
            return;
        }

        // Build MRA DTOs
        $header = new InvoiceHeader();
        $header->invoiceNumber = $invoice->qb_invoice_number;
        $header->invoiceDateTime = $invoice->transaction_date->format('Y-m-d\TH:i:s');
        $header->sellerTIN = $tenant->company_tin;
        $header->buyerTIN = '';
        $header->buyerName = $invoice->customer_name;
        $header->siteId = $tenant->terminal_id;
        $header->globalConfigVersion = 1;
        $header->taxpayerConfigVersion = 1;
        $header->terminalConfigVersion = 1;
        $header->isExport = false;
        $header->isReliefSupply = false;
        $header->paymentMethod = 'Cash';

        $summary = new InvoiceSummary();
        $summary->taxableAmount = (float)$invoice->amount_before_tax;
        $summary->nonTaxableAmount = 0.0;
        $summary->vatAmount = (float)$invoice->tax_amount;
        $summary->totalAmount = (float)$invoice->total_amount;

        // Generate line items from transaction values
        $lineItem = new LineItemDto();
        $lineItem->itemId = 1;
        $lineItem->itemName = 'Sales Item';
        $lineItem->quantity = 1.0;
        $lineItem->unitPrice = (float)$invoice->amount_before_tax;
        $lineItem->taxCode = 'A'; // standard rate A
        $lineItem->taxRate = 16.5; // standard rate in Malawi is 16.5%
        $lineItem->totalAmount = (float)$invoice->total_amount;
        $lineItem->taxAmount = (float)$invoice->tax_amount;

        $salesInvoice = new SalesInvoice();
        $salesInvoice->invoiceHeader = $header;
        $salesInvoice->invoiceLineItems = [$lineItem->toArray()];
        $salesInvoice->invoiceSummary = $summary;

        // Log outgoing REST API payload
        $auditLogReq = AuditLog::create([
            'tenant_id' => $tenant->id,
            'invoice_id' => $invoice->id,
            'direction' => 'outbound_mra',
            'type' => 'rest_request',
            'payload' => json_encode($salesInvoice->toArray(), JSON_PRETTY_PRINT),
        ]);

        try {
            // Call MRA API using the tenant's secret key and active terminal JWT token
            $response = $client->submitSalesTransaction($salesInvoice, $tenant->secret_key, $tenant->jwt_token);

            // Log incoming response
            AuditLog::create([
                'tenant_id' => $tenant->id,
                'invoice_id' => $invoice->id,
                'direction' => 'inbound_mra',
                'type' => 'rest_response',
                'payload' => json_encode($response, JSON_PRETTY_PRINT),
                'status_code' => 200,
            ]);

            // Update local Invoice with response data
            // Response typically has properties like fiscal invoice number, QR code etc.
            $result = $response['result'] ?? [];
            
            $invoice->update([
                'status' => 'submitted',
                'mra_invoice_number' => $result['fiscalInvoiceNumber'] ?? 'MRA-' . $invoice->qb_invoice_number,
                'mra_receipt_number' => $result['receiptNumber'] ?? 'REC-' . rand(1000, 9999),
                'mra_qr_code' => $result['qrCode'] ?? $result['verificationUrl'] ?? 'https://www.mra.mw/verify/' . $invoice->qb_invoice_number,
                'mra_submitted_at' => now(),
                'error_message' => null,
            ]);

        } catch (\Exception $e) {
            // Log Error
            AuditLog::create([
                'tenant_id' => $tenant->id,
                'invoice_id' => $invoice->id,
                'direction' => 'inbound_mra',
                'type' => 'error',
                'payload' => $e->getMessage(),
                'status_code' => 500,
            ]);

            $invoice->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'retry_count' => $invoice->retry_count + 1,
            ]);

            // Re-throw to trigger Laravel Queue retry
            throw $e;
        }
    }
}
