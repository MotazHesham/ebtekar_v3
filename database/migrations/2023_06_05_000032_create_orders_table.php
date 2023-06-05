<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('paymob_orderid')->nullable();
            $table->string('order_type');
            $table->string('order_num')->nullable();
            $table->string('client_name');
            $table->string('phone_number');
            $table->string('phone_number_2');
            $table->longText('shipping_address');
            $table->string('shipping_country_name');
            $table->decimal('shipping_country_cost', 15, 2);
            $table->decimal('shipping_cost_by_seller', 15, 2)->nullable();
            $table->boolean('free_shipping')->default(0)->nullable();
            $table->string('free_shipping_reason')->nullable();
            $table->integer('printing_times')->nullable();
            $table->boolean('completed')->default(0)->nullable();
            $table->boolean('calling')->default(0)->nullable();
            $table->boolean('supplied')->default(0)->nullable();
            $table->datetime('done_time')->nullable();
            $table->datetime('send_to_delivery_date')->nullable();
            $table->datetime('send_to_playlist_date')->nullable();
            $table->date('date_of_receiving_order')->nullable();
            $table->date('excepected_deliverd_date')->nullable();
            $table->string('playlist_status')->nullable();
            $table->string('payment_status');
            $table->string('delivery_status');
            $table->string('payment_type');
            $table->string('commission_status')->nullable();
            $table->string('deposit_type')->nullable();
            $table->decimal('deposit_amount', 15, 2)->nullable();
            $table->decimal('total_cost_by_seller', 15, 2)->nullable();
            $table->decimal('total_cost', 15, 2)->nullable();
            $table->decimal('commission', 15, 2)->nullable();
            $table->decimal('extra_commission', 15, 2)->nullable();
            $table->decimal('discount', 15, 2)->nullable();
            $table->string('discount_code')->nullable();
            $table->longText('note')->nullable();
            $table->longText('cancel_reason')->nullable();
            $table->longText('delay_reason')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
