<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRBranchesTable extends Migration
{
    public function up()
    {
        Schema::create('r_branches', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('phone_number');
            $table->decimal('remaining', 15, 2)->default(0);
            $table->string('payment_type')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
