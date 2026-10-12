<?php

namespace App\Livewire;

use App\Models\AuditLog;
use Livewire\Component;
use Livewire\WithPagination;

class Logs extends Component
{
    use WithPagination;

    // Filters and Search
    public string $logSearch = '';
    public ?int $selectedLogId = null;

    public function selectLog(int $logId): void
    {
        $this->selectedLogId = $logId;
    }

    public function closeLogModal(): void
    {
        $this->selectedLogId = null;
    }

    public function clearLogs(): void
    {
        AuditLog::query()->delete();
        session()->flash('logs_success', 'All audit logs cleared successfully.');
    }

    public function render()
    {
        $auditLogs = AuditLog::with(['tenant', 'invoice'])
            ->when($this->logSearch, function ($query) {
                $query->where('type', 'like', '%' . $this->logSearch . '%')
                    ->orWhere('payload', 'like', '%' . $this->logSearch . '%');
            })
            ->latest()
            ->paginate(15);

        $activeLog = $this->selectedLogId ? AuditLog::find($this->selectedLogId) : null;

        return view('livewire.logs', [
            'auditLogs' => $auditLogs,
            'activeLog' => $activeLog,
        ]);
    }
}
