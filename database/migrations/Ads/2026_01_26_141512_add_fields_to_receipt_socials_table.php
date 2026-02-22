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
        Schema::table('receipt_socials', function (Blueprint $table) {
            $table->text('utm_details')->nullable();
            $table->foreignId('ad_history_id')->nullable()->after('id')->constrained('ads_accounts_history')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('receipt_socials', function (Blueprint $table) {
            $table->dropColumn('utm_details');
            $table->dropForeign(['ad_history_id']);
            $table->dropColumn('ad_history_id');
        });
    }
};
