<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSlidersTable extends Migration
{
    public function up()
    {
        Schema::create('sliders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('published')->default(1);
            $table->string('link')->nullable();
            $table->unsignedBigInteger('website_setting_id')->nullable();
            $table->foreign('website_setting_id', 'website_setting_fk_8566548')->references('id')->on('website_settings');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
