<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('designer_images', function (Blueprint $table) {
            $table->bigIncrements('id'); 
            $table->text('image'); 
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id', 'user_fk_8963171')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('designer_images');
    }
};
