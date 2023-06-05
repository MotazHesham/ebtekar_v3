<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToReceiptSocialsTable extends Migration
{
    public function up()
    {
        Schema::table('receipt_socials', function (Blueprint $table) {
            $table->unsignedBigInteger('staff_id')->nullable();
            $table->foreign('staff_id', 'staff_fk_8581499')->references('id')->on('users');
            $table->unsignedBigInteger('designer_id')->nullable();
            $table->foreign('designer_id', 'designer_fk_8581500')->references('id')->on('users');
            $table->unsignedBigInteger('preparer_id')->nullable();
            $table->foreign('preparer_id', 'preparer_fk_8581501')->references('id')->on('users');
            $table->unsignedBigInteger('manufacturer_id')->nullable();
            $table->foreign('manufacturer_id', 'manufacturer_fk_8581502')->references('id')->on('users');
            $table->unsignedBigInteger('shipmenter_id')->nullable();
            $table->foreign('shipmenter_id', 'shipmenter_fk_8581503')->references('id')->on('users');
            $table->unsignedBigInteger('delivery_man_id')->nullable();
            $table->foreign('delivery_man_id', 'delivery_man_fk_8581504')->references('id')->on('users');
            $table->unsignedBigInteger('shipping_country_id')->nullable();
            $table->foreign('shipping_country_id', 'shipping_country_fk_8582213')->references('id')->on('countries');
        });
    }
}
