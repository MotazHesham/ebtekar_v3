<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\Shipping\Support\ShippingTables as ST;

return new class extends Migration
{
    public function up(): void
    {
        $table = ST::name(ST::DISPATCH_BATCH_ITEMS);

        if (! Schema::hasTable($table) || ! Schema::hasColumn($table, 'message')) {
            return;
        }

        DB::statement("ALTER TABLE `{$table}` MODIFY `message` TEXT NULL");
    }

    public function down(): void
    {
        $table = ST::name(ST::DISPATCH_BATCH_ITEMS);

        if (! Schema::hasTable($table) || ! Schema::hasColumn($table, 'message')) {
            return;
        }

        DB::statement("ALTER TABLE `{$table}` MODIFY `message` VARCHAR(255) NULL");
    }
};
