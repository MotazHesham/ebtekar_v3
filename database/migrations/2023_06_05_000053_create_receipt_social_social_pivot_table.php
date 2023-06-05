<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceiptSocialSocialPivotTable extends Migration
{
    public function up()
    {
        Schema::create('receipt_social_social', function (Blueprint $table) {
            $table->unsignedBigInteger('receipt_social_id');
            $table->foreign('receipt_social_id', 'receipt_social_id_fk_8582399')->references('id')->on('receipt_socials')->onDelete('cascade');
            $table->unsignedBigInteger('social_id');
            $table->foreign('social_id', 'social_id_fk_8582399')->references('id')->on('socials')->onDelete('cascade');
        });
    }
}
