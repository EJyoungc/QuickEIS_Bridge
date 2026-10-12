<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('company_tin')->unique();
            $table->string('mra_vendor_access_key');
            $table->string('secret_key')->nullable();
            $table->string('terminal_id')->nullable();
            $table->string('terminal_token')->nullable();
            $table->string('activation_code')->nullable();
            $table->string('status')->default('pending'); // pending, active, blocked
            $table->timestamps();
        });

        Schema::create('quickbooks_connections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->string('username')->unique();
            $table->string('password_hash');
            $table->string('owner_id')->nullable();
            $table->string('file_id')->nullable();
            $table->string('ticket')->nullable();
            $table->timestamp('last_sync_at')->nullable();
            $table->string('sync_status')->default('idle'); // idle, syncing, error
            $table->timestamps();
        });

        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->string('qb_txn_id')->index();
            $table->string('qb_edit_sequence');
            $table->string('qb_invoice_number');
            $table->string('customer_name')->nullable();
            $table->date('transaction_date');
            $table->decimal('amount_before_tax', 15, 2);
            $table->decimal('tax_amount', 15, 2);
            $table->decimal('total_amount', 15, 2);
            $table->string('status')->default('pending'); // pending, submitted, failed, voided
            $table->string('mra_invoice_number')->nullable();
            $table->string('mra_receipt_number')->nullable();
            $table->text('mra_qr_code')->nullable();
            $table->timestamp('mra_submitted_at')->nullable();
            $table->text('error_message')->nullable();
            $table->integer('retry_count')->default(0);
            $table->timestamps();
        });

        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->nullable()->constrained('tenants')->onDelete('cascade');
            $table->foreignId('invoice_id')->nullable()->constrained('invoices')->onDelete('set null');
            $table->string('direction'); // inbound_qb, outbound_mra, inbound_mra, outbound_qb
            $table->string('type'); // soap_request, soap_response, rest_request, rest_response, error, auth
            $table->longText('payload');
            $table->integer('status_code')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('quickbooks_connections');
        Schema::dropIfExists('tenants');
    }
};
