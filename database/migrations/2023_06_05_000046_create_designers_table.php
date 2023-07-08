<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDesignersTable extends Migration
{
    public function up()
    {
        Schema::create('designers', function (Blueprint $table) {
            $table->bigIncrements('id'); 
            $table->string('store_name')->unique();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id', 'user_fk_85329929')->references('id')->on('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
