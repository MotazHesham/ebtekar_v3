<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement($this->createView());
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement($this->dropView());
    }
    private function createView(): string
    { 
        return 'CREATE VIEW view_playlist_data AS
        
                SELECT  CONCAT("social") AS model_type,rs.id,rs.order_num,rs.client_name,rs.client_type,rs.phone_number,rs.phone_number_2,rs.deposit,rs.total_cost,rs.shipping_address,rs.shipping_country_id,rs.
                delivery_status,rs.payment_status,rs.playlist_status,rs.delay_reason,rs.cancel_reason,rs.designer_id,rs.preparer_id,rs.manufacturer_id,rs.staff_id as added_by,is_seasoned,
                rs.shipmenter_id,rs.delivery_man_id,rs.note,rs.send_to_playlist_date,rs.send_to_delivery_date,rs.quickly,rs.client_review,rs.printing_times,rs.website_setting_id,rs.hold,rs.created_at,rs.updated_at,rs.deleted_at,
                GROUP_CONCAT(CONCAT(rsp.title, "(", rsp.quantity, ") <br>",rsp.description) SEPARATOR "<hr>") AS description
                FROM receipt_socials rs
                JOIN receipt_social_receipt_social_product rsp ON rs.id = rsp.receipt_social_id
                Where  
                    rs.playlist_status != "pending" 
                    AND rs.playlist_status != "finish"
                GROUP BY rs.id
                
                UNION ALL

                SELECT CONCAT("company") AS model_type,id,order_num,client_name,client_type,phone_number,phone_number_2,deposit,total_cost,shipping_address,shipping_country_id,
                delivery_status,payment_status,playlist_status,delay_reason,cancel_reason,designer_id,preparer_id,manufacturer_id,staff_id As added_by,CONCAT(0) AS is_seasoned,
                shipmenter_id,delivery_man_id,note,send_to_playlist_date,send_to_delivery_date,quickly,client_review,printing_times,CONCAT(0) AS website_setting_id,CONCAT(0) AS hold,created_at,updated_at,deleted_at,description
                FROM receipt_companies
                WHERE 
                    playlist_status != "pending" 
                    AND playlist_status != "finish"

                UNION ALL

                SELECT CONCAT("order") AS model_type,ords.id,ords.order_num,ords.client_name,CONCAT("individual") AS client_type,ords.phone_number,ords.phone_number_2,ords.deposit_amount as deposit,ords.total_cost,ords.shipping_address,ords.shipping_country_id,
                ords.delivery_status,ords.payment_status,ords.playlist_status,ords.delay_reason,ords.cancel_reason,ords.designer_id,ords.preparer_id,ords.manufacturer_id,ords.user_id As added_by,CONCAT(0) AS is_seasoned,
                ords.shipmenter_id,ords.delivery_man_id,ords.note,ords.send_to_playlist_date,ords.send_to_delivery_date,ords.quickly,ords.client_review,ords.printing_times,ords.website_setting_id,ords.hold,ords.created_at,ords.updated_at,ords.deleted_at,
                GROUP_CONCAT(CONCAT(p.name, "(", ords_detls.quantity, ") <br>",ords_detls.description) SEPARATOR "<hr>") AS description
                FROM orders ords
                JOIN order_details ords_detls ON ords.id = ords_detls.order_id 
                JOIN products p ON p.id = ords_detls.product_id
                WHERE
                    ords.playlist_status != "pending"
                    AND ords.playlist_status != "finish"
                GROUP BY ords.id 
            ';
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    private function dropView(): string
    {
        return ' 
            DROP VIEW IF EXISTS `view_playlist_data`;
            ';
    }
};
