<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            [
                'id'    => 1,
                'title' => 'user_management_access',
            ],
            [
                'id'    => 2,
                'title' => 'permission_create',
            ],
            [
                'id'    => 3,
                'title' => 'permission_edit',
            ],
            [
                'id'    => 4,
                'title' => 'permission_show',
            ],
            [
                'id'    => 5,
                'title' => 'permission_delete',
            ],
            [
                'id'    => 6,
                'title' => 'permission_access',
            ],
            [
                'id'    => 7,
                'title' => 'role_create',
            ],
            [
                'id'    => 8,
                'title' => 'role_edit',
            ],
            [
                'id'    => 9,
                'title' => 'role_show',
            ],
            [
                'id'    => 10,
                'title' => 'role_delete',
            ],
            [
                'id'    => 11,
                'title' => 'role_access',
            ],
            [
                'id'    => 12,
                'title' => 'user_create',
            ],
            [
                'id'    => 13,
                'title' => 'user_edit',
            ],
            [
                'id'    => 14,
                'title' => 'user_show',
            ],
            [
                'id'    => 15,
                'title' => 'user_delete',
            ],
            [
                'id'    => 16,
                'title' => 'user_access',
            ],
            [
                'id'    => 17,
                'title' => 'audit_log_show',
            ],
            [
                'id'    => 18,
                'title' => 'audit_log_access',
            ],
            [
                'id'    => 19,
                'title' => 'task_management_access',
            ],
            [
                'id'    => 20,
                'title' => 'task_status_create',
            ],
            [
                'id'    => 21,
                'title' => 'task_status_edit',
            ],
            [
                'id'    => 22,
                'title' => 'task_status_show',
            ],
            [
                'id'    => 23,
                'title' => 'task_status_delete',
            ],
            [
                'id'    => 24,
                'title' => 'task_status_access',
            ],
            [
                'id'    => 25,
                'title' => 'task_tag_create',
            ],
            [
                'id'    => 26,
                'title' => 'task_tag_edit',
            ],
            [
                'id'    => 27,
                'title' => 'task_tag_show',
            ],
            [
                'id'    => 28,
                'title' => 'task_tag_delete',
            ],
            [
                'id'    => 29,
                'title' => 'task_tag_access',
            ],
            [
                'id'    => 30,
                'title' => 'task_create',
            ],
            [
                'id'    => 31,
                'title' => 'task_edit',
            ],
            [
                'id'    => 32,
                'title' => 'task_show',
            ],
            [
                'id'    => 33,
                'title' => 'task_delete',
            ],
            [
                'id'    => 34,
                'title' => 'task_access',
            ],
            [
                'id'    => 35,
                'title' => 'tasks_calendar_access',
            ],
            [
                'id'    => 36,
                'title' => 'user_alert_create',
            ],
            [
                'id'    => 37,
                'title' => 'user_alert_show',
            ],
            [
                'id'    => 38,
                'title' => 'user_alert_delete',
            ],
            [
                'id'    => 39,
                'title' => 'user_alert_access',
            ],
            [
                'id'    => 40,
                'title' => 'receipts_managment_access',
            ],
            [
                'id'    => 41,
                'title' => 'receipt_social_create',
            ],
            [
                'id'    => 42,
                'title' => 'receipt_social_edit',
            ],
            [
                'id'    => 43,
                'title' => 'receipt_social_show',
            ],
            [
                'id'    => 44,
                'title' => 'receipt_social_delete',
            ],
            [
                'id'    => 45,
                'title' => 'receipt_social_access',
            ],
            [
                'id'    => 46,
                'title' => 'receipt_social_product_create',
            ],
            [
                'id'    => 47,
                'title' => 'receipt_social_product_edit',
            ],
            [
                'id'    => 48,
                'title' => 'receipt_social_product_show',
            ],
            [
                'id'    => 49,
                'title' => 'receipt_social_product_delete',
            ],
            [
                'id'    => 50,
                'title' => 'receipt_social_product_access',
            ],
            [
                'id'    => 51,
                'title' => 'receipt_client_create',
            ],
            [
                'id'    => 52,
                'title' => 'receipt_client_edit',
            ],
            [
                'id'    => 53,
                'title' => 'receipt_client_show',
            ],
            [
                'id'    => 54,
                'title' => 'receipt_client_delete',
            ],
            [
                'id'    => 55,
                'title' => 'receipt_client_access',
            ],
            [
                'id'    => 56,
                'title' => 'receipt_client_product_create',
            ],
            [
                'id'    => 57,
                'title' => 'receipt_client_product_edit',
            ],
            [
                'id'    => 58,
                'title' => 'receipt_client_product_show',
            ],
            [
                'id'    => 59,
                'title' => 'receipt_client_product_delete',
            ],
            [
                'id'    => 60,
                'title' => 'receipt_client_product_access',
            ],
            [
                'id'    => 61,
                'title' => 'receipt_company_create',
            ],
            [
                'id'    => 62,
                'title' => 'receipt_company_edit',
            ],
            [
                'id'    => 63,
                'title' => 'receipt_company_show',
            ],
            [
                'id'    => 64,
                'title' => 'receipt_company_delete',
            ],
            [
                'id'    => 65,
                'title' => 'receipt_company_access',
            ],
            [
                'id'    => 66,
                'title' => 'setting_access',
            ],
            [
                'id'    => 67,
                'title' => 'general_setting_edit',
            ],
            [
                'id'    => 68,
                'title' => 'general_setting_show',
            ],
            [
                'id'    => 69,
                'title' => 'general_setting_access',
            ],
            [
                'id'    => 70,
                'title' => 'customer_create',
            ],
            [
                'id'    => 71,
                'title' => 'customer_edit',
            ],
            [
                'id'    => 72,
                'title' => 'customer_show',
            ],
            [
                'id'    => 73,
                'title' => 'customer_delete',
            ],
            [
                'id'    => 74,
                'title' => 'customer_access',
            ],
            [
                'id'    => 75,
                'title' => 'seller_managment_access',
            ],
            [
                'id'    => 76,
                'title' => 'seller_create',
            ],
            [
                'id'    => 77,
                'title' => 'seller_edit',
            ],
            [
                'id'    => 78,
                'title' => 'seller_show',
            ],
            [
                'id'    => 79,
                'title' => 'seller_delete',
            ],
            [
                'id'    => 80,
                'title' => 'seller_access',
            ],
            [
                'id'    => 81,
                'title' => 'commission_request_create',
            ],
            [
                'id'    => 82,
                'title' => 'commission_request_edit',
            ],
            [
                'id'    => 83,
                'title' => 'commission_request_show',
            ],
            [
                'id'    => 84,
                'title' => 'commission_request_delete',
            ],
            [
                'id'    => 85,
                'title' => 'commission_request_access',
            ],
            [
                'id'    => 86,
                'title' => 'admin_create',
            ],
            [
                'id'    => 87,
                'title' => 'admin_edit',
            ],
            [
                'id'    => 88,
                'title' => 'admin_show',
            ],
            [
                'id'    => 89,
                'title' => 'admin_delete',
            ],
            [
                'id'    => 90,
                'title' => 'admin_access',
            ],
            [
                'id'    => 91,
                'title' => 'borrow_create',
            ],
            [
                'id'    => 92,
                'title' => 'borrow_edit',
            ],
            [
                'id'    => 93,
                'title' => 'borrow_show',
            ],
            [
                'id'    => 94,
                'title' => 'borrow_delete',
            ],
            [
                'id'    => 95,
                'title' => 'borrow_access',
            ],
            [
                'id'    => 96,
                'title' => 'subtraction_create',
            ],
            [
                'id'    => 97,
                'title' => 'subtraction_edit',
            ],
            [
                'id'    => 98,
                'title' => 'subtraction_show',
            ],
            [
                'id'    => 99,
                'title' => 'subtraction_delete',
            ],
            [
                'id'    => 100,
                'title' => 'subtraction_access',
            ],
            [
                'id'    => 101,
                'title' => 'employee_create',
            ],
            [
                'id'    => 102,
                'title' => 'employee_edit',
            ],
            [
                'id'    => 103,
                'title' => 'employee_show',
            ],
            [
                'id'    => 104,
                'title' => 'employee_delete',
            ],
            [
                'id'    => 105,
                'title' => 'employee_access',
            ],
            [
                'id'    => 106,
                'title' => 'country_create',
            ],
            [
                'id'    => 107,
                'title' => 'country_edit',
            ],
            [
                'id'    => 108,
                'title' => 'country_show',
            ],
            [
                'id'    => 109,
                'title' => 'country_delete',
            ],
            [
                'id'    => 110,
                'title' => 'country_access',
            ],
            [
                'id'    => 111,
                'title' => 'social_create',
            ],
            [
                'id'    => 112,
                'title' => 'social_edit',
            ],
            [
                'id'    => 113,
                'title' => 'social_show',
            ],
            [
                'id'    => 114,
                'title' => 'social_delete',
            ],
            [
                'id'    => 115,
                'title' => 'social_access',
            ],
            [
                'id'    => 116,
                'title' => 'banned_phone_create',
            ],
            [
                'id'    => 117,
                'title' => 'banned_phone_edit',
            ],
            [
                'id'    => 118,
                'title' => 'banned_phone_show',
            ],
            [
                'id'    => 119,
                'title' => 'banned_phone_delete',
            ],
            [
                'id'    => 120,
                'title' => 'banned_phone_access',
            ],
            [
                'id'    => 121,
                'title' => 'police_create',
            ],
            [
                'id'    => 122,
                'title' => 'police_edit',
            ],
            [
                'id'    => 123,
                'title' => 'police_show',
            ],
            [
                'id'    => 124,
                'title' => 'police_delete',
            ],
            [
                'id'    => 125,
                'title' => 'police_access',
            ],
            [
                'id'    => 126,
                'title' => 'frontend_setting_access',
            ],
            [
                'id'    => 127,
                'title' => 'slider_create',
            ],
            [
                'id'    => 128,
                'title' => 'slider_edit',
            ],
            [
                'id'    => 129,
                'title' => 'slider_show',
            ],
            [
                'id'    => 130,
                'title' => 'slider_delete',
            ],
            [
                'id'    => 131,
                'title' => 'slider_access',
            ],
            [
                'id'    => 132,
                'title' => 'banner_create',
            ],
            [
                'id'    => 133,
                'title' => 'banner_edit',
            ],
            [
                'id'    => 134,
                'title' => 'banner_show',
            ],
            [
                'id'    => 135,
                'title' => 'banner_delete',
            ],
            [
                'id'    => 136,
                'title' => 'banner_access',
            ],
            [
                'id'    => 137,
                'title' => 'product_managment_access',
            ],
            [
                'id'    => 138,
                'title' => 'category_create',
            ],
            [
                'id'    => 139,
                'title' => 'category_edit',
            ],
            [
                'id'    => 140,
                'title' => 'category_show',
            ],
            [
                'id'    => 141,
                'title' => 'category_delete',
            ],
            [
                'id'    => 142,
                'title' => 'category_access',
            ],
            [
                'id'    => 143,
                'title' => 'home_category_create',
            ],
            [
                'id'    => 144,
                'title' => 'home_category_edit',
            ],
            [
                'id'    => 145,
                'title' => 'home_category_show',
            ],
            [
                'id'    => 146,
                'title' => 'home_category_delete',
            ],
            [
                'id'    => 147,
                'title' => 'home_category_access',
            ],
            [
                'id'    => 148,
                'title' => 'quality_responsible_create',
            ],
            [
                'id'    => 149,
                'title' => 'quality_responsible_edit',
            ],
            [
                'id'    => 150,
                'title' => 'quality_responsible_show',
            ],
            [
                'id'    => 151,
                'title' => 'quality_responsible_delete',
            ],
            [
                'id'    => 152,
                'title' => 'quality_responsible_access',
            ],
            [
                'id'    => 153,
                'title' => 'seo_setting_create',
            ],
            [
                'id'    => 154,
                'title' => 'seo_setting_edit',
            ],
            [
                'id'    => 155,
                'title' => 'seo_setting_show',
            ],
            [
                'id'    => 156,
                'title' => 'seo_setting_delete',
            ],
            [
                'id'    => 157,
                'title' => 'seo_setting_access',
            ],
            [
                'id'    => 158,
                'title' => 'conversation_create',
            ],
            [
                'id'    => 159,
                'title' => 'conversation_edit',
            ],
            [
                'id'    => 160,
                'title' => 'conversation_show',
            ],
            [
                'id'    => 161,
                'title' => 'conversation_delete',
            ],
            [
                'id'    => 162,
                'title' => 'conversation_access',
            ],
            [
                'id'    => 163,
                'title' => 'order_create',
            ],
            [
                'id'    => 164,
                'title' => 'order_edit',
            ],
            [
                'id'    => 165,
                'title' => 'order_show',
            ],
            [
                'id'    => 166,
                'title' => 'order_delete',
            ],
            [
                'id'    => 167,
                'title' => 'order_access',
            ],
            [
                'id'    => 168,
                'title' => 'receipt_outgoing_create',
            ],
            [
                'id'    => 169,
                'title' => 'receipt_outgoing_edit',
            ],
            [
                'id'    => 170,
                'title' => 'receipt_outgoing_show',
            ],
            [
                'id'    => 171,
                'title' => 'receipt_outgoing_delete',
            ],
            [
                'id'    => 172,
                'title' => 'receipt_outgoing_access',
            ],
            [
                'id'    => 173,
                'title' => 'receipt_outgoing_product_create',
            ],
            [
                'id'    => 174,
                'title' => 'receipt_outgoing_product_edit',
            ],
            [
                'id'    => 175,
                'title' => 'receipt_outgoing_product_show',
            ],
            [
                'id'    => 176,
                'title' => 'receipt_outgoing_product_delete',
            ],
            [
                'id'    => 177,
                'title' => 'receipt_outgoing_product_access',
            ],
            [
                'id'    => 178,
                'title' => 'receipt_price_view_create',
            ],
            [
                'id'    => 179,
                'title' => 'receipt_price_view_edit',
            ],
            [
                'id'    => 180,
                'title' => 'receipt_price_view_show',
            ],
            [
                'id'    => 181,
                'title' => 'receipt_price_view_delete',
            ],
            [
                'id'    => 182,
                'title' => 'receipt_price_view_access',
            ],
            [
                'id'    => 183,
                'title' => 'receipt_price_view_product_create',
            ],
            [
                'id'    => 184,
                'title' => 'receipt_price_view_product_edit',
            ],
            [
                'id'    => 185,
                'title' => 'receipt_price_view_product_show',
            ],
            [
                'id'    => 186,
                'title' => 'receipt_price_view_product_delete',
            ],
            [
                'id'    => 187,
                'title' => 'receipt_price_view_product_access',
            ],
            [
                'id'    => 188,
                'title' => 'excel_file_create',
            ],
            [
                'id'    => 189,
                'title' => 'excel_file_edit',
            ],
            [
                'id'    => 190,
                'title' => 'excel_file_show',
            ],
            [
                'id'    => 191,
                'title' => 'excel_file_delete',
            ],
            [
                'id'    => 192,
                'title' => 'excel_file_access',
            ],
            [
                'id'    => 193,
                'title' => 'product_create',
            ],
            [
                'id'    => 194,
                'title' => 'product_edit',
            ],
            [
                'id'    => 195,
                'title' => 'product_show',
            ],
            [
                'id'    => 196,
                'title' => 'product_delete',
            ],
            [
                'id'    => 197,
                'title' => 'product_access',
            ],
            [
                'id'    => 198,
                'title' => 'sub_category_create',
            ],
            [
                'id'    => 199,
                'title' => 'sub_category_edit',
            ],
            [
                'id'    => 200,
                'title' => 'sub_category_show',
            ],
            [
                'id'    => 201,
                'title' => 'sub_category_delete',
            ],
            [
                'id'    => 202,
                'title' => 'sub_category_access',
            ],
            [
                'id'    => 203,
                'title' => 'sub_sub_category_create',
            ],
            [
                'id'    => 204,
                'title' => 'sub_sub_category_edit',
            ],
            [
                'id'    => 205,
                'title' => 'sub_sub_category_show',
            ],
            [
                'id'    => 206,
                'title' => 'sub_sub_category_delete',
            ],
            [
                'id'    => 207,
                'title' => 'sub_sub_category_access',
            ],
            [
                'id'    => 208,
                'title' => 'attribute_create',
            ],
            [
                'id'    => 209,
                'title' => 'attribute_edit',
            ],
            [
                'id'    => 210,
                'title' => 'attribute_show',
            ],
            [
                'id'    => 211,
                'title' => 'attribute_delete',
            ],
            [
                'id'    => 212,
                'title' => 'attribute_access',
            ],
            [
                'id'    => 213,
                'title' => 'review_access',
            ],
            [
                'id'    => 214,
                'title' => 'playlist_create',
            ],
            [
                'id'    => 215,
                'title' => 'playlist_edit',
            ],
            [
                'id'    => 216,
                'title' => 'playlist_show',
            ],
            [
                'id'    => 217,
                'title' => 'playlist_delete',
            ],
            [
                'id'    => 218,
                'title' => 'playlist_access',
            ],
            [
                'id'    => 219,
                'title' => 'borrows_and_subtraction_access',
            ],
            [
                'id'    => 220,
                'title' => 'faq_management_access',
            ],
            [
                'id'    => 221,
                'title' => 'faq_category_create',
            ],
            [
                'id'    => 222,
                'title' => 'faq_category_edit',
            ],
            [
                'id'    => 223,
                'title' => 'faq_category_show',
            ],
            [
                'id'    => 224,
                'title' => 'faq_category_delete',
            ],
            [
                'id'    => 225,
                'title' => 'faq_category_access',
            ],
            [
                'id'    => 226,
                'title' => 'faq_question_create',
            ],
            [
                'id'    => 227,
                'title' => 'faq_question_edit',
            ],
            [
                'id'    => 228,
                'title' => 'faq_question_show',
            ],
            [
                'id'    => 229,
                'title' => 'faq_question_delete',
            ],
            [
                'id'    => 230,
                'title' => 'faq_question_access',
            ],
            [
                'id'    => 231,
                'title' => 'mockups_managment_access',
            ],
            [
                'id'    => 232,
                'title' => 'mockup_create',
            ],
            [
                'id'    => 233,
                'title' => 'mockup_edit',
            ],
            [
                'id'    => 234,
                'title' => 'mockup_show',
            ],
            [
                'id'    => 235,
                'title' => 'mockup_delete',
            ],
            [
                'id'    => 236,
                'title' => 'mockup_access',
            ],
            [
                'id'    => 237,
                'title' => 'designer_create',
            ],
            [
                'id'    => 238,
                'title' => 'designer_edit',
            ],
            [
                'id'    => 239,
                'title' => 'designer_show',
            ],
            [
                'id'    => 240,
                'title' => 'designer_delete',
            ],
            [
                'id'    => 241,
                'title' => 'designer_access',
            ],
            [
                'id'    => 242,
                'title' => 'designe_create',
            ],
            [
                'id'    => 243,
                'title' => 'designe_edit',
            ],
            [
                'id'    => 244,
                'title' => 'designe_show',
            ],
            [
                'id'    => 245,
                'title' => 'designe_delete',
            ],
            [
                'id'    => 246,
                'title' => 'designe_access',
            ],
            [
                'id'    => 247,
                'title' => 'delivery_order_create',
            ],
            [
                'id'    => 248,
                'title' => 'delivery_order_edit',
            ],
            [
                'id'    => 249,
                'title' => 'delivery_order_show',
            ],
            [
                'id'    => 250,
                'title' => 'delivery_order_delete',
            ],
            [
                'id'    => 251,
                'title' => 'delivery_order_access',
            ],
            [
                'id'    => 252,
                'title' => 'delivery_managment_access',
            ],
            [
                'id'    => 253,
                'title' => 'deliver_man_create',
            ],
            [
                'id'    => 254,
                'title' => 'deliver_man_edit',
            ],
            [
                'id'    => 255,
                'title' => 'deliver_man_show',
            ],
            [
                'id'    => 256,
                'title' => 'deliver_man_delete',
            ],
            [
                'id'    => 257,
                'title' => 'deliver_man_access',
            ],
            [
                'id'    => 258,
                'title' => 'profile_password_edit',
            ],
        ];

        Permission::insert($permissions);
    }
}
