<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Shipping\Support\ShippingTables as ST;

return new class extends Migration
{
    public function up(): void
    {
        $table  = ST::name(ST::DELIVERY_TIMELINE_EVENTS);
        $orders = ST::name(ST::DELIVERY_ORDERS);

        if (Schema::hasTable($table) || Schema::hasTable(ST::DELIVERY_TIMELINE_EVENTS)) {
            return;
        }

        Schema::create($table, function (Blueprint $blueprint) use ($orders) {
            $blueprint->bigIncrements('id');
            $blueprint->unsignedBigInteger('delivery_order_id');
            $blueprint->unsignedBigInteger('user_id')->nullable();
            $blueprint->string('event_type');
            $blueprint->string('old_status')->nullable();
            $blueprint->string('new_status')->nullable();
            $blueprint->text('body')->nullable();
            $blueprint->json('meta')->nullable();
            $blueprint->timestamp('created_at')->useCurrent();

            $blueprint->index(['delivery_order_id', 'created_at']);
            $blueprint->foreign('delivery_order_id')->references('id')->on($orders)->onDelete('cascade');
            $blueprint->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(ST::name(ST::DELIVERY_TIMELINE_EVENTS));
        Schema::dropIfExists(ST::DELIVERY_TIMELINE_EVENTS);
    }
};
