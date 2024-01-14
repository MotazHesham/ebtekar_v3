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
        Schema::create('qr_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('product');
            $table->integer('quantity')->default(0); 
            $table->unsignedBigInteger('r_branch_id')->nullable();
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qr_products');
    }
};
