<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceiptClientsTable extends Migration
{
    public function up()
    {
        Schema::create('receipt_clients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('date_of_receiving_order')->nullable();
            $table->string('order_num')->nullable();
            $table->decimal('deposit', 15, 2)->nullable();
            $table->decimal('discount', 15, 2)->nullable();
            $table->longText('note')->nullable();
            $table->decimal('total_cost', 15, 2)->nullable();
            $table->boolean('done')->default(0)->nullable();
            $table->boolean('quickly')->default(0)->nullable();
            $table->integer('printing_times')->nullable();
            $table->string('permission_status')->nullable();
            $table->unsignedBigInteger('r_branche_id')->nullable();
            $table->foreign('r_branch_id', 'r_branch_fk_86546588')->references('id')->on('r_branches');
            $table->unsignedBigInteger('website_setting_id')->nullable();
            $table->foreign('website_setting_id', 'website_setting_fk_86542348')->references('id')->on('website_settings');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
