<?php

namespace App\Http\Resources\V1\Customer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailResource extends JsonResource
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
            'product_id' => $this->product_id,
            'product_name' => $this->whenLoaded('product', fn() => $this->product->name),
            'product_main_photo' => $this->whenLoaded('product', fn() => $this->product->main_photo ? $this->product->main_photo->getUrl() : getNonImage()),
            'quantity' => $this->quantity,
            'price' => round($this->price),
            'total_cost' => round($this->total_cost),
            'variation' => $this->variation,
            'description' => $this->description,
            'photos' => $this->photos,
            'pdf' => $this->pdf,
            'link' => $this->link,
            'email_sent' => $this->email_sent,
            'created_at' => $this->created_at ?? null,
        ];
    }
}
