<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMockupsTable extends Migration
{
    public function up()
    {
        Schema::create('mockups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->text('preview_1');
            $table->text('preview_2')->nullable();
            $table->text('preview_3')->nullable();
            $table->string('video_provider')->nullable();
            $table->string('video_link')->nullable();
            $table->decimal('purchase_price', 15, 2);
            $table->longText('attributes')->nullable();
            $table->longText('attribute_options')->nullable();
            $table->longText('colors')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id', 'category_fk_8583331')->references('id')->on('categories');
            $table->unsignedBigInteger('sub_category_id')->nullable();
            $table->foreign('sub_category_id', 'sub_category_fk_8583332')->references('id')->on('sub_categories');
            $table->unsignedBigInteger('sub_sub_category_id')->nullable();
            $table->foreign('sub_sub_category_id', 'sub_sub_category_fk_8583333')->references('id')->on('sub_sub_categories');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
