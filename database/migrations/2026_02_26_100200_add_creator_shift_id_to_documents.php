<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCreatorShiftIdToDocuments extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('creator_shift_id')->nullable()->after('social_user_id');
            $table->foreign('creator_shift_id')->references('id')->on('employee_shifts')->onDelete('set null');
        });

        Schema::table('receipt_socials', function (Blueprint $table) {
            $table->unsignedBigInteger('creator_shift_id')->nullable()->after('ad_history_id');
            $table->foreign('creator_shift_id')->references('id')->on('employee_shifts')->onDelete('set null');
        });

        Schema::table('receipt_companies', function (Blueprint $table) {
            $table->unsignedBigInteger('creator_shift_id')->nullable()->after('shopify_order_num');
            $table->foreign('creator_shift_id')->references('id')->on('employee_shifts')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['creator_shift_id']);
            $table->dropColumn('creator_shift_id');
        });

        Schema::table('receipt_socials', function (Blueprint $table) {
            $table->dropForeign(['creator_shift_id']);
            $table->dropColumn('creator_shift_id');
        });

        Schema::table('receipt_companies', function (Blueprint $table) {
            $table->dropForeign(['creator_shift_id']);
            $table->dropColumn('creator_shift_id');
        });
    }
}

