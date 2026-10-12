<?php

namespace App\Livewire;

use App\Models\Tenant;
use App\Models\QuickbooksConnection;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Connections extends Component
{
    // Create Form State
    public ?int $selectedTenantId = null;
    public string $qbUsername = '';
    public string $qbPassword = '';

    // Edit Form State
    public ?int $editingConnectionId = null;
    public ?int $editSelectedTenantId = null;
    public string $editQbUsername = '';
    public string $editQbPassword = '';

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

    public function editConnection(int $connectionId): void
    {
        $conn = QuickbooksConnection::find($connectionId);
        if ($conn) {
            $this->editingConnectionId = $conn->id;
            $this->editSelectedTenantId = $conn->tenant_id;
            $this->editQbUsername = $conn->username;
            $this->editQbPassword = ''; // reset password field
        }
    }

    public function updateQbConnection(): void
    {
        $this->validate([
            'editSelectedTenantId' => 'required|exists:tenants,id',
            'editQbUsername' => 'required|string|unique:quickbooks_connections,username,' . $this->editingConnectionId,
            'editQbPassword' => 'nullable|string|min:6',
        ]);

        $conn = QuickbooksConnection::find($this->editingConnectionId);
        if ($conn) {
            $data = [
                'tenant_id' => $this->editSelectedTenantId,
                'username' => $this->editQbUsername,
            ];

            if (!empty($this->editQbPassword)) {
                $data['password_hash'] = Hash::make($this->editQbPassword);
            }

            $conn->update($data);

            $this->reset(['editingConnectionId', 'editSelectedTenantId', 'editQbUsername', 'editQbPassword']);
            session()->flash('qb_success', 'QuickBooks Web Connector credential updated successfully.');
        }
    }

    public function closeEditModal(): void
    {
        $this->reset(['editingConnectionId', 'editSelectedTenantId', 'editQbUsername', 'editQbPassword']);
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
  <AppID>{' . \Illuminate\Support\Str::uuid() . '}</AppID>
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
        }, 'eis-bridge-' . \Illuminate\Support\Str::slug($conn->tenant->name) . '.qwc', [
            'Content-Type' => 'text/xml',
        ]);
    }

    public function render()
    {
        $tenants = Tenant::with('connection')->latest()->get();
        $connections = QuickbooksConnection::with('tenant')->latest()->get();

        return view('livewire.connections', [
            'tenants' => $tenants,
            'connections' => $connections,
        ]);
    }
}
