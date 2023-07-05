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
        Schema::create('carts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('variation')->nullable();
            $table->text('description')->nullable();
            $table->integer('quantity'); 
            $table->text('photos')->nullable();
            $table->text('pdf')->nullable();
            $table->string('link')->nullable();
            $table->tinyInteger('email_sent')->default(0);
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id', 'product_fk_8505671')->references('id')->on('products');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id', 'user_fk_8500631')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
