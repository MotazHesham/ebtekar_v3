<?php

namespace App\Http\Resources\V1\Customer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderSingleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_num' => $this->order_num,  
            'store' => [
                'id' => $this->store_id,
                'name' => $this->store->store_name ?? null,
                'logo' => $this->store && $this->store->logo ? $this->store->logo->getUrl() : getNonImage(),
            ],
            'order_details' => $this->whenLoaded('orderOrderDetails', function(){
                return OrderDetailResource::collection($this->orderOrderDetails);
            }),
            'special_order' => $this->whenLoaded('specialOrder', function(){
                return new SpecialOrderSingleResource($this->specialOrder);
            }),
            'order_status' => $this->order_status,
            'delivery_status' => $this->delivery_status,
            'payment_status' => $this->payment_status,  
            'status' => $this->status,
            'expected_delivery_date' => $this->expected_delivery_date,

            'shipping_address' => $this->shippingAddress(),
            'note' => $this->note,

            'sub_total' => formatPrice($this->sub_total),
            'coupon_discount' => formatPrice($this->coupon_discount),
            'shipping_cost' => formatPrice($this->shipping_cost), 
            'vat' => formatPrice($this->vat),
            'total' => formatPrice($this->total),

            'rate' =>(float) $this->rate ?? 0,
            
            'created_at' => $this->created_at,
        ];
    }
}
