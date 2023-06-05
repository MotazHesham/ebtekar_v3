<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToReceiptOutgoingsTable extends Migration
{
    public function up()
    {
        Schema::table('receipt_outgoings', function (Blueprint $table) {
            $table->unsignedBigInteger('staff_id')->nullable();
            $table->foreign('staff_id', 'staff_fk_8582749')->references('id')->on('users');
        });
    }
}
