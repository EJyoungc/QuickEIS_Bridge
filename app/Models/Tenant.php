<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'company_tin',
        'mra_vendor_access_key',
        'secret_key',
        'terminal_id',
        'terminal_token',
        'jwt_token',
        'site_id',
        'activation_code',
        'activated_at',
        'status',
    ];

    public function connection(): HasOne
    {
        return $this->hasOne(QuickbooksConnection::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }
}
