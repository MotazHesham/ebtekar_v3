<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToReceiptOutgoingProductsTable extends Migration
{
    public function up()
    {
        Schema::table('receipt_outgoing_products', function (Blueprint $table) {
            $table->unsignedBigInteger('receipt_outgoing_id')->nullable();
            $table->foreign('receipt_outgoing_id', 'receipt_outgoing_fk_8582755')->references('id')->on('receipt_outgoings');
        });
    }
}
