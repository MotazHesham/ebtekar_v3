<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceiptClientProductsTable extends Migration
{
    public function up()
    {
        Schema::create('receipt_client_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->decimal('price', 15, 2);
            $table->unsignedBigInteger('website_setting_id')->nullable();
            $table->foreign('website_setting_id', 'website_setting_fk_80031697')->references('id')->on('website_settings');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
