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
            'order_details' => $this->whenLoaded('orderDetails', function () {
                return OrderDetailResource::collection($this->orderDetails);
            }),
            'number_of_products' => $this->order_details_count,
            'sub_total' => round($this->calc_total_cost()),
            'discount' => round($this->calc_discount()),
            'shipping_cost' => round($this->shipping_country_cost),
            'total_cost' => round($this->calc_total() - $this->calc_discount()),
            'delivery_status' => $this->delivery_status,
            'payment_type' => $this->payment_type,
            'shipping_address' => $this->shipping_address,
            'shipping_country' => $this->shipping_country->name ?? null,
            'created_at' => $this->created_at ?? null,
        ];
    }
}
