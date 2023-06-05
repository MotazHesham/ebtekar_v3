<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceiptClientReceiptClientProductPivotTable extends Migration
{
    public function up()
    {
        Schema::create('receipt_client_receipt_client_product', function (Blueprint $table) {
            $table->unsignedBigInteger('receipt_client_product_id');
            $table->foreign('receipt_client_product_id', 'receipt_client_product_id_fk_8581600')->references('id')->on('receipt_client_products')->onDelete('cascade');
            $table->unsignedBigInteger('receipt_client_id');
            $table->foreign('receipt_client_id', 'receipt_client_id_fk_8581600')->references('id')->on('receipt_clients')->onDelete('cascade');
        });
    }
}
