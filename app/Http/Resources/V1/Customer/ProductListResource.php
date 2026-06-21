<?php

namespace App\Http\Resources\V1\Customer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductListResource extends JsonResource
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
            'name' => $this->name,
            'main_photo' => $this->main_photo ? $this->main_photo->getUrl() : getNonImage(),
            'discount' => $this->discount,
            'discount_type' => $this->discount_type,
            'base_price' => $this->basePrice(),
            'discounted_price' => $this->baseDiscountedPrice(),
            'rating' => $this->rating,
            'reviews_count' => $this->reviews_count,
            'is_favorite' => $this->isInFavorites(request()->user('sanctum')),
        ];
    }
}
