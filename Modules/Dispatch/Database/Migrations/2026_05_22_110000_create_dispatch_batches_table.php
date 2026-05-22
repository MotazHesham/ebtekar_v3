<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Shipping\Support\ShippingTables as ST;

return new class extends Migration
{
    public function up(): void
    {
        $batches  = ST::name(ST::DISPATCH_BATCHES);
        $items    = ST::name(ST::DISPATCH_BATCH_ITEMS);
        $partners = ST::name(ST::SHIPPING_PARTNERS);
        $orders   = ST::name(ST::DELIVERY_ORDERS);

        if (! Schema::hasTable($batches) && ! Schema::hasTable(ST::DISPATCH_BATCHES)) {
            Schema::create($batches, function (Blueprint $blueprint) use ($partners) {
                $blueprint->bigIncrements('id');
                $blueprint->string('type', 32);
                $blueprint->string('status', 32)->default('completed');
                $blueprint->unsignedBigInteger('shipping_partner_id')->nullable();
                $blueprint->unsignedBigInteger('courier_id')->nullable();
                $blueprint->unsignedBigInteger('created_by_user_id')->nullable();
                $blueprint->unsignedInteger('total_count')->default(0);
                $blueprint->unsignedInteger('success_count')->default(0);
                $blueprint->unsignedInteger('failed_count')->default(0);
                $blueprint->json('meta')->nullable();
                $blueprint->timestamps();

                $blueprint->index(['type', 'created_at']);
                $blueprint->foreign('shipping_partner_id')->references('id')->on($partners)->onDelete('set null');
                $blueprint->foreign('courier_id')->references('id')->on('deliver_men')->onDelete('set null');
                $blueprint->foreign('created_by_user_id')->references('id')->on('users')->onDelete('set null');
            });
        }

        if (Schema::hasTable($items) || Schema::hasTable(ST::DISPATCH_BATCH_ITEMS)) {
            return;
        }

        Schema::create($items, function (Blueprint $blueprint) use ($batches, $orders) {
            $blueprint->bigIncrements('id');
            $blueprint->unsignedBigInteger('dispatch_batch_id');
            $blueprint->unsignedBigInteger('delivery_order_id');
            $blueprint->unsignedBigInteger('courier_id')->nullable();
            $blueprint->string('result', 32);
            $blueprint->text('message')->nullable();
            $blueprint->timestamp('created_at')->useCurrent();

            $blueprint->index(['dispatch_batch_id', 'result']);
            $blueprint->foreign('dispatch_batch_id')->references('id')->on($batches)->onDelete('cascade');
            $blueprint->foreign('delivery_order_id')->references('id')->on($orders)->onDelete('cascade');
            $blueprint->foreign('courier_id')->references('id')->on('deliver_men')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(ST::name(ST::DISPATCH_BATCH_ITEMS));
        Schema::dropIfExists(ST::DISPATCH_BATCH_ITEMS);
        Schema::dropIfExists(ST::name(ST::DISPATCH_BATCHES));
        Schema::dropIfExists(ST::DISPATCH_BATCHES);
    }
};
