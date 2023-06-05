<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellersTable extends Migration
{
    public function up()
    {
        Schema::create('sellers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('seller_type');
            $table->decimal('discount', 15, 2)->nullable();
            $table->string('discount_code')->nullable();
            $table->integer('order_out_website')->nullable();
            $table->integer('order_in_website')->nullable();
            $table->string('qualification')->nullable();
            $table->string('social_name');
            $table->string('social_link');
            $table->string('seller_code')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
