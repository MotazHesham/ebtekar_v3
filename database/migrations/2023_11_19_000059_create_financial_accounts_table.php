<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinancialAccountsTable extends Migration
{
    public function up()
    {
        Schema::create('financial_accounts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('account');
            $table->string('description')->nullable();
            $table->boolean('active')->default(0)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
