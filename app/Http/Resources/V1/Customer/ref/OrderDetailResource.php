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
            'product_id' => $this->whenLoaded('product', fn() => $this->product->id),
            'product_name' => $this->whenLoaded('product', fn() => $this->product->name),
            'product_main_photo' => $this->whenLoaded('product', fn() => $this->product->main_photo ? $this->product->main_photo->getUrl() : getNonImage()),
            'product_categories' => $this->whenLoaded('product', fn() => $this->product->product_categories->pluck('name')),
            'quantity' => $this->quantity,
            'price' => formatPrice($this->price),
            'variation' => $this->variation,
        ];
    }
}
