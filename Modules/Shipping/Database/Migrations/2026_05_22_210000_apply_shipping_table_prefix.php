<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Modules\Shipping\Support\ShippingTables as ST;

return new class extends Migration
{
    public function up(): void
    {
        $prefix = ST::prefix();

        if ($prefix === '') {
            return;
        }

        foreach (ST::PREFIXED as $base) {
            $old = $base;
            $new = ST::name($base);

            if ($old === $new) {
                continue;
            }

            if (Schema::hasTable($old) && ! Schema::hasTable($new)) {
                Schema::rename($old, $new);
            }
        }
    }

    public function down(): void
    {
        $prefix = ST::prefix();

        if ($prefix === '') {
            return;
        }

        foreach (array_reverse(ST::PREFIXED) as $base) {
            $old = $base;
            $new = ST::name($base);

            if ($old === $new) {
                continue;
            }

            if (Schema::hasTable($new) && ! Schema::hasTable($old)) {
                Schema::rename($new, $old);
            }
        }
    }
};
