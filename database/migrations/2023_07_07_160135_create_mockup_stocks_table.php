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
        Schema::create('mockup_stocks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('variant');
            $table->decimal('price', 15, 2);
            $table->integer('quantity');
            $table->unsignedBigInteger('mockup_id');
            $table->foreign('mockup_id', 'mockup_fk_8532171')->references('id')->on('mockups');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mockup_stocks');
    }
};
