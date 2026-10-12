<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuickbooksConnection extends Model
{
    use HasFactory;

    protected $table = 'quickbooks_connections';

    protected $fillable = [
        'tenant_id',
        'username',
        'password_hash',
        'owner_id',
        'file_id',
        'ticket',
        'last_sync_at',
        'sync_status',
    ];

    protected $casts = [
        'last_sync_at' => 'datetime',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
