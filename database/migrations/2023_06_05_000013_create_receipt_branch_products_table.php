<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceiptBranchProductsTable extends Migration
{
    public function up()
    {
        Schema::create('receipt_branch_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->decimal('price', 15, 2);
            $table->decimal('price_parts', 15, 2);
            $table->decimal('price_permissions', 15, 2);
            $table->unsignedBigInteger('website_setting_id')->nullable();
            $table->foreign('website_setting_id', 'website_setting_fk_80697697')->references('id')->on('website_settings');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
