<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceiptCompaniesTable extends Migration
{
    public function up()
    {
        Schema::create('receipt_companies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('order_num')->nullable();
            $table->string('client_name');
            $table->string('client_type');
            $table->string('phone_number');
            $table->string('phone_number_2')->nullable();
            $table->decimal('deposit', 15, 2)->nullable();
            $table->decimal('total_cost', 15, 2)->nullable();
            $table->boolean('calling')->default(0)->nullable();
            $table->boolean('quickly')->default(0)->nullable();
            $table->boolean('done')->default(0)->nullable();
            $table->boolean('no_answer')->default(0)->nullable();
            $table->boolean('supplied')->default(0)->nullable();
            $table->integer('printing_times')->nullable();
            $table->date('deliver_date')->nullable();
            $table->date('date_of_receiving_order')->nullable();
            $table->datetime('send_to_playlist_date')->nullable();
            $table->datetime('send_to_delivery_date')->nullable();
            $table->datetime('done_time')->nullable();
            $table->decimal('shipping_country_cost', 15, 2);
            $table->longText('shipping_address');
            $table->longText('description');
            $table->longText('note')->nullable();
            $table->longText('cancel_reason')->nullable();
            $table->longText('delay_reason')->nullable();
            $table->string('delivery_status')->default('pending');
            $table->string('payment_status')->default('unpaid');
            $table->string('playlist_status')->default('pending');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
