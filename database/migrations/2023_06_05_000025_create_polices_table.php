<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePolicesTable extends Migration
{
    public function up()
    {
        Schema::create('polices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->longText('content');
            $table->unsignedBigInteger('website_setting_id')->nullable();
            $table->foreign('website_setting_id', 'website_setting_fk_8598748')->references('id')->on('website_settings');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
