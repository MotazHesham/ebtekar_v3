<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommissionRequestsTable extends Migration
{
    public function up()
    {
        Schema::create('commission_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('status');
            $table->decimal('total_commission', 15, 2);
            $table->string('payment_method');
            $table->string('transfer_number')->nullable();
            $table->datetime('done_time')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
