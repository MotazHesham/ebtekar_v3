<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToCommissionRequestsTable extends Migration
{
    public function up()
    {
        Schema::table('commission_requests', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id', 'user_fk_8581953')->references('id')->on('users');
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->foreign('created_by_id', 'created_by_fk_8581954')->references('id')->on('users');
            $table->unsignedBigInteger('done_by_user_id')->nullable();
            $table->foreign('done_by_user_id', 'done_by_user_fk_8581955')->references('id')->on('users');
        });
    }
}
