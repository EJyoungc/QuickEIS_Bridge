<div class="space-y-8">
    <!-- Notifications / Flashes -->
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Create / Edit Form -->
        <div class="space-y-4 lg:col-span-1">
            <div wire:key="tenant-form-{{ $formMode }}-{{ $editingTenantId }}" class="bg-slate-800/50 p-6 rounded-2xl border {{ $formMode === 'edit' ? 'border-cyan-500/60' : 'border-slate-700' }} shadow-xl space-y-4 transition-all">
                <div class="flex items-center justify-between">
                    <flux:heading size="lg">
                        {{ $formMode === 'edit' ? 'Edit Tenant Company' : 'Register Tenant Company' }}
                    </flux:heading>
                    @if ($formMode === 'edit')
                        <flux:badge color="blue" size="sm">Editing</flux:badge>
                    @endif
                </div>

                <form wire:submit.prevent="{{ $formMode === 'edit' ? 'updateTenant' : 'createTenant' }}" class="space-y-4">
                    <div class="space-y-1">
                        <label class="block text-sm font-semibold text-slate-300">Company Name</label>
                        <input type="text" wire:model="tenantName" value="{{ $tenantName }}" placeholder="e.g. Lubemasters and Accessories"
                            class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-white text-sm placeholder-slate-500 focus:outline-none focus:border-cyan-400 transition" required />
                        @error('tenantName') <span class="text-xs text-rose-400">{{ $message }}</span> @enderror
                    </div>
                    <div class="space-y-1">
                        <label class="block text-sm font-semibold text-slate-300">Company TIN</label>
                        <input type="text" wire:model="tenantTin" value="{{ $tenantTin }}" placeholder="e.g. TPI N202 08072"
                            class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-white text-sm placeholder-slate-500 focus:outline-none focus:border-cyan-400 transition" required />
                        @error('tenantTin') <span class="text-xs text-rose-400">{{ $message }}</span> @enderror
                    </div>
                    <div class="space-y-1">
                        <label class="block text-sm font-semibold text-slate-300">MRA Vendor Access Key</label>
                        <input type="text" wire:model="tenantAccessKey" value="{{ $tenantAccessKey }}" placeholder="Issued by MRA"
                            class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-white text-sm placeholder-slate-500 focus:outline-none focus:border-cyan-400 transition" required />
                        @error('tenantAccessKey') <span class="text-xs text-rose-400">{{ $message }}</span> @enderror
                    </div>
                    <div class="space-y-1">
                        <label class="block text-sm font-semibold text-slate-300">Activation Code (TAC)</label>
                        <input type="text" wire:model="tenantActivationCode" value="{{ $tenantActivationCode }}" placeholder="Terminal Activation Code"
                            class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-white text-sm placeholder-slate-500 focus:outline-none focus:border-cyan-400 transition" />
                    </div>

                    <div class="flex items-center gap-3 pt-1">
                        <flux:button type="submit" variant="primary" class="flex-1">
                            {{ $formMode === 'edit' ? 'Save Changes' : 'Register Tenant' }}
                        </flux:button>
                        @if ($formMode === 'edit')
                            <flux:button type="button" wire:click="cancelEdit" variant="ghost">
                                Cancel
                            </flux:button>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Tenants Table -->
        <div class="lg:col-span-2 bg-slate-800/30 rounded-2xl border border-slate-700 shadow-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-700 flex justify-between items-center bg-slate-800/50">
                <flux:heading size="lg">Registered Tenant Companies</flux:heading>
                <flux:badge size="sm">{{ $tenants->count() }} total</flux:badge>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-300">
                    <thead class="bg-slate-800/75 text-xs uppercase tracking-wider text-slate-400">
                        <tr>
                            <th class="px-6 py-4">Company Name</th>
                            <th class="px-6 py-4">TIN</th>
                            <th class="px-6 py-4">QBWC User</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700">
                        @forelse ($tenants as $t)
                            <tr wire:key="tenant-row-{{ $t->id }}" class="hover:bg-slate-800/40 {{ $editingTenantId === $t->id ? 'bg-cyan-900/10 border-l-2 border-cyan-500' : '' }}">
                                <td class="px-6 py-4 font-bold text-white">{{ $t->name }}</td>
                                <td class="px-6 py-4 font-mono text-xs">{{ $t->company_tin }}</td>
                                <td class="px-6 py-4">
                                    @if ($t->connection)
                                        <flux:badge size="sm" inset="top">{{ $t->connection->username }}</flux:badge>
                                    @else
                                        <span class="text-xs text-slate-500">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if ($t->status === 'active')
                                        <flux:badge color="emerald" size="sm">Active</flux:badge>
                                    @elseif ($t->status === 'pending_confirmation')
                                        <flux:badge color="blue" size="sm">Awaiting Confirmation</flux:badge>
                                    @else
                                        <flux:badge color="amber" size="sm">Pending</flux:badge>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <!-- View -->
                                        <flux:button wire:click="viewTenant({{ $t->id }})" variant="ghost" size="sm" icon="eye">
                                        </flux:button>

                                        <!-- Onboard (pending/awaiting confirmation) -->
                                        @if ($t->status !== 'active')
                                            <flux:button
                                                wire:click="onboardTerminal({{ $t->id }})"
                                                variant="primary"
                                                size="sm"
                                            >
                                                {{ $t->status === 'pending_confirmation' ? 'Confirm' : 'Onboard' }}
                                            </flux:button>
                                        @endif

                                        <!-- Request New Token — always visible -->
                                        <flux:button
                                            wire:click="refreshToken({{ $t->id }})"
                                            wire:confirm="Request a new terminal token from MRA for '{{ $t->name }}'?"
                                            variant="ghost"
                                            size="sm"
                                            icon="arrow-path"
                                            title="Request New Token from MRA"
                                        >
                                        </flux:button>

                                        <!-- Edit -->
                                        <flux:button wire:click="editTenant({{ $t->id }})" variant="filled" size="sm" icon="pencil">
                                        </flux:button>

                                        <!-- Delete -->
                                        <flux:button
                                            wire:click="deleteTenant({{ $t->id }})"
                                            wire:confirm="Are you sure you want to delete '{{ $t->name }}'? This will also remove all associated QuickBooks connections and invoices."
                                            variant="danger"
                                            size="sm"
                                            icon="trash"
                                        >
                                        </flux:button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-500 font-semibold">No registered tenants found. Use the form to add one.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- View Tenant Detail Modal -->
    @if ($viewingTenantId && $viewingTenant)
        <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="bg-slate-800 rounded-2xl border border-slate-700 shadow-2xl w-full max-w-2xl flex flex-col">
                <!-- Header -->
                <div class="px-6 py-4 border-b border-slate-700 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-white">{{ $viewingTenant->name }}</h3>
                        <p class="text-xs text-slate-400 mt-0.5">TIN: {{ $viewingTenant->company_tin }}</p>
                    </div>
                    <div class="flex items-center gap-3">
                        @if ($viewingTenant->status === 'active')
                            <flux:badge color="emerald">Active</flux:badge>
                        @else
                            <flux:badge color="amber">Pending Onboarding</flux:badge>
                        @endif
                        <button wire:click="closeViewModal" class="text-slate-400 hover:text-slate-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>

                <!-- Body -->
                <div class="p-6 space-y-6 overflow-y-auto max-h-[70vh]">
                    <!-- Company Details -->
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Company Details</p>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-slate-900/50 p-4 rounded-xl">
                                <p class="text-xs text-slate-400 mb-1">Company Name</p>
                                <p class="text-sm font-semibold text-white">{{ $viewingTenant->name }}</p>
                            </div>
                            <div class="bg-slate-900/50 p-4 rounded-xl">
                                <p class="text-xs text-slate-400 mb-1">Company TIN</p>
                                <p class="text-sm font-semibold text-white font-mono">{{ $viewingTenant->company_tin }}</p>
                            </div>
                            <div class="bg-slate-900/50 p-4 rounded-xl">
                                <p class="text-xs text-slate-400 mb-1">Activation Code (TAC)</p>
                                <p class="text-sm font-semibold text-white font-mono">{{ $viewingTenant->activation_code ?? '—' }}</p>
                            </div>
                            <div class="bg-slate-900/50 p-4 rounded-xl">
                                <p class="text-xs text-slate-400 mb-1">Registered</p>
                                <p class="text-sm font-semibold text-white">{{ $viewingTenant->created_at->format('M d, Y H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- MRA Credentials -->
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">MRA Terminal Credentials</p>
                        @if ($viewingTenant->status === 'active')
                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-slate-900/50 p-4 rounded-xl">
                                    <p class="text-xs text-slate-400 mb-1">Terminal ID</p>
                                    <p class="text-sm font-semibold text-white font-mono">{{ $viewingTenant->terminal_id ?? '—' }}</p>
                                </div>
                                <div class="bg-slate-900/50 p-4 rounded-xl">
                                    <p class="text-xs text-slate-400 mb-1">Site ID</p>
                                    <p class="text-sm font-semibold text-white font-mono">{{ $viewingTenant->site_id ?? '—' }}</p>
                                </div>
                                <div class="bg-slate-900/50 p-4 rounded-xl">
                                    <p class="text-xs text-slate-400 mb-1">Activated At</p>
                                    <p class="text-sm font-semibold text-white">{{ $viewingTenant->activated_at ? \Carbon\Carbon::parse($viewingTenant->activated_at)->format('M d, Y H:i') : '—' }}</p>
                                </div>
                                <div class="bg-slate-900/50 p-4 rounded-xl">
                                    <p class="text-xs text-slate-400 mb-1">Secret Key</p>
                                    <p class="text-sm font-semibold text-white font-mono truncate">{{ $viewingTenant->secret_key ? substr($viewingTenant->secret_key, 0, 20) . '...' : '—' }}</p>
                                </div>
                                <div class="bg-slate-900/50 p-4 rounded-xl col-span-2">
                                    <p class="text-xs text-slate-400 mb-1">JWT Token (Bearer Auth)</p>
                                    <p class="text-xs font-semibold text-emerald-400 font-mono break-all leading-relaxed">{{ $viewingTenant->jwt_token ? substr($viewingTenant->jwt_token, 0, 80) . '...' : '—' }}</p>
                                </div>
                                <div class="bg-slate-900/50 p-4 rounded-xl col-span-2">
                                    <p class="text-xs text-slate-400 mb-1">MRA Vendor Access Key</p>
                                    <p class="text-sm font-semibold text-white font-mono break-all">{{ $viewingTenant->mra_vendor_access_key }}</p>
                                </div>
                            </div>
                        @else
                            <div class="bg-amber-500/10 border border-amber-500/30 rounded-xl p-4 text-amber-400 text-sm font-semibold">
                                Terminal not yet activated. Enter a TAC and click <strong>Onboard</strong> to activate with MRA.
                            </div>
                        @endif
                    </div>

                    <!-- QBWC Connection -->
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">QuickBooks Web Connector</p>
                        @if ($viewingTenant->connection)
                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-slate-900/50 p-4 rounded-xl">
                                    <p class="text-xs text-slate-400 mb-1">QBWC Username</p>
                                    <p class="text-sm font-semibold text-white">{{ $viewingTenant->connection->username }}</p>
                                </div>
                                <div class="bg-slate-900/50 p-4 rounded-xl">
                                    <p class="text-xs text-slate-400 mb-1">Sync Status</p>
                                    <p class="text-sm font-semibold text-white capitalize">{{ $viewingTenant->connection->sync_status ?? 'idle' }}</p>
                                </div>
                                <div class="bg-slate-900/50 p-4 rounded-xl col-span-2">
                                    <p class="text-xs text-slate-400 mb-1">Active Session Ticket</p>
                                    <p class="text-sm font-semibold text-white font-mono">{{ $viewingTenant->connection->ticket ?? 'No active session' }}</p>
                                </div>
                            </div>
                        @else
                            <div class="bg-amber-500/10 border border-amber-500/30 rounded-xl p-4 text-amber-400 text-sm font-semibold">
                                No QuickBooks connection registered yet. Go to the QuickBooks Connections page to create one.
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-6 py-4 border-t border-slate-700 bg-slate-800/50 flex justify-between">
                    <flux:button wire:click="editTenant({{ $viewingTenant->id }}); closeViewModal()" variant="filled" size="sm" icon="pencil">
                        Edit Tenant
                    </flux:button>
                    <flux:button wire:click="closeViewModal" variant="primary">
                        Close
                    </flux:button>
                </div>
            </div>
        </div>
    @endif
</div>
