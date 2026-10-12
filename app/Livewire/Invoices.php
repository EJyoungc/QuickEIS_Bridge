<?php

namespace App\Livewire;

use App\Models\Tenant;
use App\Models\Invoice;
use App\Jobs\SubmitInvoiceToMra;
use Livewire\Component;
use Livewire\WithPagination;

class Invoices extends Component
{
    use WithPagination;

    // Filters and Search
    public string $invoiceSearch = '';
    public string $invoiceFilterStatus = '';

    // Modal view state
    public ?int $viewingInvoiceId = null;
    public ?Invoice $selectedInvoice = null;

    public function viewInvoice(int $invoiceId): void
    {
        $this->viewingInvoiceId = $invoiceId;
        $this->selectedInvoice = Invoice::with('tenant')->find($invoiceId);
    }

    public function closeViewModal(): void
    {
        $this->reset(['viewingInvoiceId', 'selectedInvoice']);
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

    public function deleteInvoice(int $invoiceId): void
    {
        $invoice = Invoice::find($invoiceId);
        if ($invoice) {
            $invoice->delete();
            session()->flash('invoice_success', 'Invoice deleted successfully.');
        }
    }

    public function render()
    {
        $invoices = Invoice::with('tenant')
            ->when($this->invoiceSearch, function ($query) {
                $query->where('qb_invoice_number', 'like', '%' . $this->invoiceSearch . '%')
                    ->orWhere('customer_name', 'like', '%' . $this->invoiceSearch . '%');
            })
            ->when($this->invoiceFilterStatus, function ($query) {
                $query->where('status', $this->invoiceFilterStatus);
            })
            ->latest()
            ->paginate(10);

        return view('livewire.invoices', [
            'invoices' => $invoices,
        ]);
    }
}
