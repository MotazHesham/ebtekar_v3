<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('receipt_social_followups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('receipt_social_id');
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->text('comment')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('receipt_social_id')
                ->references('id')
                ->on('receipt_socials')
                ->onDelete('cascade');

            $table->foreign('created_by_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('receipt_social_followups');
    }
};


