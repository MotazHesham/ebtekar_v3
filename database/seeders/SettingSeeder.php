<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [];

        // General
        $settings[] = [
            'key' => 'site_name',
            'value' => 'Invastro',
            'data_type' => 'string',
            'options' => null,
            'group_name' => 'general',
            'order_level' => 1,
            'grid_col' => 6,
            'lang' => null,
        ];
        $settings[] = [
            'key' => 'contact_email',
            'value' => 'support@invastro.com',
            'data_type' => 'string',
            'options' => null,
            'lang' => null,
            'group_name' => 'general',
            'order_level' => 2,
            'grid_col' => 6,
        ];
        $settings[] = [
            'key' => 'contact_phone',
            'value' => '+961 70 000 000',
            'data_type' => 'string',
            'options' => null,
            'lang' => null,
            'group_name' => 'general',
            'order_level' => 3,
            'grid_col' => 4,
        ];
        $settings[] = [
            'key' => 'about_us',
            'value' => 'عن الشركة',
            'data_type' => 'textarea',
            'options' => null,
            'lang' => 'ar',
            'group_name' => 'general',
            'order_level' => 7,
            'grid_col' => 12,
        ];
        $settings[] = [
            'key' => 'about_us',
            'value' => 'About Us',
            'data_type' => 'textarea',
            'options' => null,
            'lang' => 'en',
            'group_name' => 'general',
            'order_level' => 7,
            'grid_col' => 12,
        ];
        $settings[] = [
            'key' => 'terms_and_conditions',
            'value' => 'Terms And Conditions',
            'data_type' => 'textarea',
            'options' => null,
            'lang' => 'en',
            'group_name' => 'general',
            'order_level' => 8,
            'grid_col' => 12,
        ];
        $settings[] = [
            'key' => 'terms_and_conditions',
            'value' => 'الشروط والأحكام',
            'data_type' => 'textarea',
            'options' => null,
            'lang' => 'ar',
            'group_name' => 'general',
            'order_level' => 8,
            'grid_col' => 12,
        ];
        $settings[] = [
            'key' => 'app_logo',
            'value' => null,
            'data_type' => 'file',
            'options' => null,
            'lang' => null,
            'group_name' => 'general',
            'order_level' => 9,
            'grid_col' => 6,
        ];

        // Social Media
        $settings[] = [
            'key' => 'facebook',
            'value' => 'https://www.facebook.com/ebtekar',
            'data_type' => 'string',
            'options' => null,
            'lang' => null,
            'group_name' => 'social_media',
            'order_level' => 1,
            'grid_col' => 4,
        ];
        $settings[] = [
            'key' => 'instagram',
            'value' => 'https://www.instagram.com/ebtekar',
            'data_type' => 'string',
            'options' => null,
            'lang' => null,
            'group_name' => 'social_media',
            'order_level' => 2,
            'grid_col' => 4,
        ];
        $settings[] = [
            'key' => 'twitter',
            'value' => 'https://www.twitter.com/ebtekar',
            'data_type' => 'string',
            'options' => null,
            'lang' => null,
            'group_name' => 'social_media',
            'order_level' => 3,
            'grid_col' => 4,
        ];
        $settings[] = [
            'key' => 'youtube',
            'value' => 'https://www.youtube.com/ebtekar',
            'data_type' => 'string',
            'options' => null,
            'lang' => null,
            'group_name' => 'social_media',
            'order_level' => 4,
            'grid_col' => 4,
        ];
        $settings[] = [
            'key' => 'tiktok',
            'value' => 'https://www.tiktok.com/ebtekar',
            'data_type' => 'string',
            'options' => null,
            'lang' => null,
            'group_name' => 'social_media',
            'order_level' => 5,
            'grid_col' => 4,
        ];
        $settings[] = [
            'key' => 'linkedin',
            'value' => 'https://www.linkedin.com/ebtekar',
            'data_type' => 'string',
            'options' => null,
            'lang' => null,
            'group_name' => 'social_media',
            'order_level' => 6,
            'grid_col' => 4,
        ];
        $settings[] = [
            'key' => 'pinterest',
            'value' => 'https://www.pinterest.com/ebtekar',
            'data_type' => 'string',
            'options' => null,
            'lang' => null,
            'group_name' => 'social_media',
            'order_level' => 7,
            'grid_col' => 4,
        ];
        $settings[] = [
            'key' => 'telegram',
            'value' => 'https://www.telegram.com/ebtekar',
            'data_type' => 'string',
            'options' => null,
            'lang' => null,
            'group_name' => 'social_media',
            'order_level' => 9,
            'grid_col' => 4,
        ];
        $settings[] = [
            'key' => 'snapchat',
            'value' => 'https://www.snapchat.com/ebtekar',
            'data_type' => 'string',
            'options' => null,
            'lang' => null,
            'group_name' => 'social_media',
            'order_level' => 10,
            'grid_col' => 4,
        ];
        $settings[] = [
            'key' => 'whatsapp',
            'value' => 'https://www.whatsapp.com/ebtekar',
            'data_type' => 'string',
            'options' => null,
            'lang' => null,
            'group_name' => 'social_media',
            'order_level' => 11,
            'grid_col' => 4,
        ];

        // Policies
        $settings[] = [
            'key' => 'privacy_policy',
            'value' => 'Privacy Policy',
            'data_type' => 'textarea',
            'options' => null,
            'lang' => 'en',
            'group_name' => 'policies',
        ];
        $settings[] = [
            'key' => 'privacy_policy',
            'value' => 'سياسة الخصوصية',
            'data_type' => 'textarea',
            'options' => null,
            'lang' => 'ar',
            'group_name' => 'policies',
        ];
        $settings[] = [
            'key' => 'refund_policy',
            'value' => 'Refund Policy',
            'data_type' => 'textarea',
            'options' => null,
            'lang' => 'en',
            'group_name' => 'policies',
        ];
        $settings[] = [
            'key' => 'refund_policy',
            'value' => 'سياسة الاسترجاع',
            'data_type' => 'textarea',
            'options' => null,
            'lang' => 'ar',
            'group_name' => 'policies',
        ];
        $settings[] = [
            'key' => 'payment_policy',
            'value' => 'Payment Policy',
            'data_type' => 'textarea',
            'options' => null,
            'lang' => 'en',
            'group_name' => 'policies',
        ];
        $settings[] = [
            'key' => 'payment_policy',
            'value' => 'سياسة الدفع',
            'data_type' => 'textarea',
            'options' => null,
            'lang' => 'ar',
            'group_name' => 'policies',
        ];
        $settings[] = [
            'key' => 'delivery_policy',
            'value' => 'Delivery Policy',
            'data_type' => 'textarea',
            'options' => null,
            'lang' => 'en',
            'group_name' => 'policies',
        ];
        $settings[] = [
            'key' => 'delivery_policy',
            'value' => 'سياسة التوصيل',
            'data_type' => 'textarea',
            'options' => null,
            'lang' => 'ar',
            'group_name' => 'policies',
        ];

        $productionDefaults = app()->environment('production');

        // Features
        $settings[] = [
            'key' => 'sms_enabled',
            'value' => $productionDefaults ? '1' : '0',
            'data_type' => 'select',
            'options' => json_encode(['0', '1']),
            'group_name' => 'features',
            'order_level' => 1,
            'grid_col' => 6,
            'lang' => null,
        ];
        $settings[] = [
            'key' => 'push_notifications_enabled',
            'value' => $productionDefaults ? '1' : '0',
            'data_type' => 'select',
            'options' => json_encode(['0', '1']),
            'group_name' => 'features',
            'order_level' => 2,
            'grid_col' => 6,
            'lang' => null,
        ];
        $settings[] = [
            'key' => 'otp_expiry_minutes',
            'value' => '5',
            'data_type' => 'number',
            'options' => null,
            'group_name' => 'features',
            'order_level' => 3,
            'grid_col' => 4,
            'lang' => null,
        ];
        $settings[] = [
            'key' => 'otp_use_fixed_code',
            'value' => '0',
            'data_type' => 'select',
            'options' => json_encode(['0', '1']),
            'group_name' => 'features',
            'order_level' => 4,
            'grid_col' => 4,
            'lang' => null,
        ];
        $settings[] = [
            'key' => 'otp_fixed_code',
            'value' => '1111',
            'data_type' => 'string',
            'options' => null,
            'group_name' => 'features',
            'order_level' => 5,
            'grid_col' => 4,
            'lang' => null,
        ];
        $settings[] = [
            'key' => 'calendar_default_reminder_days',
            'value' => '7',
            'data_type' => 'number',
            'options' => null,
            'group_name' => 'features',
            'order_level' => 6,
            'grid_col' => 4,
            'lang' => null,
        ];

        foreach ($settings as $setting) {
            if (!Setting::where('key', $setting['key'])->where('lang', $setting['lang'])->exists()) {
                Setting::create($setting);
            }
        }
    }
}
