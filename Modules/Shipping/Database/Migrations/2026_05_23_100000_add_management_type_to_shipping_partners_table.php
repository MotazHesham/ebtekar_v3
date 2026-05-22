<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Shipping\Enums\ShippingPartnerManagementType;
use Modules\Shipping\Support\ShippingTables as ST;

return new class extends Migration
{
    public function up(): void
    {
        $table = $this->resolveTable(ST::SHIPPING_PARTNERS);

        if (! $table || Schema::hasColumn($table, 'management_type')) {
            return;
        }

        Schema::table($table, function (Blueprint $blueprint) {
            $blueprint->string('management_type', 32)
                ->default(ShippingPartnerManagementType::Partner->value)
                ->after('is_active');
        });
    }

    public function down(): void
    {
        $table = $this->resolveTable(ST::SHIPPING_PARTNERS);

        if ($table && Schema::hasColumn($table, 'management_type')) {
            Schema::table($table, fn (Blueprint $blueprint) => $blueprint->dropColumn('management_type'));
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
