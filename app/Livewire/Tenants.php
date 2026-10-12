<?php

namespace App\Livewire;

use App\Models\Tenant;
use App\Services\MraOnboardingService;
use Livewire\Component;

class Tenants extends Component
{
    // Form mode: 'create' or 'edit'
    public string $formMode = 'create';
    public ?int $editingTenantId = null;

    // Form fields
    public string $tenantName = '';
    public string $tenantTin = '';
    public string $tenantAccessKey = '';
    public string $tenantActivationCode = '';

    // View detail modal
    public ?int $viewingTenantId = null;

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

        $this->resetForm();
        session()->flash('tenant_success', 'Tenant company registered successfully.');
    }

    public function editTenant(int $tenantId): void
    {
        $tenant = Tenant::findOrFail($tenantId);
        $this->formMode = 'edit';
        $this->editingTenantId = $tenantId;
        $this->tenantName = $tenant->name;
        $this->tenantTin = $tenant->company_tin;
        $this->tenantAccessKey = $tenant->mra_vendor_access_key;
        $this->tenantActivationCode = $tenant->activation_code ?? '';
    }

    public function updateTenant(): void
    {
        $this->validate([
            'tenantName' => 'required|string|max:255',
            'tenantTin' => 'required|string|max:255|unique:tenants,company_tin,' . $this->editingTenantId,
            'tenantAccessKey' => 'required|string|max:255',
        ]);

        $tenant = Tenant::findOrFail($this->editingTenantId);
        $tenant->update([
            'name' => $this->tenantName,
            'company_tin' => $this->tenantTin,
            'mra_vendor_access_key' => $this->tenantAccessKey,
            'activation_code' => $this->tenantActivationCode,
        ]);

        $this->resetForm();
        session()->flash('tenant_success', "Tenant '{$tenant->name}' updated successfully.");
    }

    public function cancelEdit(): void
    {
        $this->resetForm();
    }

    public function deleteTenant(int $tenantId): void
    {
        $tenant = Tenant::find($tenantId);
        if ($tenant) {
            $name = $tenant->name;
            $tenant->delete();
            session()->flash('tenant_success', "Tenant '{$name}' deleted successfully.");
        }
    }

    public function viewTenant(int $tenantId): void
    {
        $this->viewingTenantId = $tenantId;
    }

    public function closeViewModal(): void
    {
        $this->viewingTenantId = null;
    }

    public function onboardTerminal(int $tenantId): void
    {
        $tenant = Tenant::find($tenantId);

        if (! $tenant) {
            return;
        }

        $service = app(MraOnboardingService::class);

        // If Step 1 already completed but Step 2 was not confirmed,
        // skip re-activation and only send the confirmation.
        if ($tenant->status === 'pending_confirmation' && $tenant->jwt_token && $tenant->terminal_id) {
            $result = $service->confirmActivation(
                $tenant,
                $tenant->terminal_id,
                $tenant->jwt_token,
                $tenant->secret_key
            );
        } else {
            if (empty($tenant->activation_code)) {
                session()->flash('tenant_error', 'Activation code (TAC) is required before onboarding.');

                return;
            }

            $result = $service->activate($tenant);
        }

        if ($result['success']) {
            session()->flash('tenant_success', $result['message']);
        } else {
            session()->flash('tenant_error', $result['message']);
        }
    }

    public function refreshToken(int $tenantId): void
    {
        $tenant = Tenant::find($tenantId);

        if (! $tenant) {
            return;
        }

        $result = app(MraOnboardingService::class)->refreshToken($tenant);

        if ($result['success']) {
            session()->flash('tenant_success', $result['message']);
        } else {
            session()->flash('tenant_error', 'Token refresh failed: ' . $result['message']);
        }
    }

    protected function resetForm(): void
    {
        $this->formMode = 'create';
        $this->editingTenantId = null;
        $this->reset(['tenantName', 'tenantTin', 'tenantAccessKey', 'tenantActivationCode']);
    }

    public function render()
    {
        $tenants = Tenant::with('connection')->latest()->get();
        $viewingTenant = $this->viewingTenantId ? Tenant::with('connection')->find($this->viewingTenantId) : null;

        return view('livewire.tenants', [
            'tenants' => $tenants,
            'viewingTenant' => $viewingTenant,
        ]);
    }
}
