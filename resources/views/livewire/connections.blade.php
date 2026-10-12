<div class="space-y-8">
    <!-- Livewire Notifications / Flashes -->
    @if (session()->has('qb_success'))
        <flux:callout variant="success" title="Success">
            {{ session('qb_success') }}
        </flux:callout>
    @endif
    @if (session()->has('qb_error'))
        <flux:callout variant="danger" title="Error">
            {{ session('qb_error') }}
        </flux:callout>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Registration Forms Column -->
        <div class="space-y-8 lg:col-span-1">
            <!-- Register QBWC Credential Form -->
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

        <!-- Connections List Table -->
        <div class="lg:col-span-2 bg-slate-800/30 rounded-2xl border border-slate-700 shadow-xl overflow-hidden">
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
                                    <flux:button wire:click="downloadQwc({{ $conn->id }})" variant="primary" size="sm" icon="arrow-down-tray" title="Download QWC File">
                                        QWC
                                    </flux:button>
                                    <flux:button wire:click="editConnection({{ $conn->id }})" variant="ghost" size="sm" icon="pencil">
                                    </flux:button>
                                    <flux:button wire:click="deleteConnection({{ $conn->id }})" variant="danger" size="sm">
                                        Remove
                                    </flux:button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-500 font-semibold">No active QuickBooks connections found. Register one inside the form panel!</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Edit Connection Modal -->
    @if ($editingConnectionId)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm">
            <div class="bg-slate-800 border border-slate-700 w-full max-w-lg p-6 rounded-2xl shadow-2xl space-y-6 text-white">
                <div class="flex justify-between items-center border-b border-slate-700 pb-3">
                    <flux:heading size="lg">Edit QuickBooks Connection</flux:heading>
                    <flux:button wire:click="closeEditModal" variant="ghost" icon="x-mark" size="sm"></flux:button>
                </div>

                <form wire:submit.prevent="updateQbConnection" class="space-y-4">
                    <flux:field>
                        <flux:label>Link Tenant</flux:label>
                        <select wire:model="editSelectedTenantId" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-white focus:outline-none focus:border-cyan-400" required>
                            <option value="">Select Tenant Company</option>
                            @foreach ($tenants as $t)
                                <option value="{{ $t->id }}">{{ $t->name }} ({{ $t->company_tin }})</option>
                            @endforeach
                        </select>
                        @error('editSelectedTenantId') <span class="text-xs text-rose-400">{{ $message }}</span> @enderror
                    </flux:field>

                    <flux:field>
                        <flux:label>QBWC Username</flux:label>
                        <flux:input type="text" wire:model="editQbUsername" required />
                        @error('editQbUsername') <span class="text-xs text-rose-400">{{ $message }}</span> @enderror
                    </flux:field>

                    <flux:field>
                        <flux:label>QBWC Password (leave blank to keep unchanged)</flux:label>
                        <flux:input type="password" wire:model="editQbPassword" placeholder="••••••••" />
                        @error('editQbPassword') <span class="text-xs text-rose-400">{{ $message }}</span> @enderror
                    </flux:field>

                    <div class="flex justify-end gap-2 pt-4 border-t border-slate-700">
                        <flux:button wire:click="closeEditModal" variant="ghost">Cancel</flux:button>
                        <flux:button type="submit" variant="primary">Save Changes</flux:button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
