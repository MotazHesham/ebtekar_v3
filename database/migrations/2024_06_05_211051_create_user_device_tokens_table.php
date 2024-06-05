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
        Schema::create('user_device_tokens', function (Blueprint $table) {
            $table->bigIncrements('id'); 
            $table->text('device_token');
            $table->text('userAgent')->nullable();
            $table->string('device_type')->nullable();
            $table->string('browser_type')->nullable();
            $table->string('browser_version')->nullable();
            $table->string('platform_type')->nullable();
            $table->string('playform_version')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id', 'user_fk_89258161')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_device_tokens');
    }
};
