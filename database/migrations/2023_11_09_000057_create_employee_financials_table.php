<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeFinancialsTable extends Migration
{
    public function up()
    {
        Schema::create('employee_financials', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->decimal('amount', 15, 2);
            $table->longText('reason')->nullable();
            $table->date('entry_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
