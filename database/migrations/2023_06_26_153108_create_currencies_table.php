<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurrenciesTable extends Migration
{
    public function up()
    {
        Schema::create('currencies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('symbol');
            $table->decimal('exchange_rate', 15, 2);
            $table->boolean('status')->default(1);
            $table->string('code');
            $table->decimal('half_kg', 15, 2);
            $table->decimal('one_kg', 15, 2);
            $table->decimal('one_half_kg', 15, 2);
            $table->decimal('two_kg', 15, 2);
            $table->decimal('two_half_kg', 15, 2);
            $table->decimal('three_kg', 15, 2);
            $table->timestamps();
            $table->softDeletes();
        });
    }
}