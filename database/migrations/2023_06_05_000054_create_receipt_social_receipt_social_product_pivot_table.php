<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceiptSocialReceiptSocialProductPivotTable extends Migration
{
    public function up()
    {
        Schema::create('receipt_social_receipt_social_product', function (Blueprint $table) {
            $table->bigIncrements('id'); 
            $table->string('title'); 
            $table->text('description')->nullable(); 
            $table->integer('quantity'); 
            $table->decimal('price', 15, 2); 
            $table->decimal('total_cost', 15, 2); 
            $table->decimal('commission', 15, 2); 
            $table->decimal('extra_commission', 15, 2)->nullable(); 
            $table->text('pdf')->nullable();
            $table->longText('photos')->nullable();
            $table->unsignedBigInteger('receipt_social_product_id');
            $table->foreign('receipt_social_product_id', 'receipt_social_product_id_fk_8581563')->references('id')->on('receipt_social_products')->onDelete('cascade');
            $table->unsignedBigInteger('receipt_social_id');
            $table->foreign('receipt_social_id', 'receipt_social_id_fk_8581563')->references('id')->on('receipt_socials')->onDelete('cascade');
            $table->timestamps();
        });
    }
}
