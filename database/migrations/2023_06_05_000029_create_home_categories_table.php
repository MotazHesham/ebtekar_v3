<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHomeCategoriesTable extends Migration
{
    public function up()
    {
        Schema::create('home_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('website_setting_id')->nullable();
            $table->foreign('website_setting_id', 'website_setting_fk_8954318')->references('id')->on('website_settings');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
