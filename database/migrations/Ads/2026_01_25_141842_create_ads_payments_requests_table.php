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
        Schema::create('ads_payments_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ad_account_id')->constrained('ads_accounts')->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            $table->string('status');
            $table->dateTime('add_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ads_payments_requests');
    }
};
