<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDesignesTable extends Migration
{
    public function up()
    {
        Schema::create('designes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('design_name');
            $table->decimal('profit', 15, 2);
            $table->longText('colors')->nullable();
            $table->longText('dataset_1')->nullable();
            $table->longText('dataset_2')->nullable();
            $table->longText('dataset_3')->nullable();
            $table->string('status');
            $table->longText('cancel_reason')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
