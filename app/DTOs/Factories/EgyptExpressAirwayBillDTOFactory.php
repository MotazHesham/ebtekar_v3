<?php

namespace App\DTOs\Factories;

use App\DTOs\EgyptExpressAirwayBillDTO;
use App\Models\Country;
use App\Models\Order;
use App\Models\ReceiptCompany;
use App\Models\ReceiptSocial;

class EgyptExpressAirwayBillDTOFactory
{
    /**
     * Create DTO from ReceiptSocial model
     *
     * @param ReceiptSocial $receipt
     * @return EgyptExpressAirwayBillDTO
     */
    public static function fromReceiptSocial(ReceiptSocial $receipt): EgyptExpressAirwayBillDTO 
    {
        $shippingCountry = $receipt->shipping_country; 

        // Calculate total pieces (sum of quantities from products)
        $numberOfPieces = $receipt->receiptsReceiptSocialProducts->sum('quantity') ?: 1; 

        // Get goods description from products
        $goodsDescription = self::getGoodsDescriptionFromReceiptSocial($receipt); 

        return new EgyptExpressAirwayBillDTO(
            receiverName: $receipt->client_name,
            receiverPhone: $receipt->phone_number,
            receiverPhone2: $receipt->phone_number_2,
            receiverEmail: null, // Add email field if needed
            receiverAddress1: $receipt->shipping_address ?? '',
            receiverAddress2: '',
            receiverCity: $shippingCountry->name,
            receiverProvince: '',
            receiverSubCity: '',
            receiverZip: '',
            receiverCompany: $receipt->client_name,
            receiverCountry: 'Egypt',
            destination: $shippingCountry->code ?? 'CAI',
            origin: 'CAI',
            numberOfPieces: 1,
            weight: 5,
            goodsDescription: $goodsDescription,
            serviceType: 'FRG',
            productType: 'FRE',
            codAmount: $receipt->calc_total_for_client(),
            codCurrency: 'EGP',
            invoiceValue: 0,
            invoiceCurrency: 'EGP',
            shipperReference: $receipt->order_num ?? 'N/A',
            specialInstruction: $receipt->note,
            modelId: $receipt->id,
            modelType: ReceiptSocial::class,
            orderNum: $receipt->order_num,
        );
    }

    /**
     * Create DTO from ReceiptCompany model
     *
     * @param ReceiptCompany $receipt
     * @return EgyptExpressAirwayBillDTO
     */
    public static function fromReceiptCompany(ReceiptCompany $receipt): EgyptExpressAirwayBillDTO
    { 
        // Get shipping country/city info
        $shippingCountry = $receipt->shipping_country ?? null; 

        // Default values for ReceiptCompany
        $numberOfPieces = 1;
        $weight = 1.0; 

        return new EgyptExpressAirwayBillDTO(
            receiverName: $receipt->client_name,
            receiverPhone: $receipt->phone_number,
            receiverPhone2: $receipt->phone_number_2 ?? null,
            receiverEmail: null,
            receiverAddress1: $receipt->shipping_address ?? '',
            receiverAddress2: '',
            receiverCity: $shippingCountry->name,
            receiverProvince: '',
            receiverSubCity: '',
            receiverZip: '',
            receiverCompany: $receipt->client_name,
            receiverCountry: 'Egypt',
            destination: $shippingCountry->code ?? 'CAI',
            origin: 'CAI',
            numberOfPieces: 1,
            weight: 5,
            goodsDescription: strip_tags($receipt->description),
            serviceType: 'FRG',
            productType: 'FRE',
            codAmount: $receipt->need_to_pay,
            codCurrency: 'EGP',
            invoiceValue: 0,
            invoiceCurrency: 'EGP',
            shipperReference: $receipt->order_num ?? 'N/A',
            specialInstruction: $receipt->note ?? null,
            modelId: $receipt->id,
            modelType: ReceiptCompany::class,
            orderNum: $receipt->order_num,
        );
    }

    /**
     * Create DTO from Order model
     *
     * @param Order $order
     * @return EgyptExpressAirwayBillDTO
     */
    public static function fromOrder(Order $order): EgyptExpressAirwayBillDTO
    { 
        $shippingCountry = $order->shipping_country ?? null; 

        // Calculate total pieces from order details if available
        $numberOfPieces = 1;
        if (method_exists($order, 'orderDetails') && $order->relationLoaded('orderDetails')) {
            $numberOfPieces = $order->orderDetails->sum('quantity') ?: 1;
        }

        
        $weight = 1;

        // Get goods description
        $goodsDescription = ''; 
        foreach($order->orderDetails as $orderDetail){
            $name = $orderDetail->product->name ?? 'N/A';
            $quantity = $orderDetail->quantity ?? 'N/A';
            $goodsDescription .= $name . " - [" . $quantity . "]";
        }  

        return new EgyptExpressAirwayBillDTO(
            receiverName: $order->client_name,
            receiverPhone: $order->phone_number,
            receiverPhone2: $order->phone_number_2 ?? null,
            receiverEmail: null,
            receiverAddress1: $order->shipping_address ?? '',
            receiverAddress2: $addressParts['address2'] ?? null,
            receiverCity: $shippingCountry->name,
            receiverProvince: '',
            receiverSubCity: '',
            receiverZip: '',
            receiverCompany: $order->client_name,
            receiverCountry: 'Egypt',
            destination: $shippingCountry->code ?? 'CAI',
            origin: 'CAI',
            numberOfPieces: 1,
            weight: 5,
            goodsDescription: $goodsDescription,
            serviceType: 'FRG',
            productType: 'FRE',
            codAmount: $order->calc_total_for_client(),
            codCurrency: 'EGP',
            invoiceValue: $order->total_cost ?? 0,
            invoiceCurrency: 'EGP',
            shipperReference: $order->order_num ?? 'N/A',
            specialInstruction: $order->note ?? null,
            modelId: $order->id,
            modelType: Order::class,
            orderNum: $order->order_num,
        );
    } 

    /**
     * Get goods description from ReceiptSocial products
     *
     * @param ReceiptSocial $receipt
     * @return string
     */
    protected static function getGoodsDescriptionFromReceiptSocial(ReceiptSocial $receipt): string
    {
        $products = $receipt->receiptsReceiptSocialProducts;
        
        if ($products->isEmpty()) {
            return ''; // Default description
        }

        $description = '';
        foreach($receipt->receiptsReceiptSocialProducts as $product){
            $description .= $product->title . " - [" . $product->quantity . "]"; 
        }
        
        // Limit to 100 characters as per API requirements
        return mb_substr($description, 0, 100);
    }
}
