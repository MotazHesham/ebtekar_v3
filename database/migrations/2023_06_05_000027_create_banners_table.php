<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBannersTable extends Migration
{
    public function up()
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('url')->nullable();
            $table->string('position');
            $table->boolean('published')->default(1);
            $table->unsignedBigInteger('website_setting_id')->nullable();
            $table->foreign('website_setting_id', 'website_setting_fk_8500648')->references('id')->on('website_settings');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
