<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToReceiptCompaniesTable extends Migration
{
    public function up()
    {
        Schema::table('receipt_companies', function (Blueprint $table) {
            $table->unsignedBigInteger('staff_id')->nullable();
            $table->foreign('staff_id', 'staff_fk_8581640')->references('id')->on('users');
            $table->unsignedBigInteger('designer_id')->nullable();
            $table->foreign('designer_id', 'designer_fk_8581641')->references('id')->on('users');
            $table->unsignedBigInteger('preparer_id')->nullable();
            $table->foreign('preparer_id', 'preparer_fk_8581642')->references('id')->on('users');
            $table->unsignedBigInteger('manufacturer_id')->nullable();
            $table->foreign('manufacturer_id', 'manufacturer_fk_8581643')->references('id')->on('users');
            $table->unsignedBigInteger('shipmenter_id')->nullable();
            $table->foreign('shipmenter_id', 'shipmenter_fk_8581644')->references('id')->on('users');
            $table->unsignedBigInteger('delivery_man_id')->nullable();
            $table->foreign('delivery_man_id', 'delivery_man_fk_8581645')->references('id')->on('users');
            $table->unsignedBigInteger('shipping_country_id')->nullable();
            $table->foreign('shipping_country_id', 'shipping_country_fk_8582257')->references('id')->on('countries');
        });
    }
}
