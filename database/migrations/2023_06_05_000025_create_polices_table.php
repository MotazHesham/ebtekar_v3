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
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
