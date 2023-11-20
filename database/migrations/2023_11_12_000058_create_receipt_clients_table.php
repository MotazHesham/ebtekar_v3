<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceiptClientsTable extends Migration
{
    public function up()
    {
        Schema::create('receipt_clients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('date_of_receiving_order')->nullable();
            $table->string('order_num')->nullable();
            $table->string('client_name');
            $table->string('phone_number');
            $table->decimal('deposit', 15, 2)->nullable();
            $table->string('deposit_type')->nullable();
            $table->decimal('discount', 15, 2)->nullable();
            $table->longText('note')->nullable();
            $table->decimal('total_cost', 15, 2)->nullable();
            $table->boolean('done')->default(0)->nullable();
            $table->boolean('quickly')->default(0)->nullable();
            $table->integer('printing_times')->nullable();
            $table->unsignedBigInteger('website_setting_id')->nullable();
            $table->foreign('website_setting_id', 'website_setting_fk_86542348')->references('id')->on('website_settings');
            $table->unsignedBigInteger('financial_account_id')->nullable();
            $table->foreign('financial_account_id', 'financial_account_fk_8699908')->references('id')->on('financial_accounts');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
