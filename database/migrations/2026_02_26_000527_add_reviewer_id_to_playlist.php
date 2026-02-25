<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('receipt_socials', function (Blueprint $table) {
            $table->unsignedBigInteger('reviewer_id')->nullable()->after('manufacturer_id');
            $table->foreign('reviewer_id', 'reviewer_id_fk_8698749')->references('id')->on('users');
        });
        Schema::table('receipt_companies', function (Blueprint $table) {
            $table->unsignedBigInteger('reviewer_id')->nullable()->after('manufacturer_id');
            $table->foreign('reviewer_id', 'reviewer_id_fk_864239')->references('id')->on('users');
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('reviewer_id')->nullable()->after('manufacturer_id');
            $table->foreign('reviewer_id', 'reviewer_id_fk_842339')->references('id')->on('users');
        }); 
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('receipt_socials', function (Blueprint $table) {
            $table->dropForeign(['reviewer_id']);
            $table->dropColumn('reviewer_id');
        });
        Schema::table('receipt_companies', function (Blueprint $table) {
            $table->dropForeign(['reviewer_id']);
            $table->dropColumn('reviewer_id');
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['reviewer_id']);
            $table->dropColumn('reviewer_id');
        });
    }
};
