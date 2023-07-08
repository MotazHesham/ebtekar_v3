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
        Schema::create('design_images', function (Blueprint $table) {
            $table->bigIncrements('id'); 
            $table->text('image'); 
            $table->string('color'); 
            $table->string('preview'); 
            $table->unsignedBigInteger('design_id');
            $table->foreign('design_id', 'design_fk_8560071')->references('id')->on('designs');
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('design_images');
    }
};
