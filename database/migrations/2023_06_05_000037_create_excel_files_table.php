<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExcelFilesTable extends Migration
{
    public function up()
    {
        Schema::create('excel_files', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type')->nullable();
            $table->string('type2')->default('done');
            $table->longText('results');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
