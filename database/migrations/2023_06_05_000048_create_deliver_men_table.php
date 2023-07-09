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
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id', 'user_fk_893178073')->references('id')->on('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
