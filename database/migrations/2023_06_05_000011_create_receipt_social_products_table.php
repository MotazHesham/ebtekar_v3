<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceiptSocialProductsTable extends Migration
{
    public function up()
    {
        Schema::create('receipt_social_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->decimal('price', 15, 2)->nullable();
            $table->string('commission');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
