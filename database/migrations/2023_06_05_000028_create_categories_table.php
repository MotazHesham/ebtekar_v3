<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('slug');
            $table->boolean('design')->default(0)->nullable();
            $table->boolean('featured')->default(0)->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->unsignedBigInteger('website_setting_id')->nullable();
            $table->foreign('website_setting_id', 'website_setting_fk_8598138')->references('id')->on('website_settings');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
