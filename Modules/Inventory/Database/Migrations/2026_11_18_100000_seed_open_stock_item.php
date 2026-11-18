<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $sku = 'open-stock';
        $existing = DB::table('inv_items')->where('sku', $sku)->first();
        if ($existing) {
            if ($existing->deleted_at !== null) {
                DB::table('inv_items')->where('id', $existing->id)->update(['deleted_at' => null]);
            }

            return;
        }

        $id = DB::table('inv_items')->insertGetId([
            'name' => 'Open stock',
            'sku' => $sku,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('inv_balances')->insert([
            'inv_item_id' => $id,
            'on_hand' => 0,
            'sync_pending' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        $item = DB::table('inv_items')->where('sku', 'open-stock')->first();
        if (! $item) {
            return;
        }

        DB::table('inv_balances')->where('inv_item_id', $item->id)->delete();
        DB::table('inv_items')->where('id', $item->id)->delete();
    }
};
