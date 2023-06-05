<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMockupsTable extends Migration
{
    public function up()
    {
        Schema::create('mockups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->longText('description')->nullable();
            $table->string('video_provider')->nullable();
            $table->string('video_link')->nullable();
            $table->decimal('purchase_price', 15, 2);
            $table->longText('attributes')->nullable();
            $table->longText('choice_options')->nullable();
            $table->longText('colors')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
