<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('delivery_settlements')) {
            return;
        }

        Schema::create('delivery_settlements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('deliver_man_id');
            $table->unsignedBigInteger('settled_by_user_id')->nullable();
            $table->date('settlement_date');
            $table->decimal('expected_amount', 12, 2)->default(0);
            $table->decimal('collected_amount', 12, 2)->default(0);
            $table->decimal('difference_amount', 12, 2)->default(0);
            $table->string('status')->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['deliver_man_id', 'settlement_date']);
            $table->foreign('deliver_man_id')->references('id')->on('deliver_men')->onDelete('cascade');
            $table->foreign('settled_by_user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_settlements');
    }
};
