<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Shipping\Support\ShippingTables as ST;

return new class extends Migration
{
    public function up(): void
    {
        $table  = ST::name(ST::DELIVERY_NOTES);
        $orders = ST::name(ST::DELIVERY_ORDERS);

        if (Schema::hasTable($table) || Schema::hasTable(ST::DELIVERY_NOTES)) {
            return;
        }

        Schema::create($table, function (Blueprint $blueprint) use ($orders, $table) {
            $blueprint->bigIncrements('id');
            $blueprint->unsignedBigInteger('delivery_order_id');
            $blueprint->unsignedBigInteger('user_id')->nullable();
            $blueprint->unsignedBigInteger('parent_id')->nullable();
            $blueprint->text('body');
            $blueprint->timestamps();
            $blueprint->softDeletes();

            $blueprint->index(['delivery_order_id', 'created_at']);
            $blueprint->foreign('delivery_order_id')->references('id')->on($orders)->onDelete('cascade');
            $blueprint->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $blueprint->foreign('parent_id')->references('id')->on($table)->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(ST::name(ST::DELIVERY_NOTES));
        Schema::dropIfExists(ST::DELIVERY_NOTES);
    }
};
