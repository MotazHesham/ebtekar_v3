<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Shipping\Support\ShippingTables as ST;

return new class extends Migration
{
    public function up(): void
    {
        $table    = ST::name(ST::RETURN_CASES);
        $orders   = ST::name(ST::DELIVERY_ORDERS);
        $partners = ST::name(ST::SHIPPING_PARTNERS);

        if (Schema::hasTable($table) || Schema::hasTable(ST::RETURN_CASES)) {
            return;
        }

        Schema::create($table, function (Blueprint $blueprint) use ($orders, $partners) {
            $blueprint->bigIncrements('id');
            $blueprint->char('uuid', 36)->unique();
            $blueprint->unsignedBigInteger('delivery_order_id');
            $blueprint->unsignedBigInteger('deliver_man_id')->nullable();
            $blueprint->unsignedBigInteger('shipping_partner_id')->nullable();
            $blueprint->unsignedBigInteger('created_by_user_id')->nullable();
            $blueprint->string('reason', 64);
            $blueprint->text('note')->nullable();
            $blueprint->string('shipment_status', 32);
            $blueprint->string('status', 32)->default('open');
            $blueprint->timestamp('warehouse_received_at')->nullable();
            $blueprint->timestamp('closed_at')->nullable();
            $blueprint->timestamps();

            $blueprint->index(['status', 'created_at']);
            $blueprint->index(['delivery_order_id', 'status']);
            $blueprint->foreign('delivery_order_id')->references('id')->on($orders)->onDelete('cascade');
            $blueprint->foreign('deliver_man_id')->references('id')->on('deliver_men')->onDelete('set null');
            $blueprint->foreign('shipping_partner_id')->references('id')->on($partners)->onDelete('set null');
            $blueprint->foreign('created_by_user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(ST::name(ST::RETURN_CASES));
        Schema::dropIfExists(ST::RETURN_CASES);
    }
};
