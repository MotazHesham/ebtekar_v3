<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceiptPriceViewProductsTable extends Migration
{
    public function up()
    {
        Schema::create('receipt_price_view_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('description');
            $table->decimal('price', 15, 2);
            $table->integer('quantity');
            $table->decimal('total_cost', 15, 2);
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
