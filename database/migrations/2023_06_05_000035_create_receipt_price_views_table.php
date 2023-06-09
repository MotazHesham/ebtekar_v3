<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceiptPriceViewsTable extends Migration
{
    public function up()
    {
        Schema::create('receipt_price_views', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('order_num')->nullable();
            $table->date('date_of_receiving_order')->nullable();
            $table->string('client_name');
            $table->string('phone_number');
            $table->decimal('total_cost', 15, 2)->default(0);
            $table->string('place')->nullable();
            $table->string('relate_duration')->nullable();
            $table->string('supply_duration')->nullable();
            $table->string('payment')->nullable();
            $table->boolean('added_value')->default(0)->nullable();
            $table->integer('printing_times')->nullable();
            $table->unsignedBigInteger('website_setting_id')->nullable();
            $table->foreign('website_setting_id', 'website_setting_fk_85913648')->references('id')->on('website_settings');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
