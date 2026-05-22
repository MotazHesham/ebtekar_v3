<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('delivery_orders')) {
            return;
        }

        Schema::create('delivery_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid')->nullable()->unique();
            $table->string('orderable_type');
            $table->unsignedBigInteger('orderable_id');
            $table->unsignedBigInteger('shipping_partner_id')->nullable();
            $table->unsignedBigInteger('deliver_man_id')->nullable();
            $table->unsignedBigInteger('assigned_by_user_id')->nullable();
            $table->string('status')->default('pending');
            $table->string('return_reason')->nullable();
            $table->text('return_note')->nullable();
            $table->string('order_num')->nullable()->index();
            $table->string('client_name')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('governorate')->nullable();
            $table->string('region')->nullable();
            $table->decimal('cod_amount', 12, 2)->nullable();
            $table->decimal('deposit_amount', 12, 2)->nullable();
            $table->decimal('remaining_cod', 12, 2)->nullable();
            $table->decimal('shipping_cost', 12, 2)->nullable();
            $table->string('payment_status')->nullable();
            $table->dateTime('last_status_at')->nullable()->index();
            $table->dateTime('handed_to_partner_at')->nullable();
            $table->dateTime('received_by_partner_at')->nullable();
            $table->dateTime('out_with_courier_at')->nullable();
            $table->dateTime('first_attempt_at')->nullable();
            $table->dateTime('delivered_at')->nullable();
            $table->dateTime('returned_at')->nullable();
            $table->dateTime('returned_to_warehouse_at')->nullable();
            $table->dateTime('settled_at')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['orderable_type', 'orderable_id']);
            $table->index(['shipping_partner_id', 'status']);
            $table->index(['deliver_man_id', 'status']);

            $table->foreign('shipping_partner_id')->references('id')->on('shipping_partners')->onDelete('set null');
            $table->foreign('deliver_man_id')->references('id')->on('deliver_men')->onDelete('set null');
            $table->foreign('assigned_by_user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_orders');
    }
};
