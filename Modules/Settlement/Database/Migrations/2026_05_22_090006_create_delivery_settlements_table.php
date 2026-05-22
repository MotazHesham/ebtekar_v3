<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Shipping\Support\ShippingTables as ST;

return new class extends Migration
{
    public function up(): void
    {
        $table = ST::name(ST::DELIVERY_SETTLEMENTS);

        if (Schema::hasTable($table) || Schema::hasTable(ST::DELIVERY_SETTLEMENTS)) {
            return;
        }

        Schema::create($table, function (Blueprint $blueprint) {
            $blueprint->bigIncrements('id');
            $blueprint->unsignedBigInteger('deliver_man_id');
            $blueprint->unsignedBigInteger('settled_by_user_id')->nullable();
            $blueprint->date('settlement_date');
            $blueprint->decimal('expected_amount', 12, 2)->default(0);
            $blueprint->decimal('collected_amount', 12, 2)->default(0);
            $blueprint->decimal('difference_amount', 12, 2)->default(0);
            $blueprint->string('status')->default('pending');
            $blueprint->text('notes')->nullable();
            $blueprint->timestamps();

            $blueprint->index(['deliver_man_id', 'settlement_date']);
            $blueprint->foreign('deliver_man_id')->references('id')->on('deliver_men')->onDelete('cascade');
            $blueprint->foreign('settled_by_user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(ST::name(ST::DELIVERY_SETTLEMENTS));
        Schema::dropIfExists(ST::DELIVERY_SETTLEMENTS);
    }
};
