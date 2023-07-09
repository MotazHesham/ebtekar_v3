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
        Schema::create('commission_request_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->decimal('commission', 15, 2);
            $table->unsignedBigInteger('commission_request_id');
            $table->foreign('commission_request_id', 'commission_request_fk_8645681')->references('id')->on('commission_requests');
            $table->unsignedBigInteger('order_id')->nullable();
            $table->foreign('order_id', 'order_fk_85321651')->references('id')->on('orders');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commission_request_orders');
    }
};
