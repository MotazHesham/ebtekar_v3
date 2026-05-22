<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Shipping\Support\ShippingTables as ST;

return new class extends Migration
{
    public function up(): void
    {
        $table = ST::name(ST::SHIPPING_PARTNERS);

        if (Schema::hasTable($table) || Schema::hasTable(ST::SHIPPING_PARTNERS)) {
            return;
        }

        Schema::create($table, function (Blueprint $blueprint) {
            $blueprint->bigIncrements('id');
            $blueprint->uuid('uuid')->nullable()->unique();
            $blueprint->string('name');
            $blueprint->string('code')->nullable()->unique();
            $blueprint->unsignedBigInteger('user_id')->nullable();
            $blueprint->string('phone')->nullable();
            $blueprint->text('address')->nullable();
            $blueprint->boolean('is_active')->default(true);
            $blueprint->json('settings')->nullable();
            $blueprint->text('internal_notes')->nullable();
            $blueprint->timestamps();
            $blueprint->softDeletes();

            $blueprint->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(ST::name(ST::SHIPPING_PARTNERS));
        Schema::dropIfExists(ST::SHIPPING_PARTNERS);
    }
};
