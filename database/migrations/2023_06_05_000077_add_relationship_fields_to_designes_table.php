<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToDesignesTable extends Migration
{
    public function up()
    {
        Schema::table('designes', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id', 'user_fk_8583414')->references('id')->on('users');
            $table->unsignedBigInteger('mockup_id')->nullable();
            $table->foreign('mockup_id', 'mockup_fk_8583415')->references('id')->on('mockups');
        });
    }
}
