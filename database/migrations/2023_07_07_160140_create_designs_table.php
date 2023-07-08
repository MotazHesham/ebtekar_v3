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
        Schema::create('designs', function (Blueprint $table) {
            $table->bigIncrements('id'); 
            $table->string('design_name'); 
            $table->decimal('profit', 15, 2);
            $table->longText('colors')->nullable();
            $table->longText('dataset1');
            $table->longText('dataset2')->nullable();
            $table->longText('dataset3')->nullable();
            $table->string('status')->default('pending'); 
            $table->string('cancel_reason')->nullable(); 
            $table->unsignedBigInteger('mockup_id');
            $table->foreign('mockup_id', 'mockup_fk_8500071')->references('id')->on('mockups');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id', 'user_fk_850801')->references('id')->on('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('designs');
    }
};
