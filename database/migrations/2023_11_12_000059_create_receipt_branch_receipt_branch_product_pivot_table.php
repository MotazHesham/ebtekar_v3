<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceiptBranchReceiptBranchProductPivotTable extends Migration
{
    public function up()
    {
        Schema::create('receipt_branch_receipt_branch_product', function (Blueprint $table) {
            $table->bigIncrements('id');  
            $table->text('description')->nullable(); 
            $table->integer('quantity'); 
            $table->decimal('price', 15, 2); 
            $table->decimal('total_cost', 15, 2);  
            $table->unsignedBigInteger('receipt_branch_product_id')->nullable();
            $table->foreign('receipt_branch_product_id', 'receipt_branch_product_id_fk_8581600')->references('id')->on('receipt_branch_products')->onDelete('cascade');
            $table->unsignedBigInteger('receipt_branch_id');
            $table->foreign('receipt_branch_id', 'receipt_branch_id_fk_8581600')->references('id')->on('receipt_branches')->onDelete('cascade');
            $table->timestamps();
        });
    }
}
