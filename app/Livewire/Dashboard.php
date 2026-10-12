<?php

namespace App\Livewire;

use App\Models\AuditLog;
use App\Models\Invoice;
use App\Models\Tenant;
use App\Models\QuickbooksConnection;
use App\Jobs\SubmitInvoiceToMra;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;

class Dashboard extends Component
{
    use WithPagination;

    // Navigation Tab
    public string $activeTab = 'tenants';

    // Search and Filters
    public string $invoiceSearch = '';
    public string $invoiceFilterStatus = '';
    public string $logSearch = '';

    // Selected Audit Log for Modal
    public ?int $selectedLogId = null;

    // Tenant Creation Form State
    public string $tenantName = '';
    public string $tenantTin = '';
    public string $tenantAccessKey = '';
    public string $tenantActivationCode = '';

    // Quickbooks Connection Creation Form State
    public ?int $selectedTenantId = null;
    public string $qbUsername = '';
    public string $qbPassword = '';

    public function selectLog(int $logId): void
    {
        $this->selectedLogId = $logId;
    }

    public function closeLogModal(): void
    {
        $this->selectedLogId = null;
    }

    public function createTenant(): void
    {
        $this->validate([
            'tenantName' => 'required|string|max:255',
            'tenantTin' => 'required|string|max:255|unique:tenants,company_tin',
            'tenantAccessKey' => 'required|string|max:255',
        ]);

        Tenant::create([
            'name' => $this->tenantName,
            'company_tin' => $this->tenantTin,
            'mra_vendor_access_key' => $this->tenantAccessKey,
            'activation_code' => $this->tenantActivationCode,
            'status' => 'pending',
        ]);

        $this->reset(['tenantName', 'tenantTin', 'tenantAccessKey', 'tenantActivationCode']);
        session()->flash('tenant_success', 'Tenant company registered successfully.');
    }

    public function onboardTerminal(int $tenantId): void
    {
        $tenant = Tenant::find($tenantId);
        if ($tenant) {
            if (empty($tenant->activation_code)) {
                session()->flash('tenant_error', 'Activation code is required for terminal onboarding.');
                return;
            }

            // Simulate signed MRA onboarding call and receive credentials
            $tenant->update([
                'status' => 'active',
                'secret_key' => 'mra-sec-key-' . rand(100000, 999999),
                'terminal_id' => 'TERM-' . rand(1000, 9999),
                'terminal_token' => 'TOK-' . rand(100000, 999999),
            ]);

            session()->flash('tenant_success', "Terminal '{$tenant->name}' onboarded successfully.");
        }
    }

    public function createQbConnection(): void
    {
        $this->validate([
            'selectedTenantId' => 'required|exists:tenants,id',
            'qbUsername' => 'required|string|unique:quickbooks_connections,username',
            'qbPassword' => 'required|string|min:6',
        ]);

        QuickbooksConnection::create([
            'tenant_id' => $this->selectedTenantId,
            'username' => $this->qbUsername,
            'password_hash' => Hash::make($this->qbPassword),
            'sync_status' => 'idle',
        ]);

        $this->reset(['selectedTenantId', 'qbUsername', 'qbPassword']);
        session()->flash('qb_success', 'QuickBooks Web Connector credential created successfully.');
    }

    public function deleteConnection(int $connectionId): void
    {
        $conn = QuickbooksConnection::find($connectionId);
        if ($conn) {
            $conn->delete();
            session()->flash('qb_success', 'QuickBooks Connection removed successfully.');
        }
    }

    public function downloadQwc(int $connectionId)
    {
        $conn = QuickbooksConnection::with('tenant')->find($connectionId);
        if (!$conn) {
            session()->flash('qb_error', 'Connection not found.');
            return;
        }

        $xml = '<?xml version="1.0" encoding="utf-8"?>
<QBWCXML>
  <AppName>EIS Bridge - ' . htmlspecialchars($conn->tenant->name) . '</AppName>
  <AppID></AppID>
  <AppURL>' . htmlspecialchars(url('/qbwc')) . '</AppURL>
  <AppDescription>EIS Bridge QuickBooks Integration for MRA Compliance</AppDescription>
  <AppSupport>' . htmlspecialchars(url('/')) . '</AppSupport>
  <UserName>' . htmlspecialchars($conn->username) . '</UserName>
  <OwnerID>{' . \Illuminate\Support\Str::uuid() . '}</OwnerID>
  <FileID>{' . \Illuminate\Support\Str::uuid() . '}</FileID>
  <QBType>QBFS</QBType>
  <Scheduler>
    <RunEveryNMinutes>5</RunEveryNMinutes>
  </Scheduler>
</QBWCXML>';

        return response()->streamDownload(function () use ($xml) {
            echo $xml;
        }, 'eis-bridge-' . \Illuminate\Support\Str::kebab($conn->tenant->name) . '.qwc', [
            'Content-Type' => 'text/xml',
        ]);
    }

    public function replaySync(int $invoiceId): void
    {
        $invoice = Invoice::find($invoiceId);
        if ($invoice) {
            $invoice->update(['status' => 'pending']);
            SubmitInvoiceToMra::dispatch($invoice->id);
            session()->flash('invoice_success', "Invoice #{$invoice->qb_invoice_number} sync replayed successfully.");
        }
    }

    public function simulateInvoice(): void
    {
        $tenant = Tenant::where('status', 'active')->first();
        if (!$tenant) {
            session()->flash('tenant_error', 'No active/onboarded tenant found. Please register and onboard a tenant first.');
            return;
        }

        $invNumber = 'SIM-' . rand(1000, 9999);
        $invoice = Invoice::create([
            'tenant_id' => $tenant->id,
            'qb_txn_id' => 'TXN-' . rand(100000, 999999),
            'qb_edit_sequence' => '1',
            'qb_invoice_number' => $invNumber,
            'customer_name' => 'Demo Customer Ltd',
            'transaction_date' => now(),
            'amount_before_tax' => 1000.00,
            'tax_amount' => 165.00,
            'total_amount' => 1165.00,
            'status' => 'pending',
        ]);

        SubmitInvoiceToMra::dispatch($invoice->id);
        session()->flash('invoice_success', "Simulated Inbound QuickBooks invoice #{$invNumber} created. Check queue listener output!");
    }

    public function clearLogs(): void
    {
        AuditLog::query()->delete();
        session()->flash('logs_success', 'All audit logs cleared successfully.');
    }

    public function render()
    {
        // Fetch stats
        $stats = [
            'total_tenants' => Tenant::count(),
            'active_terminals' => Tenant::where('status', 'active')->count(),
            'synced_invoices' => Invoice::where('status', 'submitted')->count(),
            'failed_invoices' => Invoice::where('status', 'failed')->count(),
        ];

        // Fetch tenants list
        $tenants = Tenant::with('connection')->latest()->get();

        // Fetch Invoices with search and filter
        $invoices = Invoice::with('tenant')
            ->when($this->invoiceSearch, function ($query) {
                $query->where('qb_invoice_number', 'like', '%' . $this->invoiceSearch . '%')
                    ->orWhere('customer_name', 'like', '%' . $this->invoiceSearch . '%');
            })
            ->when($this->invoiceFilterStatus, function ($query) {
                $query->where('status', $this->invoiceFilterStatus);
            })
            ->latest()
            ->paginate(10, pageName: 'invoices-page');

        // Fetch Audit Logs with search
        $auditLogs = AuditLog::with(['tenant', 'invoice'])
            ->when($this->logSearch, function ($query) {
                $query->where('type', 'like', '%' . $this->logSearch . '%')
                    ->orWhere('payload', 'like', '%' . $this->logSearch . '%');
            })
            ->latest()
            ->paginate(15, pageName: 'logs-page');

        // Fetch connections list
        $connections = QuickbooksConnection::with('tenant')->latest()->get();

        $activeLog = $this->selectedLogId ? AuditLog::find($this->selectedLogId) : null;

        return view('livewire.dashboard', [
            'stats' => $stats,
            'tenants' => $tenants,
            'connections' => $connections,
            'invoices' => $invoices,
            'auditLogs' => $auditLogs,
            'activeLog' => $activeLog,
        ]);
    }
}
