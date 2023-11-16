<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToReceiptClientsTable extends Migration
{
    public function up()
    {
        Schema::table('receipt_clients', function (Blueprint $table) {
            $table->unsignedBigInteger('staff_id')->nullable();
            $table->foreign('staff_id', 'staff_fk_8581590')->references('id')->on('users');
        });
    }
}
