<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToEmployeeFinancialsTable extends Migration
{
    public function up()
    {
        Schema::table('employee_financials', function (Blueprint $table) {
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->foreign('employee_id', 'employee_fk_9221128')->references('id')->on('employees');
            $table->unsignedBigInteger('financial_category_id')->nullable();
            $table->foreign('financial_category_id', 'financial_category_fk_9221129')->references('id')->on('financial_categories');
        });
    }
}
