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
        Schema::create('product_stocks', function (Blueprint $table) {
            $table->bigIncrements('id'); 
            $table->string('variant');
            $table->decimal('unit_price', 15, 2);
            $table->decimal('purchase_price', 15, 2);
            $table->integer('stock');
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id', 'product_fk_8564281')->references('id')->on('products');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_stocks');
    }
};
