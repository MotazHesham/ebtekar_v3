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
        Schema::create('settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('key')->nullable();
            $table->longText('value')->nullable();
            $table->string('name')->nullable();
            $table->string('options')->nullable();
            $table->string('lang')->nullable();
            $table->string('data_type')->nullable();
            $table->string('group_name')->nullable();
            $table->integer('order_level')->nullable();
            $table->integer('grid_col')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
