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
        Schema::create('ads_accounts_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ad_account_detail_id')->constrained('ads_accounts_details')->cascadeOnDelete();
            $table->decimal('total_spent', 12, 2);
            $table->date('date');
            $table->json('sales')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ads_accounts_history');
    }
};
