<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Shipping\Support\ShippingTables as ST;

return new class extends Migration
{
    public function up(): void
    {
        $partners = ST::name(ST::SHIPPING_PARTNERS);
        $partnersTable = Schema::hasTable($partners)
            ? $partners
            : (Schema::hasTable(ST::SHIPPING_PARTNERS) ? ST::SHIPPING_PARTNERS : null);

        Schema::table('deliver_men', function (Blueprint $table) use ($partnersTable) {
            if (! Schema::hasColumn('deliver_men', 'uuid')) {
                $table->uuid('uuid')->nullable()->unique()->after('id');
            }
            if (! Schema::hasColumn('deliver_men', 'shipping_partner_id')) {
                $table->unsignedBigInteger('shipping_partner_id')->nullable()->after('user_id');
                if ($partnersTable) {
                    $table->foreign('shipping_partner_id')->references('id')->on($partnersTable)->onDelete('set null');
                }
            }
            if (! Schema::hasColumn('deliver_men', 'status')) {
                $table->string('status')->default('active')->after('user_id');
            }
            if (! Schema::hasColumn('deliver_men', 'internal_notes')) {
                $table->text('internal_notes')->nullable();
            }
            if (! Schema::hasColumn('deliver_men', 'capacity_max')) {
                $table->unsignedInteger('capacity_max')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('deliver_men', function (Blueprint $table) {
            if (Schema::hasColumn('deliver_men', 'shipping_partner_id')) {
                $table->dropForeign(['shipping_partner_id']);
            }
            $cols = array_filter([
                Schema::hasColumn('deliver_men', 'shipping_partner_id') ? 'shipping_partner_id' : null,
                Schema::hasColumn('deliver_men', 'status') ? 'status' : null,
                Schema::hasColumn('deliver_men', 'internal_notes') ? 'internal_notes' : null,
                Schema::hasColumn('deliver_men', 'capacity_max') ? 'capacity_max' : null,
                Schema::hasColumn('deliver_men', 'uuid') ? 'uuid' : null,
            ]);
            if ($cols) {
                $table->dropColumn($cols);
            }
        });
    }
};
