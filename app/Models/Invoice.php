<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'qb_txn_id',
        'qb_edit_sequence',
        'qb_invoice_number',
        'customer_name',
        'transaction_date',
        'amount_before_tax',
        'tax_amount',
        'total_amount',
        'status',
        'mra_invoice_number',
        'mra_receipt_number',
        'mra_qr_code',
        'mra_submitted_at',
        'error_message',
        'retry_count',
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'mra_submitted_at' => 'datetime',
        'amount_before_tax' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }
}
