<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceiptOutgoingsTable extends Migration
{
    public function up()
    {
        Schema::create('receipt_outgoings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('order_num')->nullable();
            $table->date('date_of_receiving_order')->nullable();
            $table->string('client_name');
            $table->string('phone_number');
            $table->decimal('total_cost', 15, 2)->nullable();
            $table->longText('note')->nullable();
            $table->boolean('done')->default(0)->nullable();
            $table->integer('printing_times')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
