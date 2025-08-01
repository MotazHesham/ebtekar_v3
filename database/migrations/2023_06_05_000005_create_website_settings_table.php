<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('website_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('css_file_name')->nullable();
            $table->string('site_name');
            $table->string('url')->nullable();
            $table->string('domains');
            $table->longText('keywords_seo');
            $table->string('author_seo');
            $table->string('sitemap_link_seo');
            $table->longText('description_seo');
            $table->longText('address')->nullable();
            $table->longText('description')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('email')->nullable();
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->string('twitter')->nullable();
            $table->string('telegram')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('youtube')->nullable();
            $table->string('google_plus')->nullable();
            $table->longText('welcome_message')->nullable();
            $table->string('video_instructions')->nullable();
            $table->string('delivery_system')->nullable();
            $table->string('employee_password')->nullable();
            $table->string('tag_manager')->nullable();
            $table->string('google_analytics')->nullable();
            $table->string('fb_pixel_id')->nullable();
            $table->string('fb_access_token',2000)->nullable();
            $table->string('fb_test_code')->nullable();
            
            $table->string('order_num_prefix')->nullable(); 
            $table->string('playlist_status')->nullable(); 
            $table->string('shopify_api_key')->nullable(); 
            $table->string('shopify_access_token')->nullable();
            $table->string('shopify_domain')->nullable();
            $table->string('shopify_api_secret')->nullable();
            $table->string('shopify_webhook_sign')->nullable();
            $table->boolean('shopify_integration_status')->default(0);
            $table->unsignedBigInteger('designer_id')->nullable();
            $table->foreign('designer_id', 'designer_fk_8581724')->references('id')->on('users');
            $table->unsignedBigInteger('preparer_id')->nullable();
            $table->foreign('preparer_id', 'preparer_fk_8581725')->references('id')->on('users');
            $table->unsignedBigInteger('manufacturer_id')->nullable();
            $table->foreign('manufacturer_id', 'manufacturer_fk_8581726')->references('id')->on('users');
            $table->unsignedBigInteger('shipmenter_id')->nullable();
            $table->foreign('shipmenter_id', 'shipmenter_fk_8581727')->references('id')->on('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('website_settings');
    }
};
