<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToOrdersTable extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id', 'user_fk_8582716')->references('id')->on('users');
            $table->unsignedBigInteger('shipping_country_id')->nullable();
            $table->foreign('shipping_country_id', 'shipping_country_fk_8582717')->references('id')->on('countries');
            $table->unsignedBigInteger('designer_id')->nullable();
            $table->foreign('designer_id', 'designer_fk_8582718')->references('id')->on('users');
            $table->unsignedBigInteger('preparer_id')->nullable();
            $table->foreign('preparer_id', 'preparer_fk_8582719')->references('id')->on('users');
            $table->unsignedBigInteger('manufacturer_id')->nullable();
            $table->foreign('manufacturer_id', 'manufacturer_fk_8582720')->references('id')->on('users');
            $table->unsignedBigInteger('shipmenter_id')->nullable();
            $table->foreign('shipmenter_id', 'shipmenter_fk_8582721')->references('id')->on('users');
            $table->unsignedBigInteger('delivery_man_id')->nullable();
            $table->foreign('delivery_man_id', 'delivery_man_fk_8582722')->references('id')->on('users');
        });
    }
}
