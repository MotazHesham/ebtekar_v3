<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adds transfer fields and transaction_type if missing (e.g. when earlier migration was skipped).
     */
    public function up(): void
    {
        if (!Schema::hasTable('ads_payments_requests')) {
            return;
        }

        Schema::table('ads_payments_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('ads_payments_requests', 'from_ad_account_id')) {
                $table->foreignId('from_ad_account_id')->nullable()->after('ad_account_id')->constrained('ads_accounts')->nullOnDelete();
            }
            if (!Schema::hasColumn('ads_payments_requests', 'to_ad_account_id')) {
                $after = Schema::hasColumn('ads_payments_requests', 'from_ad_account_id') ? 'from_ad_account_id' : 'ad_account_id';
                $table->foreignId('to_ad_account_id')->nullable()->after($after)->constrained('ads_accounts')->nullOnDelete();
            }
            if (!Schema::hasColumn('ads_payments_requests', 'reason')) {
                $table->text('reason')->nullable()->after('receipt');
            }
            if (!Schema::hasColumn('ads_payments_requests', 'transaction_type')) {
                $after = Schema::hasColumn('ads_payments_requests', 'reason') ? 'reason' : 'receipt';
                $table->string('transaction_type')->default('charge')->after($after);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('ads_payments_requests')) {
            return;
        }

        Schema::table('ads_payments_requests', function (Blueprint $table) {
            if (Schema::hasColumn('ads_payments_requests', 'from_ad_account_id')) {
                $table->dropForeign(['from_ad_account_id']);
            }
            if (Schema::hasColumn('ads_payments_requests', 'to_ad_account_id')) {
                $table->dropForeign(['to_ad_account_id']);
            }
            $columnsToDrop = array_filter(
                ['from_ad_account_id', 'to_ad_account_id', 'reason', 'transaction_type'],
                fn ($col) => Schema::hasColumn('ads_payments_requests', $col)
            );
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
