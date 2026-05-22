<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Modules\Shipping\Support\ShippingTables as ST;

return new class extends Migration
{
    public function up(): void
    {
        $orders = $this->resolveTable(ST::DELIVERY_ORDERS);
        if ($orders && ! Schema::hasColumn($orders, 'uuid')) {
            Schema::table($orders, function (Blueprint $table) {
                $table->uuid('uuid')->nullable()->unique()->after('id');
            });
            foreach (\DB::table($orders)->whereNull('uuid')->pluck('id') as $id) {
                \DB::table($orders)->where('id', $id)->update(['uuid' => (string) Str::uuid()]);
            }
        }

        $partners = $this->resolveTable(ST::SHIPPING_PARTNERS);
        if ($partners && ! Schema::hasColumn($partners, 'uuid')) {
            Schema::table($partners, function (Blueprint $table) {
                $table->uuid('uuid')->nullable()->unique()->after('id');
            });
            foreach (\DB::table($partners)->whereNull('uuid')->pluck('id') as $id) {
                \DB::table($partners)->where('id', $id)->update(['uuid' => (string) Str::uuid()]);
            }
        }

        if (Schema::hasTable('deliver_men') && ! Schema::hasColumn('deliver_men', 'uuid')) {
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
        foreach ([ST::DELIVERY_ORDERS, ST::SHIPPING_PARTNERS] as $base) {
            $table = $this->resolveTable($base);
            if ($table && Schema::hasColumn($table, 'uuid')) {
                Schema::table($table, fn (Blueprint $t) => $t->dropColumn('uuid'));
            }
        }

        if (Schema::hasTable('deliver_men') && Schema::hasColumn('deliver_men', 'uuid')) {
            Schema::table('deliver_men', fn (Blueprint $t) => $t->dropColumn('uuid'));
        }
    }

    protected function resolveTable(string $base): ?string
    {
        $prefixed = ST::name($base);

        if (Schema::hasTable($prefixed)) {
            return $prefixed;
        }

        if (Schema::hasTable($base)) {
            return $base;
        }

        return null;
    }
};
