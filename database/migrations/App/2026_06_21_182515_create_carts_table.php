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
        Schema::dropIfExists('carts');
        Schema::create('carts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('temp_user_uid')->nullable();
            $table->string('note')->nullable();
            $table->string('coupon_code')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id', 'user_fk_10500614')->references('id')->on('users');
            $table->timestamp('reminder_sent_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
