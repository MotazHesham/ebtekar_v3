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
        Schema::create('egyptexpress_airway_bills', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            // Polymorphic relationship to the source model (ReceiptSocial, ReceiptCompany, Order, etc.)
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');
            
            // Reference information
            $table->string('shipper_reference')->nullable();
            $table->string('order_num')->nullable();
            
            // Tracking information from EgyptExpress response
            $table->string('airway_bill_number')->nullable();
            $table->string('tracking_number')->nullable();
            $table->string('status')->nullable();
            $table->text('status_description')->nullable();
            
            // Receiver information (stored for reference)
            $table->string('receiver_name');
            $table->string('receiver_phone');
            $table->string('receiver_city');
            $table->string('destination');
            
            // Shipping details
            $table->integer('number_of_pieces')->default(1);
            $table->decimal('weight', 8, 2)->default(0);
            $table->string('goods_description')->nullable();
            $table->decimal('cod_amount', 15, 2)->default(0);
            $table->string('cod_currency')->nullable();
            $table->decimal('invoice_value', 15, 2)->default(0);
            $table->string('invoice_currency')->default('EGP');
            
            // Full request payload (JSON)
            $table->longText('request_payload')->nullable();
            
            // Full response from API (JSON)
            $table->longText('response_data')->nullable();
            
            // Status of the API call
            $table->boolean('is_successful')->default(false);
            $table->text('error_message')->nullable();
            $table->integer('http_status_code')->nullable();
            $table->longText('airwaybillpdf')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['model_type', 'model_id'], 'egyptexpress_airway_bills_model_index');
            $table->index('airway_bill_number', 'egyptexpress_airway_bills_awb_index');
            $table->index('tracking_number', 'egyptexpress_airway_bills_tracking_index');
            $table->index('shipper_reference', 'egyptexpress_airway_bills_reference_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('egyptexpress_airway_bills');
    }
};
