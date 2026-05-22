<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Shipping\Support\ShippingTables as ST;

return new class extends Migration
{
    public function up(): void
    {
        $table        = ST::name(ST::COURIER_SETTLEMENT_LINES);
        $settlements  = ST::name(ST::DELIVERY_SETTLEMENTS);
        $orders       = ST::name(ST::DELIVERY_ORDERS);

        if (Schema::hasTable($table) || Schema::hasTable(ST::COURIER_SETTLEMENT_LINES)) {
            return;
        }

        Schema::create($table, function (Blueprint $blueprint) use ($settlements, $orders) {
            $blueprint->bigIncrements('id');
            $blueprint->unsignedBigInteger('delivery_settlement_id');
            $blueprint->unsignedBigInteger('delivery_order_id');
            $blueprint->decimal('expected_amount', 12, 2)->default(0);
            $blueprint->decimal('collected_amount', 12, 2)->nullable();
            $blueprint->string('status', 32)->default('pending');
            $blueprint->timestamp('created_at')->useCurrent();

            $blueprint->unique(['delivery_settlement_id', 'delivery_order_id'], 'settlement_line_unique');
            $blueprint->index(['delivery_order_id', 'status']);
            $blueprint->foreign('delivery_settlement_id')->references('id')->on($settlements)->onDelete('cascade');
            $blueprint->foreign('delivery_order_id')->references('id')->on($orders)->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(ST::name(ST::COURIER_SETTLEMENT_LINES));
        Schema::dropIfExists(ST::COURIER_SETTLEMENT_LINES);
    }
};
