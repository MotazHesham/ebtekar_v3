<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceiptSocialProductsTable extends Migration
{
    public function up()
    {
        Schema::create('receipt_social_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('product_type')->nullable();
            $table->string('name');
            $table->decimal('price', 15, 2);
            $table->decimal('commission', 15, 2)->nullable(); 
            $table->string('shopify_id')->nullable();
            $table->string('shopify_variant_id')->nullable();
            $table->text('shopify_images')->nullable();
            $table->unsignedBigInteger('website_setting_id')->nullable();
            $table->foreign('website_setting_id', 'website_setting_fk_89976438')->references('id')->on('website_settings');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
