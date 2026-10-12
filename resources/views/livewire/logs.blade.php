<div class="space-y-8">
    <div {{ !$selectedLogId ? 'wire:poll.5s' : '' }} class="bg-slate-800/30 rounded-2xl border border-slate-700 shadow-xl overflow-hidden space-y-4">
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
                        <tr wire:key="log-row-{{ $log->id }}" class="hover:bg-slate-800/40">
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
