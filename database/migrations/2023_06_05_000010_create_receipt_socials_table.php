<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceiptSocialsTable extends Migration
{
    public function up()
    {
        Schema::create('receipt_socials', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('order_num')->nullable();
            $table->string('client_name');
            $table->string('client_type');
            $table->string('phone_number');
            $table->string('phone_number_2')->nullable();
            $table->decimal('deposit', 15, 2)->nullable();
            $table->decimal('discount', 15, 2)->nullable();
            $table->decimal('commission', 15, 2)->nullable();
            $table->decimal('extra_commission', 15, 2)->nullable();
            $table->decimal('total_cost', 15, 2)->nullable();
            $table->boolean('done')->default(0)->nullable();
            $table->boolean('quickly')->default(0)->nullable();
            $table->boolean('confirm')->default(0)->nullable();
            $table->boolean('returned')->default(0)->nullable();
            $table->boolean('supplied')->default(0)->nullable();
            $table->boolean('is_seasoned')->default(0)->nullable();
            $table->boolean('client_review')->default(0)->nullable();
            $table->boolean('hold')->default(0)->nullable();
            $table->longText('hold_reason')->nullable();
            $table->integer('printing_times')->nullable(); 
            $table->decimal('shipping_country_cost', 15, 2);
            $table->longText('shipping_address');
            $table->date('date_of_receiving_order')->nullable();
            $table->date('deliver_date')->nullable();
            $table->datetime('send_to_delivery_date')->nullable();
            $table->datetime('send_to_playlist_date')->nullable();
            $table->datetime('done_time')->nullable();
            $table->longText('cancel_reason')->nullable();
            $table->longText('delay_reason')->nullable();
            $table->string('delivery_status')->default('pending');
            $table->longText('note')->nullable();
            $table->string('payment_status')->default('unpaid');
            $table->string('deposit_type')->nullable();
            $table->string('playlist_status')->default('pending');
            $table->unsignedBigInteger('website_setting_id')->nullable();
            $table->foreign('website_setting_id', 'website_setting_fk_8698748')->references('id')->on('website_settings');
            $table->unsignedBigInteger('financial_account_id')->nullable();
            $table->foreign('financial_account_id', 'financial_account_fk_8690008')->references('id')->on('financial_accounts');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
