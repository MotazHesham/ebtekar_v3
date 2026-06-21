<?php

namespace App\Http\Resources\V1\Customer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HomeCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $category = $this->category;

        if (!$category) {
            return [];
        }

        return [
            'id' => $category->id,
            'name' => $category->name,
            'banner' => $category->banner ? $category->banner->getUrl() : null,
            'icon' => $category->icon ? $category->icon->getUrl() : null,
        ];
    }
}
