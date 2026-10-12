<div class="space-y-8">
    <!-- Livewire Notifications / Flashes -->
    @if (session()->has('invoice_success'))
        <flux:callout variant="success" title="Success">
            {{ session('invoice_success') }}
        </flux:callout>
    @endif
    @if (session()->has('tenant_error'))
        <flux:callout variant="danger" title="Error">
            {{ session('tenant_error') }}
        </flux:callout>
    @endif

    <div class="bg-slate-800/30 rounded-2xl border border-slate-700 shadow-xl overflow-hidden space-y-4">
        <div class="px-6 py-5 border-b border-slate-700 bg-slate-800/50 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <flux:heading size="lg">Synchronized Invoices</flux:heading>
            <!-- Search and filters -->
            <div class="flex flex-wrap items-center gap-3">
                <flux:button wire:click="simulateInvoice" variant="filled" size="sm" class="!bg-cyan-500 hover:!bg-cyan-455 text-white">
                    Simulate QB Transaction
                </flux:button>
                <flux:input wire:model.live.debounce.300ms="invoiceSearch" placeholder="Search invoice..." />
                <select wire:model.live="invoiceFilterStatus" class="bg-slate-900 border border-slate-700 rounded-xl px-4 py-2 text-sm text-white focus:outline-none focus:border-cyan-400">
                    <option value="">All Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="submitted">Submitted</option>
                    <option value="failed">Failed</option>
                    <option value="voided">Voided</option>
                </select>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-300">
                <thead class="bg-slate-800/75 text-xs uppercase tracking-wider text-slate-400">
                    <tr>
                        <th class="px-6 py-4">Tenant Company</th>
                        <th class="px-6 py-4">QB Invoice Ref</th>
                        <th class="px-6 py-4">Customer Name</th>
                        <th class="px-6 py-4">Total Amount</th>
                        <th class="px-6 py-4">MRA Reference</th>
                        <th class="px-6 py-4">Sync Status</th>
                        <th class="px-6 py-4">Synced Date</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700">
                    @forelse ($invoices as $i)
                        <tr wire:key="invoice-row-{{ $i->id }}" class="hover:bg-slate-800/40">
                            <td class="px-6 py-4 font-semibold text-white">{{ $i->tenant->name }}</td>
                            <td class="px-6 py-4">{{ $i->qb_invoice_number }}</td>
                            <td class="px-6 py-4">{{ $i->customer_name ?? '-' }}</td>
                            <td class="px-6 py-4 font-bold">{{ number_format($i->total_amount, 2) }} MWK</td>
                            <td class="px-6 py-4 space-y-1">
                                @if ($i->mra_invoice_number)
                                    <div class="text-xs"><span class="text-slate-400">Fiscal No:</span> {{ $i->mra_invoice_number }}</div>
                                    <div class="text-xs"><span class="text-slate-400">Receipt:</span> {{ $i->mra_receipt_number }}</div>
                                @else
                                    <span class="text-xs text-slate-500">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if ($i->status === 'submitted')
                                    <flux:badge color="emerald" size="sm">Submitted</flux:badge>
                                @elseif ($i->status === 'failed')
                                    <flux:badge color="rose" size="sm">Failed</flux:badge>
                                @else
                                    <flux:badge color="amber" size="sm">Pending Sync</flux:badge>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-400">{{ $i->created_at->format('M d, Y H:i') }}</td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <flux:button wire:click="viewInvoice({{ $i->id }})" variant="ghost" size="sm" icon="eye" title="View Details">
                                </flux:button>
                                <flux:button wire:click="replaySync({{ $i->id }})" variant="primary" size="sm">
                                    Replay Sync
                                </flux:button>
                                <flux:button
                                    wire:click="deleteInvoice({{ $i->id }})"
                                    wire:confirm="Are you sure you want to delete invoice #{{ $i->qb_invoice_number }}? This action cannot be undone."
                                    variant="danger"
                                    size="sm"
                                >
                                    Delete
                                </flux:button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-slate-500 font-semibold">No invoices found matching criteria.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-6 border-t border-slate-700 bg-slate-800/20">
            {{ $invoices->links() }}
        </div>
    </div>

    <!-- Invoice Details Modal -->
    @if ($selectedInvoice)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4 overflow-y-auto">
            <div class="bg-slate-800 border border-slate-700 w-full max-w-2xl p-6 rounded-2xl shadow-2xl space-y-6 text-white max-h-[90vh] overflow-y-auto">
                <div class="flex justify-between items-center border-b border-slate-700 pb-3">
                    <flux:heading size="lg">Invoice Detail: #{{ $selectedInvoice->qb_invoice_number }}</flux:heading>
                    <flux:button wire:click="closeViewModal" variant="ghost" icon="x-mark" size="sm"></flux:button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                    <!-- QuickBooks / Core Info -->
                    <div class="space-y-4">
                        <flux:heading size="md" class="text-cyan-400">QuickBooks Info</flux:heading>
                        <div>
                            <span class="block text-xs text-slate-400">Customer Name</span>
                            <span class="font-semibold text-white">{{ $selectedInvoice->customer_name ?? 'N/A' }}</span>
                        </div>
                        <div>
                            <span class="block text-xs text-slate-400">QuickBooks Txn ID</span>
                            <span class="font-mono text-xs text-white">{{ $selectedInvoice->qb_txn_id }}</span>
                        </div>
                        <div>
                            <span class="block text-xs text-slate-400">QuickBooks Edit Sequence</span>
                            <span class="font-mono text-xs text-white">{{ $selectedInvoice->qb_edit_sequence }}</span>
                        </div>
                        <div>
                            <span class="block text-xs text-slate-400">Transaction Date</span>
                            <span class="text-white">{{ $selectedInvoice->transaction_date ? $selectedInvoice->transaction_date->format('M d, Y') : 'N/A' }}</span>
                        </div>
                    </div>

                    <!-- Financial Breakdown -->
                    <div class="space-y-4">
                        <flux:heading size="md" class="text-cyan-400">Financial Breakdown</flux:heading>
                        <div>
                            <span class="block text-xs text-slate-400">Amount Before Tax</span>
                            <span class="font-semibold text-white">{{ number_format($selectedInvoice->amount_before_tax, 2) }} MWK</span>
                        </div>
                        <div>
                            <span class="block text-xs text-slate-400">Tax Amount</span>
                            <span class="font-semibold text-white">{{ number_format($selectedInvoice->tax_amount, 2) }} MWK</span>
                        </div>
                        <div class="pt-2 border-t border-slate-700">
                            <span class="block text-xs text-slate-400">Total Amount</span>
                            <span class="text-lg font-bold text-cyan-300">{{ number_format($selectedInvoice->total_amount, 2) }} MWK</span>
                        </div>
                    </div>
                </div>

                <!-- MRA / Compliance Info -->
                <div class="border-t border-slate-700 pt-6 space-y-4">
                    <flux:heading size="md" class="text-cyan-400">MRA EIS Compliance Status</flux:heading>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="block text-xs text-slate-400">Submission Status</span>
                            <div class="mt-1">
                                @if ($selectedInvoice->status === 'submitted')
                                    <flux:badge color="emerald" size="sm">Submitted Successfully</flux:badge>
                                @elseif ($selectedInvoice->status === 'failed')
                                    <flux:badge color="rose" size="sm">Failed</flux:badge>
                                @else
                                    <flux:badge color="amber" size="sm">Pending Sync</flux:badge>
                                @endif
                            </div>
                        </div>
                        <div>
                            <span class="block text-xs text-slate-400">MRA Submission Date</span>
                            <span class="text-white">{{ $selectedInvoice->mra_submitted_at ? $selectedInvoice->mra_submitted_at->format('M d, Y H:i:s') : 'Not submitted yet' }}</span>
                        </div>
                        @if ($selectedInvoice->mra_invoice_number)
                            <div>
                                <span class="block text-xs text-slate-400">MRA Fiscal Invoice Number</span>
                                <span class="font-semibold text-white">{{ $selectedInvoice->mra_invoice_number }}</span>
                            </div>
                            <div>
                                <span class="block text-xs text-slate-400">MRA Receipt Number</span>
                                <span class="font-semibold text-white">{{ $selectedInvoice->mra_receipt_number }}</span>
                            </div>
                        @endif
                    </div>

                    @if ($selectedInvoice->error_message)
                        <div class="bg-rose-950/40 border border-rose-800/60 p-4 rounded-xl space-y-1">
                            <span class="block text-xs text-rose-300 font-bold">Error Message</span>
                            <p class="text-xs text-rose-100 font-mono">{{ $selectedInvoice->error_message }}</p>
                        </div>
                    @endif

                    @if ($selectedInvoice->mra_qr_code)
                        <div class="flex flex-col items-center justify-center p-4 bg-slate-900 border border-slate-700 rounded-xl space-y-2">
                            <span class="block text-xs text-slate-400 font-semibold">MRA Fiscal QR Verification Code</span>
                            <!-- Assuming the QR contains URL/text, rendering standard QR representation or link -->
                            <div class="bg-white p-2 rounded-lg inline-block">
                                {!! QrCode::size(120)->generate($selectedInvoice->mra_qr_code) !!}
                            </div>
                            <span class="text-xxs font-mono text-slate-500 break-all select-all text-center max-w-md">{{ $selectedInvoice->mra_qr_code }}</span>
                        </div>
                    @endif
                </div>

                <div class="flex justify-end gap-2 pt-4 border-t border-slate-700">
                    <flux:button wire:click="closeViewModal" variant="ghost">Close</flux:button>
                </div>
            </div>
        </div>
    @endif
</div>
