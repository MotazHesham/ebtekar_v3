<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('receipt_social_box_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('receipt_social_product_pivot_id');
            $table->foreign('receipt_social_product_pivot_id', 'rs_box_details_pivot_id_fk')->references('id')->on('receipt_social_receipt_social_product')->onDelete('cascade');
            $table->unsignedBigInteger('receipt_social_product_id');
            $table->foreign('receipt_social_product_id', 'rs_box_details_product_id_fk')->references('id')->on('receipt_social_products')->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('price', 15, 2);
            $table->decimal('total_cost', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receipt_social_box_details');
    }
};
