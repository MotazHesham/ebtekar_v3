<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToGeneralSettingsTable extends Migration
{
    public function up()
    {
        Schema::table('general_settings', function (Blueprint $table) {
            $table->unsignedBigInteger('designer_id')->nullable();
            $table->foreign('designer_id', 'designer_fk_8581724')->references('id')->on('users');
            $table->unsignedBigInteger('preparer_id')->nullable();
            $table->foreign('preparer_id', 'preparer_fk_8581725')->references('id')->on('users');
            $table->unsignedBigInteger('manufacturer_id')->nullable();
            $table->foreign('manufacturer_id', 'manufacturer_fk_8581726')->references('id')->on('users');
            $table->unsignedBigInteger('shipment_id')->nullable();
            $table->foreign('shipment_id', 'shipment_fk_8581727')->references('id')->on('users');
        });
    }
}
