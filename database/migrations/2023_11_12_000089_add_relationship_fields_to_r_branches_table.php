<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToRBranchesTable extends Migration
{
    public function up()
    {
        Schema::table('r_branches', function (Blueprint $table) {
            $table->unsignedBigInteger('r_client_id')->nullable();
            $table->foreign('r_client_id', 'r_client_fk_9208074')->references('id')->on('r_clients');
        });
    }
}
