<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        $i = 1;
        $permissions = [
            [
                'id'    => $i++,
                'title' => 'user_management_access',
            ],
            [
                'id'    => $i++,
                'title' => 'permission_create',
            ],
            [
                'id'    => $i++,
                'title' => 'permission_edit',
            ],
            [
                'id'    => $i++,
                'title' => 'permission_show',
            ],
            [
                'id'    => $i++,
                'title' => 'permission_delete',
            ],
            [
                'id'    => $i++,
                'title' => 'permission_access',
            ],
            [
                'id'    => $i++,
                'title' => 'role_create',
            ],
            [
                'id'    => $i++,
                'title' => 'role_edit',
            ],
            [
                'id'    => $i++,
                'title' => 'role_show',
            ],
            [
                'id'    => $i++,
                'title' => 'role_delete',
            ],
            [
                'id'    => $i++,
                'title' => 'role_access',
            ],
            [
                'id'    => $i++,
                'title' => 'user_create',
            ],
            [
                'id'    => $i++,
                'title' => 'user_edit',
            ],
            [
                'id'    => $i++,
                'title' => 'user_show',
            ],
            [
                'id'    => $i++,
                'title' => 'user_delete',
            ],
            [
                'id'    => $i++,
                'title' => 'user_access',
            ],
            [
                'id'    => $i++,
                'title' => 'audit_log_show',
            ],
            [
                'id'    => $i++,
                'title' => 'audit_log_access',
            ],
            [
                'id'    => $i++,
                'title' => 'task_management_access',
            ],
            [
                'id'    => $i++,
                'title' => 'task_status_create',
            ],
            [
                'id'    => $i++,
                'title' => 'task_status_edit',
            ],
            [
                'id'    => $i++,
                'title' => 'task_status_show',
            ],
            [
                'id'    => $i++,
                'title' => 'task_status_delete',
            ],
            [
                'id'    => $i++,
                'title' => 'task_status_access',
            ],
            [
                'id'    => $i++,
                'title' => 'task_tag_create',
            ],
            [
                'id'    => $i++,
                'title' => 'task_tag_edit',
            ],
            [
                'id'    => $i++,
                'title' => 'task_tag_show',
            ],
            [
                'id'    => $i++,
                'title' => 'task_tag_delete',
            ],
            [
                'id'    => $i++,
                'title' => 'task_tag_access',
            ],
            [
                'id'    => $i++,
                'title' => 'task_create',
            ],
            [
                'id'    => $i++,
                'title' => 'task_edit',
            ],
            [
                'id'    => $i++,
                'title' => 'task_show',
            ],
            [
                'id'    => $i++,
                'title' => 'task_delete',
            ],
            [
                'id'    => $i++,
                'title' => 'task_access',
            ],
            [
                'id'    => $i++,
                'title' => 'tasks_calendar_access',
            ],
            [
                'id'    => $i++,
                'title' => 'user_alert_create',
            ],
            [
                'id'    => $i++,
                'title' => 'user_alert_show',
            ],
            [
                'id'    => $i++,
                'title' => 'user_alert_delete',
            ],
            [
                'id'    => $i++,
                'title' => 'user_alert_access',
            ],
            [
                'id'    => $i++,
                'title' => 'receipts_managment_access',
            ],
            [
                'id'    => $i++,
                'title' => 'receipt_social_create',
            ],
            [
                'id'    => $i++,
                'title' => 'receipt_social_edit',
            ],
            [
                'id'    => $i++,
                'title' => 'receipt_social_show',
            ],
            [
                'id'    => $i++,
                'title' => 'receipt_social_delete',
            ],
            [
                'id'    => $i++,
                'title' => 'receipt_social_access',
            ],
            [
                'id'    => $i++,
                'title' => 'receipt_social_restore',
            ],
            [
                'id'    => $i++,
                'title' => 'receipt_social_print',
            ],
            [
                'id'    => $i++,
                'title' => 'receipt_social_duplicate',
            ],
            [
                'id'    => $i++,
                'title' => 'receipt_social_receive_money',
            ],
            [
                'id'    => $i++,
                'title' => 'receipt_social_product_create',
            ],
            [
                'id'    => $i++,
                'title' => 'receipt_social_product_edit',
            ],
            [
                'id'    => $i++,
                'title' => 'receipt_social_product_show',
            ],
            [
                'id'    => $i++,
                'title' => 'receipt_social_product_delete',
            ],
            [
                'id'    => $i++,
                'title' => 'receipt_social_product_access',
            ],
            [
                'id'    => $i++,
                'title' => 'receipt_client_create',
            ],
            [
                'id'    => $i++,
                'title' => 'receipt_client_edit',
            ],
            [
                'id'    => $i++,
                'title' => 'receipt_client_show',
            ],
            [
                'id'    => $i++,
                'title' => 'receipt_client_delete',
            ],
            [
                'id'    => $i++,
                'title' => 'receipt_client_access',
            ],
            [
                'id'    => $i++,
                'title' => 'receipt_client_print',
            ],
            [
                'id'    => $i++,
                'title' => 'receipt_client_restore',
            ],
            [
                'id'    => $i++,
                'title' => 'receipt_client_duplicate',
            ],
            [
                'id'    => $i++,
                'title' => 'receipt_client_receive_money',
            ],
            [
                'id'    => $i++,
                'title' => 'receipt_client_product_create',
            ],
            [
                'id'    => $i++,
                'title' => 'receipt_client_product_edit',
            ],
            [
                'id'    => $i++,
                'title' => 'receipt_client_product_show',
            ],
            [
                'id'    => $i++,
                'title' => 'receipt_client_product_delete',
            ],
            [
                'id'    => $i++,
                'title' => 'receipt_client_product_access',
            ],
            [
                'id'    => $i++,
                'title' => 'receipt_company_create',
            ],
            [
                'id'    => $i++,
                'title' => 'receipt_company_edit',
            ],
            [
                'id'    => $i++,
                'title' => 'receipt_company_show',
            ],
            [
                'id'    => $i++,
                'title' => 'receipt_company_delete',
            ],
            [
                'id'    => $i++,
                'title' => 'receipt_company_access',
            ],
            [
                'id'    => $i++,
                'title' => 'receipt_company_duplicate',
            ],
            [
                'id'    => $i++,
                'title' => 'receipt_company_print',
            ],
            [
                'id'    => $i++,
                'title' => 'receipt_company_restore',
            ],
            [
                'id'    => $i++,
                'title' => 'setting_access',
            ],
            [
                'id'    => $i++,
                'title' => 'general_setting_edit',
            ],
            [
                'id'    => $i++,
                'title' => 'general_setting_show',
            ],
            [
                'id'    => $i++,
                'title' => 'general_setting_access',
            ],
            [
                'id'    => $i++,
                'title' => 'customer_create',
            ],
            [
                'id'    => $i++,
                'title' => 'customer_edit',
            ],
            [
                'id'    => $i++,
                'title' => 'customer_show',
            ],
            [
                'id'    => $i++,
                'title' => 'customer_delete',
            ],
            [
                'id'    => $i++,
                'title' => 'customer_access',
            ],
            [
                'id'    => $i++,
                'title' => 'seller_managment_access',
            ],
            [
                'id'    => $i++,
                'title' => 'seller_create',
            ],
            [
                'id'    => $i++,
                'title' => 'seller_edit',
            ],
            [
                'id'    => $i++,
                'title' => 'seller_show',
            ],
            [
                'id'    => $i++,
                'title' => 'seller_delete',
            ],
            [
                'id'    => $i++,
                'title' => 'seller_access',
            ],
            [
                'id'    => $i++,
                'title' => 'commission_request_create',
            ],
            [
                'id'    => $i++,
                'title' => 'commission_request_edit',
            ],
            [
                'id'    => $i++,
                'title' => 'commission_request_show',
            ],
            [
                'id'    => $i++,
                'title' => 'commission_request_delete',
            ],
            [
                'id'    => $i++,
                'title' => 'commission_request_access',
            ],
            [
                'id'    => $i++,
                'title' => 'admin_create',
            ],
            [
                'id'    => $i++,
                'title' => 'admin_edit',
            ],
            [
                'id'    => $i++,
                'title' => 'admin_show',
            ],
            [
                'id'    => $i++,
                'title' => 'admin_delete',
            ],
            [
                'id'    => $i++,
                'title' => 'admin_access',
            ],
            [
                'id'    => $i++,
                'title' => 'borrow_create',
            ],
            [
                'id'    => $i++,
                'title' => 'borrow_edit',
            ],
            [
                'id'    => $i++,
                'title' => 'borrow_show',
            ],
            [
                'id'    => $i++,
                'title' => 'borrow_delete',
            ],
            [
                'id'    => $i++,
                'title' => 'borrow_access',
            ],
            [
                'id'    => $i++,
                'title' => 'subtraction_create',
            ],
            [
                'id'    => $i++,
                'title' => 'subtraction_edit',
            ],
            [
                'id'    => $i++,
                'title' => 'subtraction_show',
            ],
            [
                'id'    => $i++,
                'title' => 'subtraction_delete',
            ],
            [
                'id'    => $i++,
                'title' => 'subtraction_access',
            ],
            [
                'id'    => $i++,
                'title' => 'employee_create',
            ],
            [
                'id'    => $i++,
                'title' => 'employee_edit',
            ],
            [
                'id'    => $i++,
                'title' => 'employee_show',
            ],
            [
                'id'    => $i++,
                'title' => 'employee_delete',
            ],
            [
                'id'    => $i++,
                'title' => 'employee_access',
            ],
            [
                'id'    => $i++,
                'title' => 'country_create',
            ],
            [
                'id'    => $i++,
                'title' => 'country_edit',
            ],
            [
                'id'    => $i++,
                'title' => 'country_show',
            ],
            [
                'id'    => $i++,
                'title' => 'country_delete',
            ],
            [
                'id'    => $i++,
                'title' => 'country_access',
            ],
            [
                'id'    => $i++,
                'title' => 'social_create',
            ],
            [
                'id'    => $i++,
                'title' => 'social_edit',
            ],
            [
                'id'    => $i++,
                'title' => 'social_show',
            ],
            [
                'id'    => $i++,
                'title' => 'social_delete',
            ],
            [
                'id'    => $i++,
                'title' => 'social_access',
            ],
            [
                'id'    => $i++,
                'title' => 'banned_phone_create',
            ],
            [
                'id'    => $i++,
                'title' => 'banned_phone_edit',
            ],
            [
                'id'    => $i++,
                'title' => 'banned_phone_show',
            ],
            [
                'id'    => $i++,
                'title' => 'banned_phone_delete',
            ],
            [
                'id'    => $i++,
                'title' => 'banned_phone_access',
            ],
            [
                'id'    => $i++,
                'title' => 'police_create',
            ],
            [
                'id'    => $i++,
                'title' => 'police_edit',
            ],
            [
                'id'    => $i++,
                'title' => 'police_show',
            ],
            [
                'id'    => $i++,
                'title' => 'police_delete',
            ],
            [
                'id'    => $i++,
                'title' => 'police_access',
            ],
            [
                'id'    => $i++,
                'title' => 'frontend_setting_access',
            ],
            [
                'id'    => $i++,
                'title' => 'slider_create',
            ],
            [
                'id'    => $i++,
                'title' => 'slider_edit',
            ],
            [
                'id'    => $i++,
                'title' => 'slider_show',
            ],
            [
                'id'    => $i++,
                'title' => 'slider_delete',
            ],
            [
                'id'    => $i++,
                'title' => 'slider_access',
            ],
            [
                'id'    => $i++,
                'title' => 'banner_create',
            ],
            [
                'id'    => $i++,
                'title' => 'banner_edit',
            ],
            [
                'id'    => $i++,
                'title' => 'banner_show',
            ],
            [
                'id'    => $i++,
                'title' => 'banner_delete',
            ],
            [
                'id'    => $i++,
                'title' => 'banner_access',
            ],
            [
                'id'    => $i++,
                'title' => 'product_managment_access',
            ],
            [
                'id'    => $i++,
                'title' => 'category_create',
            ],
            [
                'id'    => $i++,
                'title' => 'category_edit',
            ],
            [
                'id'    => $i++,
                'title' => 'category_show',
            ],
            [
                'id'    => $i++,
                'title' => 'category_delete',
            ],
            [
                'id'    => $i++,
                'title' => 'category_access',
            ],
            [
                'id'    => $i++,
                'title' => 'home_category_create',
            ],
            [
                'id'    => $i++,
                'title' => 'home_category_edit',
            ],
            [
                'id'    => $i++,
                'title' => 'home_category_show',
            ],
            [
                'id'    => $i++,
                'title' => 'home_category_delete',
            ],
            [
                'id'    => $i++,
                'title' => 'home_category_access',
            ],
            [
                'id'    => $i++,
                'title' => 'quality_responsible_create',
            ],
            [
                'id'    => $i++,
                'title' => 'quality_responsible_edit',
            ],
            [
                'id'    => $i++,
                'title' => 'quality_responsible_show',
            ],
            [
                'id'    => $i++,
                'title' => 'quality_responsible_delete',
            ],
            [
                'id'    => $i++,
                'title' => 'quality_responsible_access',
            ],
            [
                'id'    => $i++,
                'title' => 'seo_setting_create',
            ],
            [
                'id'    => $i++,
                'title' => 'seo_setting_edit',
            ],
            [
                'id'    => $i++,
                'title' => 'seo_setting_show',
            ],
            [
                'id'    => $i++,
                'title' => 'seo_setting_delete',
            ],
            [
                'id'    => $i++,
                'title' => 'seo_setting_access',
            ],
            [
                'id'    => $i++,
                'title' => 'conversation_create',
            ],
            [
                'id'    => $i++,
                'title' => 'conversation_edit',
            ],
            [
                'id'    => $i++,
                'title' => 'conversation_show',
            ],
            [
                'id'    => $i++,
                'title' => 'conversation_delete',
            ],
            [
                'id'    => $i++,
                'title' => 'conversation_access',
            ],
            [
                'id'    => $i++,
                'title' => 'order_create',
            ],
            [
                'id'    => $i++,
                'title' => 'order_edit',
            ],
            [
                'id'    => $i++,
                'title' => 'order_show',
            ],
            [
                'id'    => $i++,
                'title' => 'order_delete',
            ],
            [
                'id'    => $i++,
                'title' => 'order_access',
            ],
            [
                'id'    => $i++,
                'title' => 'receipt_outgoing_create',
            ],
            [
                'id'    => $i++,
                'title' => 'receipt_outgoing_edit',
            ],
            [
                'id'    => $i++,
                'title' => 'receipt_outgoing_show',
            ],
            [
                'id'    => $i++,
                'title' => 'receipt_outgoing_delete',
            ],
            [
                'id'    => $i++,
                'title' => 'receipt_outgoing_restore',
            ],
            [
                'id'    => $i++,
                'title' => 'receipt_outgoing_access',
            ],
            [
                'id'    => $i++,
                'title' => 'receipt_outgoing_print',
            ],
            [
                'id'    => $i++,
                'title' => 'receipt_outgoing_duplicate',
            ],
            [
                'id'    => $i++,
                'title' => 'receipt_outgoing_product_create',
            ],
            [
                'id'    => $i++,
                'title' => 'receipt_outgoing_product_edit',
            ],
            [
                'id'    => $i++,
                'title' => 'receipt_outgoing_product_show',
            ],
            [
                'id'    => $i++,
                'title' => 'receipt_outgoing_product_delete',
            ],
            [
                'id'    => $i++,
                'title' => 'receipt_outgoing_product_access',
            ],
            [
                'id'    => $i++,
                'title' => 'receipt_price_view_create',
            ],
            [
                'id'    => $i++,
                'title' => 'receipt_price_view_edit',
            ],
            [
                'id'    => $i++,
                'title' => 'receipt_price_view_show',
            ],
            [
                'id'    => $i++,
                'title' => 'receipt_price_view_delete',
            ],
            [
                'id'    => $i++,
                'title' => 'receipt_price_view_access',
            ],
            [
                'id'    => $i++,
                'title' => 'receipt_price_view_print',
            ],
            [
                'id'    => $i++,
                'title' => 'receipt_price_view_restore',
            ],
            [
                'id'    => $i++,
                'title' => 'receipt_price_view_product_create',
            ],
            [
                'id'    => $i++,
                'title' => 'receipt_price_view_product_edit',
            ],
            [
                'id'    => $i++,
                'title' => 'receipt_price_view_product_show',
            ],
            [
                'id'    => $i++,
                'title' => 'receipt_price_view_product_delete',
            ],
            [
                'id'    => $i++,
                'title' => 'receipt_price_view_product_access',
            ],
            [
                'id'    => $i++,
                'title' => 'excel_file_create',
            ],
            [
                'id'    => $i++,
                'title' => 'excel_file_edit',
            ],
            [
                'id'    => $i++,
                'title' => 'excel_file_show',
            ],
            [
                'id'    => $i++,
                'title' => 'excel_file_delete',
            ],
            [
                'id'    => $i++,
                'title' => 'excel_file_access',
            ],
            [
                'id'    => $i++,
                'title' => 'product_create',
            ],
            [
                'id'    => $i++,
                'title' => 'product_edit',
            ],
            [
                'id'    => $i++,
                'title' => 'product_show',
            ],
            [
                'id'    => $i++,
                'title' => 'product_delete',
            ],
            [
                'id'    => $i++,
                'title' => 'product_access',
            ],
            [
                'id'    => $i++,
                'title' => 'sub_category_create',
            ],
            [
                'id'    => $i++,
                'title' => 'sub_category_edit',
            ],
            [
                'id'    => $i++,
                'title' => 'sub_category_show',
            ],
            [
                'id'    => $i++,
                'title' => 'sub_category_delete',
            ],
            [
                'id'    => $i++,
                'title' => 'sub_category_access',
            ],
            [
                'id'    => $i++,
                'title' => 'sub_sub_category_create',
            ],
            [
                'id'    => $i++,
                'title' => 'sub_sub_category_edit',
            ],
            [
                'id'    => $i++,
                'title' => 'sub_sub_category_show',
            ],
            [
                'id'    => $i++,
                'title' => 'sub_sub_category_delete',
            ],
            [
                'id'    => $i++,
                'title' => 'sub_sub_category_access',
            ],
            [
                'id'    => $i++,
                'title' => 'attribute_create',
            ],
            [
                'id'    => $i++,
                'title' => 'attribute_edit',
            ],
            [
                'id'    => $i++,
                'title' => 'attribute_show',
            ],
            [
                'id'    => $i++,
                'title' => 'attribute_delete',
            ],
            [
                'id'    => $i++,
                'title' => 'attribute_access',
            ],
            [
                'id'    => $i++,
                'title' => 'review_access',
            ],
            [
                'id'    => $i++,
                'title' => 'playlist_create',
            ],
            [
                'id'    => $i++,
                'title' => 'playlist_edit',
            ],
            [
                'id'    => $i++,
                'title' => 'playlist_show',
            ],
            [
                'id'    => $i++,
                'title' => 'playlist_delete',
            ],
            [
                'id'    => $i++,
                'title' => 'playlist_access',
            ],
            [
                'id'    => $i++,
                'title' => 'borrows_and_subtraction_access',
            ],
            [
                'id'    => $i++,
                'title' => 'faq_management_access',
            ],
            [
                'id'    => $i++,
                'title' => 'faq_category_create',
            ],
            [
                'id'    => $i++,
                'title' => 'faq_category_edit',
            ],
            [
                'id'    => $i++,
                'title' => 'faq_category_show',
            ],
            [
                'id'    => $i++,
                'title' => 'faq_category_delete',
            ],
            [
                'id'    => $i++,
                'title' => 'faq_category_access',
            ],
            [
                'id'    => $i++,
                'title' => 'faq_question_create',
            ],
            [
                'id'    => $i++,
                'title' => 'faq_question_edit',
            ],
            [
                'id'    => $i++,
                'title' => 'faq_question_show',
            ],
            [
                'id'    => $i++,
                'title' => 'faq_question_delete',
            ],
            [
                'id'    => $i++,
                'title' => 'faq_question_access',
            ],
            [
                'id'    => $i++,
                'title' => 'mockups_managment_access',
            ],
            [
                'id'    => $i++,
                'title' => 'mockup_create',
            ],
            [
                'id'    => $i++,
                'title' => 'mockup_edit',
            ],
            [
                'id'    => $i++,
                'title' => 'mockup_show',
            ],
            [
                'id'    => $i++,
                'title' => 'mockup_delete',
            ],
            [
                'id'    => $i++,
                'title' => 'mockup_access',
            ],
            [
                'id'    => $i++,
                'title' => 'designer_create',
            ],
            [
                'id'    => $i++,
                'title' => 'designer_edit',
            ],
            [
                'id'    => $i++,
                'title' => 'designer_show',
            ],
            [
                'id'    => $i++,
                'title' => 'designer_delete',
            ],
            [
                'id'    => $i++,
                'title' => 'designer_access',
            ],
            [
                'id'    => $i++,
                'title' => 'designe_create',
            ],
            [
                'id'    => $i++,
                'title' => 'designe_edit',
            ],
            [
                'id'    => $i++,
                'title' => 'designe_show',
            ],
            [
                'id'    => $i++,
                'title' => 'designe_delete',
            ],
            [
                'id'    => $i++,
                'title' => 'designe_access',
            ],
            [
                'id'    => $i++,
                'title' => 'delivery_order_create',
            ],
            [
                'id'    => $i++,
                'title' => 'delivery_order_edit',
            ],
            [
                'id'    => $i++,
                'title' => 'delivery_order_show',
            ],
            [
                'id'    => $i++,
                'title' => 'delivery_order_delete',
            ],
            [
                'id'    => $i++,
                'title' => 'delivery_order_access',
            ],
            [
                'id'    => $i++,
                'title' => 'delivery_managment_access',
            ],
            [
                'id'    => $i++,
                'title' => 'deliver_man_create',
            ],
            [
                'id'    => $i++,
                'title' => 'deliver_man_edit',
            ],
            [
                'id'    => $i++,
                'title' => 'deliver_man_show',
            ],
            [
                'id'    => $i++,
                'title' => 'deliver_man_delete',
            ],
            [
                'id'    => $i++,
                'title' => 'deliver_man_access',
            ],
            [
                'id'    => $i++,
                'title' => 'profile_password_edit',
            ],
        ];

        Permission::insert($permissions);
    }
}
