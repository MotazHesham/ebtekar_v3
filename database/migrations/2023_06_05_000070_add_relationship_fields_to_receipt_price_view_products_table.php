<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToReceiptPriceViewProductsTable extends Migration
{
    public function up()
    {
        Schema::table('receipt_price_view_products', function (Blueprint $table) {
            $table->unsignedBigInteger('receipt_price_view_id')->nullable();
            $table->foreign('receipt_price_view_id', 'receipt_price_view_fk_8582804')->references('id')->on('receipt_price_views');
        });
    }
}
