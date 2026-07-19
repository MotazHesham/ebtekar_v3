<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('calendar_dates', function (Blueprint $table) {
            $table->unsignedSmallInteger('reminder_sent_for_year')->nullable()->after('reminder_days_before');
        });
    }

    public function down(): void
    {
        Schema::table('calendar_dates', function (Blueprint $table) {
            $table->dropColumn('reminder_sent_for_year');
        });
    }
};
