<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('website_settings', function (Blueprint $table) {
            $table->unsignedInteger('marketer_attribution_window_days')->nullable()->after('shipping_integration');
            $table->string('marketer_attribution_policy')->default('first_click')->after('marketer_attribution_window_days');
            $table->boolean('marketer_lock_on_first_order')->default(true)->after('marketer_attribution_policy');
        });
    }

    public function down(): void
    {
        Schema::table('website_settings', function (Blueprint $table) {
            $table->dropColumn([
                'marketer_attribution_window_days',
                'marketer_attribution_policy',
                'marketer_lock_on_first_order',
            ]);
        });
    }
};
