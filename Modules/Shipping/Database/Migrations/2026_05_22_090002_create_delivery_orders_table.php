<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Shipping\Support\ShippingTables as ST;

return new class extends Migration
{
    public function up(): void
    {
        $orders  = ST::name(ST::DELIVERY_ORDERS);
        $partners = ST::name(ST::SHIPPING_PARTNERS);

        if (Schema::hasTable($orders) || Schema::hasTable(ST::DELIVERY_ORDERS)) {
            return;
        }

        Schema::create($orders, function (Blueprint $blueprint) use ($partners) {
            $blueprint->bigIncrements('id');
            $blueprint->uuid('uuid')->nullable()->unique();
            $blueprint->string('orderable_type');
            $blueprint->unsignedBigInteger('orderable_id');
            $blueprint->unsignedBigInteger('shipping_partner_id')->nullable();
            $blueprint->unsignedBigInteger('deliver_man_id')->nullable();
            $blueprint->unsignedBigInteger('assigned_by_user_id')->nullable();
            $blueprint->string('status')->default('pending');
            $blueprint->string('return_reason')->nullable();
            $blueprint->text('return_note')->nullable();
            $blueprint->string('order_num')->nullable()->index();
            $blueprint->string('client_name')->nullable();
            $blueprint->string('phone_number')->nullable();
            $blueprint->string('governorate')->nullable();
            $blueprint->string('region')->nullable();
            $blueprint->decimal('cod_amount', 12, 2)->nullable();
            $blueprint->decimal('deposit_amount', 12, 2)->nullable();
            $blueprint->decimal('remaining_cod', 12, 2)->nullable();
            $blueprint->decimal('shipping_cost', 12, 2)->nullable();
            $blueprint->string('payment_status')->nullable();
            $blueprint->dateTime('last_status_at')->nullable()->index();
            $blueprint->dateTime('handed_to_partner_at')->nullable();
            $blueprint->dateTime('received_by_partner_at')->nullable();
            $blueprint->dateTime('out_with_courier_at')->nullable();
            $blueprint->dateTime('first_attempt_at')->nullable();
            $blueprint->dateTime('delivered_at')->nullable();
            $blueprint->dateTime('returned_at')->nullable();
            $blueprint->dateTime('returned_to_warehouse_at')->nullable();
            $blueprint->dateTime('settled_at')->nullable();
            $blueprint->json('meta')->nullable();
            $blueprint->timestamps();
            $blueprint->softDeletes();

            $blueprint->unique(['orderable_type', 'orderable_id']);
            $blueprint->index(['shipping_partner_id', 'status']);
            $blueprint->index(['deliver_man_id', 'status']);

            $blueprint->foreign('shipping_partner_id')->references('id')->on($partners)->onDelete('set null');
            $blueprint->foreign('deliver_man_id')->references('id')->on('deliver_men')->onDelete('set null');
            $blueprint->foreign('assigned_by_user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(ST::name(ST::DELIVERY_ORDERS));
        Schema::dropIfExists(ST::DELIVERY_ORDERS);
    }
};
