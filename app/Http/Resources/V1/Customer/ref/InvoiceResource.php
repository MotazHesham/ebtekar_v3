<?php

namespace App\Http\Resources\V1\Customer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
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
            'invoice_num' => $this->id,
            'order_num' => $this->order_num,
            'created_at' => $this->created_at,
            'store' => [
                'id' => $this->store_id,
                'name' => $this->store->store_name ?? null, 
            ], 
            'payment_method' => $this->payment_method,
            'sub_total' => formatPrice($this->sub_total),
            'coupon_discount' => formatPrice($this->coupon_discount),
            'shipping_cost' => formatPrice($this->shipping_cost),
            'vat' => formatPrice($this->vat),
            'total' => formatPrice($this->total), 
        ];
    }
}
