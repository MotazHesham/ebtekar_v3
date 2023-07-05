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
        Schema::create('order_details', function (Blueprint $table) { 
            $table->bigIncrements('id');
            $table->text('variation')->nullable();
            $table->text('description')->nullable();
            $table->integer('quantity');
            $table->decimal('price', 15, 2);
            $table->decimal('weight_price', 15, 2);
            $table->decimal('total_cost', 15, 2);
            $table->decimal('commission', 15, 2);
            $table->text('photos')->nullable();
            $table->text('pdf')->nullable();
            $table->string('link')->nullable();
            $table->tinyInteger('email_sent')->default(0);
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id', 'product_fk_8563215')->references('id')->on('products');
            $table->unsignedBigInteger('order_id');
            $table->foreign('order_id', 'order_fk_8566983')->references('id')->on('orders');
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
