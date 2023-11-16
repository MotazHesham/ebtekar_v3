<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToReceiptBranchesTable extends Migration
{
    public function up()
    {
        Schema::table('receipt_branches', function (Blueprint $table) {
            $table->unsignedBigInteger('staff_id')->nullable();
            $table->foreign('staff_id', 'staff_fk_8532190')->references('id')->on('users');
        });
    }
}
