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
        Schema::table('ads_payments_requests', function (Blueprint $table) {
            $table->string('transaction_reference')->nullable()->after('status');
            $table->string('receipt')->nullable()->after('transaction_reference');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ads_payments_requests', function (Blueprint $table) {
            $table->dropColumn(['transaction_reference', 'receipt']);
        });
    }
};
