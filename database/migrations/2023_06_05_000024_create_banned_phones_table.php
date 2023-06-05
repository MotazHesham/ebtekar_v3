<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBannedPhonesTable extends Migration
{
    public function up()
    {
        Schema::create('banned_phones', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('phone');
            $table->longText('reason');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
