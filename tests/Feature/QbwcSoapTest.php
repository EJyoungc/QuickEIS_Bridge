<?php

use App\Models\Tenant;
use App\Models\QuickbooksConnection;
use App\Models\Invoice;
use App\Models\AuditLog;
use App\Jobs\SubmitInvoiceToMra;
use App\Services\Mra\MraClient;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;

test('qbwc soap serverVersion returns correct xml', function () {
    $requestXml = '<?xml version="1.0" encoding="utf-8"?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Body>
    <serverVersion xmlns="http://developer.intuit.com/">
      <strVersion>1.0.0</strVersion>
    </serverVersion>
  </soap:Body>
</soap:Envelope>';

    $response = $this->call('POST', '/qbwc', [], [], [], [
        'HTTP_CONTENT_TYPE' => 'text/xml; charset=utf-8',
        'CONTENT_TYPE' => 'text/xml; charset=utf-8'
    ], $requestXml);

    $response->assertStatus(200);
    $response->assertSee('serverVersionResult');
    $response->assertSee('1.0.0');

    // Assert audit log was written
    expect(AuditLog::count())->toBeGreaterThan(0);
});

test('qbwc soap authenticate checks credentials and returns ticket', function () {
    $tenant = Tenant::create([
        'name' => 'Test Company',
        'company_tin' => 'TIN12345',
        'mra_vendor_access_key' => 'access-key-123',
        'status' => 'active'
    ]);

    $conn = QuickbooksConnection::create([
        'tenant_id' => $tenant->id,
        'username' => 'qb_user',
        'password_hash' => Hash::make('secret_pass'),
    ]);

    $requestXml = '<?xml version="1.0" encoding="utf-8"?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Body>
    <authenticate xmlns="http://developer.intuit.com/">
      <strUserName>qb_user</strUserName>
      <strPassword>secret_pass</strPassword>
    </authenticate>
  </soap:Body>
</soap:Envelope>';

    $response = $this->call('POST', '/qbwc', [], [], [], [
        'HTTP_CONTENT_TYPE' => 'text/xml; charset=utf-8',
        'CONTENT_TYPE' => 'text/xml; charset=utf-8'
    ], $requestXml);

    $response->assertStatus(200);
    $response->assertSee('authenticateResult');
    
    // Check if a ticket has been set
    $conn->refresh();
    expect($conn->ticket)->not->toBeNull();
    $response->assertSee($conn->ticket);
});

test('submit invoice job signs and calls MRA API', function () {
    Http::fake([
        'dev-eis-api.mra.mw/*' => Http::response([
            'result' => [
                'fiscalInvoiceNumber' => 'FISCAL-123',
                'receiptNumber' => 'REC-456',
                'qrCode' => 'https://verify.mra.mw/FISCAL-123'
            ]
        ], 200)
    ]);

            $tenant = Tenant::create([
            'name' => 'Active Company',
            'company_tin' => 'TIN-ACTIVE',
            'mra_vendor_access_key' => 'acc-key',
            'secret_key' => 'sec-key',
            'jwt_token' => 'eyJdummy.jwt.token',
            'terminal_id' => 'term-1',
            'status' => 'active'
        ]);

    $invoice = Invoice::create([
        'tenant_id' => $tenant->id,
        'qb_txn_id' => 'TXN-001',
        'qb_edit_sequence' => '1',
        'qb_invoice_number' => 'INV-1001',
        'customer_name' => 'John Doe',
        'transaction_date' => now(),
        'amount_before_tax' => 100.00,
        'tax_amount' => 16.50,
        'total_amount' => 116.50,
        'status' => 'pending'
    ]);

    $job = new SubmitInvoiceToMra($invoice->id);
    $job->handle(new MraClient());

    $invoice->refresh();
    expect($invoice->status)->toBe('submitted');
    expect($invoice->mra_invoice_number)->toBe('FISCAL-123');
    expect($invoice->mra_receipt_number)->toBe('REC-456');
    expect($invoice->mra_qr_code)->toBe('https://verify.mra.mw/FISCAL-123');
});

test('livewire dashboard can delete connection and download QWC file', function () {
    $tenant = Tenant::create([
        'name' => 'Active Company',
        'company_tin' => 'TIN-ACTIVE',
        'mra_vendor_access_key' => 'acc-key',
        'status' => 'active'
    ]);

    $conn = QuickbooksConnection::create([
        'tenant_id' => $tenant->id,
        'username' => 'qb_user',
        'password_hash' => Hash::make('secret_pass'),
    ]);

    $component = Livewire\Livewire::test(App\Livewire\Connections::class);
    
    // Test downloadQwc response
    $response = $component->call('downloadQwc', $conn->id);
    $response->assertStatus(200);

    // Test delete connection
    $component->call('deleteConnection', $conn->id);
    expect(QuickbooksConnection::find($conn->id))->toBeNull();
});
