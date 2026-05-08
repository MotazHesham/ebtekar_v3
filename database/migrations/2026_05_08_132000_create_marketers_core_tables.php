<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marketers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('website_setting_id')->nullable();
            $table->string('name');
            $table->string('code')->unique();
            $table->decimal('commission_rate', 8, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('website_setting_id')->references('id')->on('website_settings')->nullOnDelete();
            $table->index(['website_setting_id', 'is_active']);
        });

        Schema::create('referral_visits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('marketer_id');
            $table->unsignedBigInteger('website_setting_id')->nullable();
            $table->string('ref_code');
            $table->string('cookie_id')->nullable();
            $table->string('session_id')->nullable();
            $table->string('ip', 45)->nullable();
            $table->string('device')->nullable();
            $table->string('browser')->nullable();
            $table->text('user_agent')->nullable();
            $table->text('landing_url')->nullable();
            $table->string('utm_source')->nullable();
            $table->string('utm_campaign')->nullable();
            $table->timestamp('first_seen_at')->nullable();
            $table->timestamp('last_seen_at')->nullable();
            $table->timestamps();

            $table->foreign('marketer_id')->references('id')->on('marketers')->cascadeOnDelete();
            $table->foreign('website_setting_id')->references('id')->on('website_settings')->nullOnDelete();
            $table->index(['marketer_id', 'created_at']);
            $table->index(['cookie_id', 'session_id']);
        });

        Schema::create('customer_marketer_attributions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('marketer_id');
            $table->unsignedBigInteger('website_setting_id')->nullable();
            $table->string('customer_identifier');
            $table->string('source')->default('link');
            $table->string('priority_rule_snapshot')->default('first_click');
            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->boolean('is_locked')->default(false);
            $table->timestamps();

            $table->foreign('marketer_id')->references('id')->on('marketers')->cascadeOnDelete();
            $table->foreign('website_setting_id')->references('id')->on('website_settings')->nullOnDelete();
            $table->index(['customer_identifier', 'website_setting_id'], 'cma_customer_website_idx');
            $table->index(['expires_at']);
        });

        Schema::create('order_marketer_attributions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->unique();
            $table->unsignedBigInteger('marketer_id');
            $table->unsignedBigInteger('customer_marketer_attribution_id')->nullable();
            $table->string('source')->default('link');
            $table->string('commission_status')->default('pending');
            $table->decimal('commission_base', 12, 2)->default(0);
            $table->decimal('commission_rate', 8, 2)->default(0);
            $table->decimal('commission_amount', 12, 2)->default(0);
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('credited_at')->nullable();
            $table->string('rejected_reason')->nullable();
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete();
            $table->foreign('marketer_id')->references('id')->on('marketers')->cascadeOnDelete();
            $table->foreign('customer_marketer_attribution_id', 'oma_attr_fk')
                ->references('id')->on('customer_marketer_attributions')->nullOnDelete();
            $table->index(['marketer_id', 'commission_status']);
        });

        Schema::create('marketer_wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('marketer_id');
            $table->string('type');
            $table->decimal('amount', 12, 2);
            $table->decimal('balance_after', 12, 2)->default(0);
            $table->string('reference_type')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->foreign('marketer_id')->references('id')->on('marketers')->cascadeOnDelete();
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
            $table->index(['marketer_id', 'created_at']);
            $table->index(['reference_type', 'reference_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marketer_wallet_transactions');
        Schema::dropIfExists('order_marketer_attributions');
        Schema::dropIfExists('customer_marketer_attributions');
        Schema::dropIfExists('referral_visits');
        Schema::dropIfExists('marketers');
    }
};
