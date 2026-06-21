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
            'store' => [
                'id' => $this->store_id,
                'name' => $this->store->store_name ?? null,
                'logo' => $this->store && $this->store->logo ? $this->store->logo->getUrl() : getNonImage(),
            ],
            'order_status' => $this->order_status,
            'delivery_status' => $this->delivery_status,
            'expected_delivery_date' => $this->expected_delivery_date,
            'status' => $this->status,
            'payment_status' => $this->payment_status,
            'created_at' => $this->created_at,
        ];
    }
}
