<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            // JWT token returned by MRA after terminal activation (used as Bearer auth)
            $table->text('jwt_token')->nullable()->after('terminal_token');
            // Site ID assigned by MRA to this terminal location
            $table->string('site_id')->nullable()->after('jwt_token');
            // Timestamp of when the terminal was last activated/onboarded
            $table->timestamp('activated_at')->nullable()->after('site_id');
        });
    }

    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn(['jwt_token', 'site_id', 'activated_at']);
        });
    }
};
