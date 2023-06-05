<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceiptOutgoingProductsTable extends Migration
{
    public function up()
    {
        Schema::create('receipt_outgoing_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('description')->nullable();
            $table->decimal('price', 15, 2);
            $table->integer('quantity');
            $table->decimal('total_cost', 15, 2);
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
