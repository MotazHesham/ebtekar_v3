<?php

namespace App\Http\Resources\V1\Customer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderListResource extends JsonResource
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
            'number_of_products' => $this->order_details_count,
            'total_cost' => round($this->total_cost),
            'delivery_status' => $this->delivery_status,
            'product_photos' => $this->orderDetails->map(function ($item) {
                return $item->product->getMedia('photos')->first()->getUrl();
            }),
            'created_at' => $this->created_at ?? null,
        ];
    }
}
