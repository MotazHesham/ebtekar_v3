<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMetricsToEmployeeShiftsTable extends Migration
{
    public function up()
    {
        Schema::table('employee_shifts', function (Blueprint $table) {
            $table->json('metrics')->nullable()->after('shift_date');
        });
    }

    public function down()
    {
        Schema::table('employee_shifts', function (Blueprint $table) {
            $table->dropColumn('metrics');
        });
    }
}

