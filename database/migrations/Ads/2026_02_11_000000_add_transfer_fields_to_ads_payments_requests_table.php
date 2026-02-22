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
            $table->foreignId('from_ad_account_id')->nullable()->after('ad_account_id')->constrained('ads_accounts')->nullOnDelete();
            $table->foreignId('to_ad_account_id')->nullable()->after('from_ad_account_id')->constrained('ads_accounts')->nullOnDelete();
            $table->text('reason')->nullable()->after('receipt');
            $table->string('transaction_type')->default('charge')->after('reason');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ads_payments_requests', function (Blueprint $table) {
            $table->dropForeign(['from_ad_account_id']);
            $table->dropForeign(['to_ad_account_id']);
            $table->dropColumn(['from_ad_account_id', 'to_ad_account_id', 'reason', 'transaction_type']);
        });
    }
};
