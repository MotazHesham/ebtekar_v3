<?php

namespace App\Http\Resources\V1\Customer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductSingleResource extends JsonResource
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
            'base_price' => $this->basePrice($this),
            'discounted_price' => $this->baseDiscountedPrice($this),
            'rating' => $this->rating,
            'reviews_count' => $this->reviews->count(),
            'is_favorite' => $this->isInFavorites(request()->user('sanctum')),
            'description' => $this->description,
            'photos' => $this->photos->map(function ($photo) {
                return $photo->getUrl();
            }),
            'colors' => $this->colors ? json_decode($this->colors) : null,
            'attribute_options' => $this->attribute_options ? collect(json_decode($this->attribute_options))->map(function ($option) {
                return [
                    'attribute_id' => $option->attribute_id,
                    'attribute_name' => get_single_attribute_name($option->attribute_id),
                    'values' => $option->values
                ];
            })->values()->all() : null,
            'max_quantity' => $this->current_stock,
            'special' => $this->special,
            'require_photos' => $this->require_photos,
            'variant_product' => $this->variant_product,
            'can_rate' => false,
        ];
    }
}
