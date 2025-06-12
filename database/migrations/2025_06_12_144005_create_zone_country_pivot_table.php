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
        Schema::create('zone_country_pivot', function (Blueprint $table) {
            $table->bigIncrements('id'); 
            $table->unsignedBigInteger('zone_id')->nullable();
            $table->foreign('zone_id', 'zone_id_fk_8581590')->references('id')->on('zones');
            $table->unsignedBigInteger('country_id')->nullable();
            $table->foreign('country_id', 'country_id_fk_8581590')->references('id')->on('countries'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zone_country_pivot');
    }
};
