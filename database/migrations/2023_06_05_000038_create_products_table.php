<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('added_by')->nullable();
            $table->decimal('unit_price', 15, 2);
            $table->decimal('purchase_price', 15, 2);
            $table->string('slug');
            $table->longText('attributes')->nullable();
            $table->longText('choice_options')->nullable();
            $table->longText('colors')->nullable();
            $table->longText('tags')->nullable();
            $table->string('video_provider')->nullable();
            $table->string('video_link')->nullable();
            $table->longText('description');
            $table->string('discount_type')->nullable();
            $table->decimal('discount', 15, 2)->nullable();
            $table->string('meta_title')->nullable();
            $table->longText('meta_description')->nullable();
            $table->boolean('flash_deal')->default(0)->nullable();
            $table->boolean('published')->default(0);
            $table->boolean('featured')->default(0)->nullable();
            $table->boolean('todays_deal')->default(0)->nullable();
            $table->boolean('variant_product')->default(0)->nullable();
            $table->float('rating', 15, 2)->nullable();
            $table->integer('current_stock')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
