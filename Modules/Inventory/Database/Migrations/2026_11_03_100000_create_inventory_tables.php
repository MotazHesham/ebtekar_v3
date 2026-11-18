<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('inv_items')) {
            Schema::create('inv_items', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name');
                $table->string('sku')->nullable()->unique();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (! Schema::hasTable('inv_balances')) {
            Schema::create('inv_balances', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('inv_item_id')->unique();
                $table->integer('on_hand')->default(0);
                $table->boolean('sync_pending')->default(false);
                $table->timestamps();

                $table->foreign('inv_item_id')->references('id')->on('inv_items')->cascadeOnDelete();
            });
        }

        if (! Schema::hasTable('inv_movements')) {
            Schema::create('inv_movements', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('inv_item_id');
                $table->integer('quantity_delta');
                $table->integer('on_hand_after');
                $table->string('type', 32);
                $table->string('source_type', 32)->nullable();
                $table->unsignedBigInteger('source_id')->nullable();
                $table->text('note')->nullable();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->timestamps();

                $table->index(['source_type', 'source_id']);
                $table->index('type');
                $table->foreign('inv_item_id')->references('id')->on('inv_items')->cascadeOnDelete();
            });
        }

        if (! Schema::hasTable('inv_social_links')) {
            Schema::create('inv_social_links', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('inv_item_id');
                $table->unsignedBigInteger('receipt_social_product_id')->unique();
                $table->timestamps();

                $table->foreign('inv_item_id')->references('id')->on('inv_items')->cascadeOnDelete();
                $table->foreign('receipt_social_product_id')->references('id')->on('receipt_social_products')->cascadeOnDelete();
            });
        }

        if (! Schema::hasTable('inv_channel_links')) {
            Schema::create('inv_channel_links', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('inv_item_id');
                $table->unsignedBigInteger('website_setting_id');
                $table->string('shopify_variant_id');
                $table->string('shopify_inventory_item_id');
                $table->string('shopify_location_id')->nullable();
                $table->string('product_title')->nullable();
                $table->string('variant_title')->nullable();
                $table->timestamps();

                $table->unique(['website_setting_id', 'shopify_variant_id'], 'inv_channel_variant_unique');
                $table->foreign('inv_item_id')->references('id')->on('inv_items')->cascadeOnDelete();
                $table->foreign('website_setting_id')->references('id')->on('website_settings')->cascadeOnDelete();
            });
        }

        if (! Schema::hasTable('inv_unmapped_events')) {
            Schema::create('inv_unmapped_events', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('website_setting_id')->nullable();
                $table->string('shopify_variant_id')->nullable();
                $table->string('source_type', 32)->nullable();
                $table->unsignedBigInteger('source_id')->nullable();
                $table->unsignedBigInteger('receipt_social_id')->nullable();
                $table->unsignedBigInteger('receipt_social_product_id')->nullable();
                $table->string('product_name')->nullable();
                $table->text('message');
                $table->timestamp('resolved_at')->nullable();
                $table->timestamps();

                $table->unique(
                    ['website_setting_id', 'shopify_variant_id', 'source_type', 'source_id'],
                    'inv_unmapped_source_unique'
                );
                $table->index('resolved_at');
            });
        }

        if (! Schema::hasTable('inv_sync_failures')) {
            Schema::create('inv_sync_failures', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('inv_item_id');
                $table->unsignedBigInteger('inv_channel_link_id')->nullable();
                $table->unsignedBigInteger('website_setting_id')->nullable();
                $table->integer('quantity_sent')->nullable();
                $table->text('error');
                $table->timestamp('resolved_at')->nullable();
                $table->timestamps();

                $table->index(['inv_item_id', 'resolved_at']);
                $table->foreign('inv_item_id')->references('id')->on('inv_items')->cascadeOnDelete();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('inv_sync_failures');
        Schema::dropIfExists('inv_unmapped_events');
        Schema::dropIfExists('inv_channel_links');
        Schema::dropIfExists('inv_social_links');
        Schema::dropIfExists('inv_movements');
        Schema::dropIfExists('inv_balances');
        Schema::dropIfExists('inv_items');
    }
};
