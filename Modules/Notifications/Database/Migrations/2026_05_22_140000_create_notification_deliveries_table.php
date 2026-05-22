<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Shipping\Support\ShippingTables as ST;

return new class extends Migration
{
    public function up(): void
    {
        $table  = ST::name(ST::NOTIFICATION_DELIVERIES);
        $orders = ST::name(ST::DELIVERY_ORDERS);

        if (Schema::hasTable($table) || Schema::hasTable(ST::NOTIFICATION_DELIVERIES)) {
            return;
        }

        Schema::create($table, function (Blueprint $blueprint) use ($orders) {
            $blueprint->bigIncrements('id');
            $blueprint->string('channel', 32);
            $blueprint->string('event_type', 64);
            $blueprint->unsignedBigInteger('delivery_order_id')->nullable();
            $blueprint->unsignedBigInteger('user_id')->nullable();
            $blueprint->string('title');
            $blueprint->text('body');
            $blueprint->string('status', 32)->default('pending');
            $blueprint->json('meta')->nullable();
            $blueprint->timestamp('sent_at')->nullable();
            $blueprint->timestamp('created_at')->useCurrent();

            $blueprint->index(['event_type', 'created_at']);
            $blueprint->index(['delivery_order_id', 'channel']);
            $blueprint->index(['user_id', 'status']);
            $blueprint->foreign('delivery_order_id')->references('id')->on($orders)->onDelete('set null');
            $blueprint->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(ST::name(ST::NOTIFICATION_DELIVERIES));
        Schema::dropIfExists(ST::NOTIFICATION_DELIVERIES);
    }
};
