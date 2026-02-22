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
        Schema::create('ads_accounts_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ad_account_id')->nullable()->constrained('ads_accounts')->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('ads_accounts_details')->cascadeOnDelete();
            $table->string('name');
            $table->string('utm_key');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ads_accounts_details');
    }
};
