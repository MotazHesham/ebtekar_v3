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
        Schema::create('qr_product_rbranches', function (Blueprint $table) {
            $table->bigIncrements('id'); 
            $table->integer('quantity')->default(0); 
            $table->text('names')->nullable(); 
            $table->unsignedBigInteger('qr_product_id')->nullable();
            $table->unsignedBigInteger('r_branch_id')->nullable();
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qr_product_rbranches');
    }
};
