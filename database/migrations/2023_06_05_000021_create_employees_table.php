<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email');
            $table->string('phone_number');
            $table->decimal('salery', 15, 2);
            $table->string('address')->nullable();
            $table->string('job_description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
