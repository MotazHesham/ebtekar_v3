<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeoSettingsTable extends Migration
{
    public function up()
    {
        Schema::create('seo_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('keyword');
            $table->string('author');
            $table->string('sitremap_link');
            $table->longText('description');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
