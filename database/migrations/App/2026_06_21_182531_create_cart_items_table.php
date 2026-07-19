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
        Schema::create('cart_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('quantity')->default(1);
            $table->string('variant')->nullable();
            $table->string('description')->nullable();
            $table->text('photos')->nullable();
            $table->text('pdf')->nullable();
            $table->string('link')->nullable();
            $table->tinyInteger('email_sent')->default(0);
            $table->unsignedBigInteger('cart_id')->nullable();
            $table->foreign('cart_id', 'cart_fk_10500614')->references('id')->on('carts');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->foreign('product_id', 'product_fk_10500610')->references('id')->on('products');
            $table->unsignedBigInteger('product_stock_id')->nullable();
            $table->foreign('product_stock_id', 'product_stock_fk_102452456')->references('id')->on('product_stocks');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
