<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('receipt_socials') && ! Schema::hasColumn('receipt_socials', 'inventory_reversed')) {
            Schema::table('receipt_socials', function (Blueprint $table) {
                $table->boolean('inventory_reversed')->default(false)->after('returned');
            });

            DB::table('receipt_socials')->where('returned', 1)->update(['inventory_reversed' => 1]);
        }

        $this->addStockColumns('receipt_social_receipt_social_product');
        $this->addStockColumns('receipt_social_box_details');

        if (Schema::hasTable('website_settings') && ! Schema::hasColumn('website_settings', 'shopify_location_id')) {
            Schema::table('website_settings', function (Blueprint $table) {
                $table->string('shopify_location_id')->nullable()->after('shopify_domain');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('website_settings', 'shopify_location_id')) {
            Schema::table('website_settings', function (Blueprint $table) {
                $table->dropColumn('shopify_location_id');
            });
        }

        $this->dropStockColumns('receipt_social_box_details');
        $this->dropStockColumns('receipt_social_receipt_social_product');

        if (Schema::hasColumn('receipt_socials', 'inventory_reversed')) {
            Schema::table('receipt_socials', function (Blueprint $table) {
                $table->dropColumn('inventory_reversed');
            });
        }
    }

    private function addStockColumns(string $tableName): void
    {
        if (! Schema::hasTable($tableName)) {
            return;
        }

        Schema::table($tableName, function (Blueprint $table) use ($tableName) {
            if (! Schema::hasColumn($tableName, 'stock_applied_qty')) {
                $table->integer('stock_applied_qty')->default(0);
            }
            if (! Schema::hasColumn($tableName, 'stock_baseline_qty')) {
                $table->integer('stock_baseline_qty')->default(0);
            }
        });

        if (Schema::hasColumn($tableName, 'quantity')) {
            DB::table($tableName)->update([
                'stock_applied_qty' => DB::raw('COALESCE(quantity, 0)'),
                'stock_baseline_qty' => DB::raw('COALESCE(quantity, 0)'),
            ]);
        }
    }

    private function dropStockColumns(string $tableName): void
    {
        if (! Schema::hasTable($tableName)) {
            return;
        }

        Schema::table($tableName, function (Blueprint $table) use ($tableName) {
            $columns = [];
            if (Schema::hasColumn($tableName, 'stock_applied_qty')) {
                $columns[] = 'stock_applied_qty';
            }
            if (Schema::hasColumn($tableName, 'stock_baseline_qty')) {
                $columns[] = 'stock_baseline_qty';
            }
            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });
    }
};
