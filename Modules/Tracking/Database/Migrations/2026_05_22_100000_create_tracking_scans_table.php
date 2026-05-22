<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Shipping\Support\ShippingTables as ST;

return new class extends Migration
{
    public function up(): void
    {
        $table    = ST::name(ST::TRACKING_SCANS);
        $orders   = ST::name(ST::DELIVERY_ORDERS);
        $partners = ST::name(ST::SHIPPING_PARTNERS);

        if (Schema::hasTable($table) || Schema::hasTable(ST::TRACKING_SCANS)) {
            return;
        }

        Schema::create($table, function (Blueprint $blueprint) use ($orders, $partners) {
            $blueprint->bigIncrements('id');
            $blueprint->unsignedBigInteger('delivery_order_id')->nullable();
            $blueprint->unsignedBigInteger('shipping_partner_id')->nullable();
            $blueprint->unsignedBigInteger('user_id')->nullable();
            $blueprint->string('scan_type', 32);
            $blueprint->string('barcode');
            $blueprint->string('result', 32);
            $blueprint->string('message')->nullable();
            $blueprint->json('meta')->nullable();
            $blueprint->timestamp('created_at')->useCurrent();

            $blueprint->index(['scan_type', 'created_at']);
            $blueprint->index(['delivery_order_id', 'scan_type']);
            $blueprint->index(['shipping_partner_id', 'scan_type']);
            $blueprint->foreign('delivery_order_id')->references('id')->on($orders)->onDelete('set null');
            $blueprint->foreign('shipping_partner_id')->references('id')->on($partners)->onDelete('set null');
            $blueprint->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(ST::name(ST::TRACKING_SCANS));
        Schema::dropIfExists(ST::TRACKING_SCANS);
    }
};
