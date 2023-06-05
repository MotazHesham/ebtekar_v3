<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountriesTable extends Migration
{
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->decimal('cost', 15, 2);
            $table->string('code')->nullable();
            $table->decimal('code_cost', 15, 2)->nullable();
            $table->string('type');
            $table->boolean('status')->default(0);
            $table->boolean('website')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
