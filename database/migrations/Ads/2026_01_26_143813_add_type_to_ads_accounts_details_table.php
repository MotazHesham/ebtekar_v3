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
        Schema::table('ads_accounts_details', function (Blueprint $table) {
            $table->enum('type', ['campaign', 'ad_set', 'ad'])->nullable()->after('utm_key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ads_accounts_details', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
