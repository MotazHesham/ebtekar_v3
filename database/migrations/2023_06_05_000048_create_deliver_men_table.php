<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliverMenTable extends Migration
{
    public function up()
    {
        Schema::create('deliver_men', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('user');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
