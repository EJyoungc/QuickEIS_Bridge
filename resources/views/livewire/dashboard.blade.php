<div class="space-y-8">
    <!-- Visual Header Stat Cards using Flux Cards and Badges -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Card 1 -->
        <div class="bg-gradient-to-br from-slate-800 to-slate-850 p-6 rounded-2xl border border-slate-700 shadow-xl flex items-center justify-between hover:border-slate-600 transition">
            <div>
                <flux:text variant="muted" class="text-xs uppercase tracking-wider font-semibold">Total Registered Tenants</flux:text>
                <p class="text-3xl font-extrabold text-white mt-1">{{ $stats['total_tenants'] }}</p>
            </div>
            <div class="p-3 bg-blue-500/10 rounded-xl text-blue-400 border border-blue-500/20">
                <flux:icon name="building-office" />
            </div>
        </div>

        <!-- Card 2 -->
        <div class="bg-gradient-to-br from-slate-800 to-slate-850 p-6 rounded-2xl border border-slate-700 shadow-xl flex items-center justify-between hover:border-slate-600 transition">
            <div>
                <flux:text variant="muted" class="text-xs uppercase tracking-wider font-semibold">Active MRA Terminals</flux:text>
                <p class="text-3xl font-extrabold text-emerald-400 mt-1">{{ $stats['active_terminals'] }}</p>
            </div>
            <div class="p-3 bg-emerald-500/10 rounded-xl text-emerald-400 border border-emerald-500/20">
                <flux:icon name="check-circle" />
            </div>
        </div>

        <!-- Card 3 -->
        <div class="bg-gradient-to-br from-slate-800 to-slate-850 p-6 rounded-2xl border border-slate-700 shadow-xl flex items-center justify-between hover:border-slate-600 transition">
            <div>
                <flux:text variant="muted" class="text-xs uppercase tracking-wider font-semibold">Successfully Synced</flux:text>
                <p class="text-3xl font-extrabold text-blue-400 mt-1">{{ $stats['synced_invoices'] }}</p>
            </div>
            <div class="p-3 bg-blue-500/10 rounded-xl text-blue-400 border border-blue-500/20">
                <flux:icon name="arrow-up-tray" />
            </div>
        </div>

        <!-- Card 4 -->
        <div class="bg-gradient-to-br from-slate-800 to-slate-850 p-6 rounded-2xl border border-slate-700 shadow-xl flex items-center justify-between hover:border-slate-600 transition">
            <div>
                <flux:text variant="muted" class="text-xs uppercase tracking-wider font-semibold">Sync Failures</flux:text>
                <p class="text-3xl font-extrabold text-rose-500 mt-1">{{ $stats['failed_invoices'] }}</p>
            </div>
            <div class="p-3 bg-rose-500/10 rounded-xl text-rose-400 border border-rose-500/20">
                <flux:icon name="exclamation-triangle" />
            </div>
        </div>
    </div>

    <!-- Livewire Notifications / Flashes using Flux Callouts -->
    @if (session()->has('tenant_success'))
        <flux:callout variant="success" title="Success">
            {{ session('tenant_success') }}
        </flux:callout>
    @endif
    @if (session()->has('tenant_error'))
        <flux:callout variant="danger" title="Error">
            {{ session('tenant_error') }}
        </flux:callout>
    @endif
    @if (session()->has('qb_success'))
        <flux:callout variant="success" title="Success">
            {{ session('qb_success') }}
        </flux:callout>
    @endif
    @if (session()->has('invoice_success'))
        <flux:callout variant="success" title="Success">
            {{ session('invoice_success') }}
        </flux:callout>
    @endif

    <!-- Operations View Navigation Tabs using Flux Buttons/Navbar elements -->
    <div class="flex items-center gap-2 border-b border-slate-700">
        <flux:button wire:click="$set('activeTab', 'tenants')" variant="{{ $activeTab === 'tenants' ? 'primary' : 'ghost' }}" class="!rounded-none !border-b-2 {{ $activeTab === 'tenants' ? '!border-cyan-400 text-cyan-400' : '!border-transparent text-slate-400' }}">
            Multi-Tenant Configuration
        </flux:button>
        <flux:button wire:click="$set('activeTab', 'connections')" variant="{{ $activeTab === 'connections' ? 'primary' : 'ghost' }}" class="!rounded-none !border-b-2 {{ $activeTab === 'connections' ? '!border-cyan-400 text-cyan-400' : '!border-transparent text-slate-400' }}">
            QuickBooks Connections
        </flux:button>
        <flux:button wire:click="$set('activeTab', 'invoices')" variant="{{ $activeTab === 'invoices' ? 'primary' : 'ghost' }}" class="!rounded-none !border-b-2 {{ $activeTab === 'invoices' ? '!border-cyan-400 text-cyan-400' : '!border-transparent text-slate-400' }}">
            Synchronized Invoices
        </flux:button>
        <flux:button wire:click="$set('activeTab', 'logs')" variant="{{ $activeTab === 'logs' ? 'primary' : 'ghost' }}" class="!rounded-none !border-b-2 {{ $activeTab === 'logs' ? '!border-cyan-400 text-cyan-400' : '!border-transparent text-slate-400' }}">
            SOAP & REST Audit Logs
        </flux:button>
    </div>

    <!-- Active Tab Panel Content -->
    <div>
        @if ($activeTab === 'tenants')
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Registration Forms Column -->
                <div class="space-y-8 lg:col-span-1">
                    <!-- Register Tenant Form using Flux Fields -->
                    <div class="bg-slate-800/50 p-6 rounded-2xl border border-slate-700 shadow-xl space-y-4">
                        <flux:heading size="lg">Register Tenant Company</flux:heading>
                        <form wire:submit.prevent="createTenant" class="space-y-4">
                            <flux:field>
                                <flux:label>Company Name</flux:label>
                                <flux:input type="text" wire:model="tenantName" required />
                                <flux:error name="tenantName" />
                            </flux:field>
                            <flux:field>
                                <flux:label>Company TIN</flux:label>
                                <flux:input type="text" wire:model="tenantTin" required />
                                <flux:error name="tenantTin" />
                            </flux:field>
                            <flux:field>
                                <flux:label>MRA Access Key (Vendor Key)</flux:label>
                                <flux:input type="text" wire:model="tenantAccessKey" required />
                                <flux:error name="tenantAccessKey" />
                            </flux:field>
                            <flux:field>
                                <flux:label>Activation Code (TAC)</flux:label>
                                <flux:input type="text" wire:model="tenantActivationCode" />
                            </flux:field>
                            <flux:button type="submit" variant="primary" class="w-full">
                                Register Tenant
                            </flux:button>
                        </form>
                    </div>

                    <!-- Register QBWC Credential Form using Flux Select and Fields -->
                    <div class="bg-slate-800/50 p-6 rounded-2xl border border-slate-700 shadow-xl space-y-4">
                        <flux:heading size="lg">Create QB Connector Credential</flux:heading>
                        <form wire:submit.prevent="createQbConnection" class="space-y-4">
                            <flux:field>
                                <flux:label>Link Tenant</flux:label>
                                <select wire:model="selectedTenantId" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-white focus:outline-none focus:border-cyan-400" required>
                                    <option value="">Select Tenant Company</option>
                                    @foreach ($tenants as $t)
                                        <option value="{{ $t->id }}">{{ $t->name }} ({{ $t->company_tin }})</option>
                                    @endforeach
                                </select>
                                @error('selectedTenantId') <span class="text-xs text-rose-400">{{ $message }}</span> @enderror
                            </flux:field>
                            <flux:field>
                                <flux:label>QBWC Username</flux:label>
                                <flux:input type="text" wire:model="qbUsername" required />
                                <flux:error name="qbUsername" />
                            </flux:field>
                            <flux:field>
                                <flux:label>QBWC Password</flux:label>
                                <flux:input type="password" wire:model="qbPassword" required />
                                <flux:error name="qbPassword" />
                            </flux:field>
                            <flux:button type="submit" variant="filled" class="w-full">
                                Create QB Connection
                            </flux:button>
                        </form>
                    </div>
                </div>

                <!-- Tenants List Table using Flux Table -->
                <div class="lg:col-span-2 bg-slate-800/30 rounded-2xl border border-slate-700 shadow-xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-700 flex justify-between items-center bg-slate-800/50">
                        <flux:heading size="lg">Registered Tenant Companies</flux:heading>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-slate-300">
                            <thead class="bg-slate-800/75 text-xs uppercase tracking-wider text-slate-400">
                                <tr>
                                    <th class="px-6 py-4">Company Name</th>
                                    <th class="px-6 py-4">TIN</th>
                                    <th class="px-6 py-4">Terminal credentials</th>
                                    <th class="px-6 py-4">QBWC User</th>
                                    <th class="px-6 py-4">Status</th>
                                    <th class="px-6 py-4">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-700">
                                @forelse ($tenants as $t)
                                    <tr class="hover:bg-slate-800/40">
                                        <td class="px-6 py-4 font-bold text-white">{{ $t->name }}</td>
                                        <td class="px-6 py-4">{{ $t->company_tin }}</td>
                                        <td class="px-6 py-4 space-y-1">
                                            @if ($t->terminal_id)
                                                <div class="text-xs"><span class="text-slate-400">ID:</span> {{ $t->terminal_id }}</div>
                                                <div class="text-xs"><span class="text-slate-400">Token:</span> {{ Str::limit($t->terminal_token, 10) }}</div>
                                            @else
                                                <span class="text-xs text-slate-500">Not Onboarded</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            @if ($t->connection)
                                                <flux:badge size="sm" inset="top">{{ $t->connection->username }}</flux:badge>
                                            @else
                                                <span class="text-xs text-slate-500">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            @if ($t->status === 'active')
                                                <flux:badge color="emerald" size="sm">Active</flux:badge>
                                            @else
                                                <flux:badge color="amber" size="sm">Pending Onboarding</flux:badge>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            @if ($t->status !== 'active')
                                                <flux:button wire:click="onboardTerminal({{ $t->id }})" variant="primary" size="sm">
                                                    Onboard
                                                </flux:button>
                                            @else
                                                <span class="text-xs text-slate-500 font-semibold">Ready</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center text-slate-500 font-semibold">No registered tenants found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        @if ($activeTab === 'connections')
            <div class="bg-slate-800/30 rounded-2xl border border-slate-700 shadow-xl overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-700 flex justify-between items-center bg-slate-800/50">
                    <flux:heading size="lg">QuickBooks Web Connector Connections</flux:heading>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-300">
                        <thead class="bg-slate-800/75 text-xs uppercase tracking-wider text-slate-400">
                            <tr>
                                <th class="px-6 py-4">Username</th>
                                <th class="px-6 py-4">Linked Company</th>
                                <th class="px-6 py-4">Active Session Ticket</th>
                                <th class="px-6 py-4">Sync Status</th>
                                <th class="px-6 py-4">Last Sync Date</th>
                                <th class="px-6 py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-700">
                            @forelse ($connections as $conn)
                                <tr class="hover:bg-slate-800/40">
                                    <td class="px-6 py-4 font-bold text-white">{{ $conn->username }}</td>
                                    <td class="px-6 py-4">{{ $conn->tenant->name }} ({{ $conn->tenant->company_tin }})</td>
                                    <td class="px-6 py-4 font-mono text-xs">{{ $conn->ticket ?? 'No active session' }}</td>
                                    <td class="px-6 py-4">
                                        @if ($conn->sync_status === 'syncing')
                                            <flux:badge color="blue" size="sm">Syncing</flux:badge>
                                        @elseif ($conn->sync_status === 'error')
                                            <flux:badge color="rose" size="sm">Error</flux:badge>
                                        @else
                                            <flux:badge size="sm">Idle</flux:badge>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-xs text-slate-400">
                                        {{ $conn->last_sync_at ? $conn->last_sync_at->format('M d, Y H:i:s') : 'Never' }}
                                    </td>
                                    <td class="px-6 py-4 text-right space-x-2">
                                        <flux:button wire:click="downloadQwc({{ $conn->id }})" variant="primary" size="sm" icon="arrow-down-tray">
                                            QWC File
                                        </flux:button>
                                        <flux:button wire:click="deleteConnection({{ $conn->id }})" variant="danger" size="sm">
                                            Remove
                                        </flux:button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-slate-500 font-semibold">No active QuickBooks connections found. Register one inside the configuration tab!</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        @if ($activeTab === 'invoices')
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
                                <tr class="hover:bg-slate-800/40">
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
                                    <td class="px-6 py-4 text-right">
                                        <flux:button wire:click="replaySync({{ $i->id }})" variant="primary" size="sm">
                                            Replay Sync
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
        @endif

        @if ($activeTab === 'logs')
            <div wire:poll.5s class="bg-slate-800/30 rounded-2xl border border-slate-700 shadow-xl overflow-hidden space-y-4">
                <div class="px-6 py-5 border-b border-slate-700 bg-slate-800/50 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <flux:heading size="lg">SOAP & REST Audit Logs</flux:heading>
                    <div class="flex items-center gap-3">
                        <flux:button wire:click="clearLogs" variant="danger" size="sm" class="!bg-rose-600 hover:!bg-rose-550 text-white">
                            Clear All Logs
                        </flux:button>
                        <flux:input wire:model.live.debounce.300ms="logSearch" placeholder="Search logs..." />
                    </div>
                </div>

                @if (session()->has('logs_success'))
                    <div class="mx-6 mt-4">
                        <flux:callout variant="success" title="Success">
                            {{ session('logs_success') }}
                        </flux:callout>
                    </div>
                @endif

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-300">
                        <thead class="bg-slate-800/75 text-xs uppercase tracking-wider text-slate-400">
                            <tr>
                                <th class="px-6 py-4">Tenant Company</th>
                                <th class="px-6 py-4">Direction</th>
                                <th class="px-6 py-4">Type</th>
                                <th class="px-6 py-4">Payload Snippet</th>
                                <th class="px-6 py-4">HTTP Status</th>
                                <th class="px-6 py-4">Date</th>
                                <th class="px-6 py-4 text-right">Inspect</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-700">
                            @forelse ($auditLogs as $log)
                                <tr class="hover:bg-slate-800/40">
                                    <td class="px-6 py-4 font-semibold text-white">{{ $log->tenant->name ?? 'System' }}</td>
                                    <td class="px-6 py-4">
                                        @if ($log->direction === 'inbound_qb')
                                            <flux:badge size="sm">Inbound QB</flux:badge>
                                        @elseif ($log->direction === 'outbound_mra')
                                            <flux:badge color="blue" size="sm">Outbound MRA</flux:badge>
                                        @elseif ($log->direction === 'inbound_mra')
                                            <flux:badge color="indigo" size="sm">Inbound MRA</flux:badge>
                                        @else
                                            <flux:badge size="sm">Outbound QB</flux:badge>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 font-bold text-xs uppercase text-cyan-400">{{ $log->type }}</td>
                                    <td class="px-6 py-4 font-mono text-xs text-slate-400">{{ Str::limit($log->payload, 50) }}</td>
                                    <td class="px-6 py-4 font-bold">{{ $log->status_code ?? '-' }}</td>
                                    <td class="px-6 py-4 text-xs text-slate-400">{{ $log->created_at->format('M d, Y H:i:s') }}</td>
                                    <td class="px-6 py-4 text-right">
                                        <flux:button wire:click="selectLog({{ $log->id }})" variant="ghost" size="sm">
                                            View XML/JSON
                                        </flux:button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-slate-500 font-semibold">No audit logs found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-6 border-t border-slate-700 bg-slate-800/20">
                    {{ $auditLogs->links() }}
                </div>
            </div>
        @endif
    </div>

    <!-- Modal to View Detailed Audit Log Payload -->
    @if ($selectedLogId && $activeLog)
        <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="bg-slate-800 rounded-2xl border border-slate-700 shadow-2xl max-w-4xl w-full max-h-[85vh] flex flex-col">
                <div class="px-6 py-4 border-b border-slate-700 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-white">Log Payload Details - <span class="text-cyan-400">{{ $activeLog->type }}</span></h3>
                    <button wire:click="closeLogModal" class="text-slate-400 hover:text-slate-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <div class="p-6 overflow-y-auto flex-1 font-mono text-xs text-slate-300 bg-slate-900/50">
                    <pre class="whitespace-pre-wrap">{{ $activeLog->payload }}</pre>
                </div>
                <div class="px-6 py-4 border-t border-slate-700 bg-slate-800 flex justify-end">
                    <flux:button wire:click="closeLogModal" variant="primary">
                        Close Log
                    </flux:button>
                </div>
            </div>
        </div>
    @endif
</div>
