<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('delivery_orders', 'uuid')) {
            Schema::table('delivery_orders', function (Blueprint $table) {
                $table->uuid('uuid')->nullable()->unique()->after('id');
            });
            foreach (\DB::table('delivery_orders')->whereNull('uuid')->pluck('id') as $id) {
                \DB::table('delivery_orders')->where('id', $id)->update(['uuid' => (string) Str::uuid()]);
            }
        }

        if (! Schema::hasColumn('shipping_partners', 'uuid')) {
            Schema::table('shipping_partners', function (Blueprint $table) {
                $table->uuid('uuid')->nullable()->unique()->after('id');
            });
            foreach (\DB::table('shipping_partners')->whereNull('uuid')->pluck('id') as $id) {
                \DB::table('shipping_partners')->where('id', $id)->update(['uuid' => (string) Str::uuid()]);
            }
        }

        if (! Schema::hasColumn('deliver_men', 'uuid')) {
            Schema::table('deliver_men', function (Blueprint $table) {
                $table->uuid('uuid')->nullable()->unique()->after('id');
            });
            foreach (\DB::table('deliver_men')->whereNull('uuid')->pluck('id') as $id) {
                \DB::table('deliver_men')->where('id', $id)->update(['uuid' => (string) Str::uuid()]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('delivery_orders', fn (Blueprint $t) => $t->dropColumn('uuid'));
        Schema::table('shipping_partners', fn (Blueprint $t) => $t->dropColumn('uuid'));
        Schema::table('deliver_men', fn (Blueprint $t) => $t->dropColumn('uuid'));
    }
};
