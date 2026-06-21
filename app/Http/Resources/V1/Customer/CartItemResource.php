<?php

namespace App\Http\Resources\V1\Customer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $photos = json_decode($this->photos);
        if ($photos && count($photos) > 0) {
            $photos = array_map(function ($photo) {
                return [
                    'photo' => $photo->photo ? asset($photo->photo) : null,
                    'note' => $photo->note,
                ];
            }, $photos);
        }
        return [
            'id' => $this->id,
            'product' => $this->whenLoaded('product', function () {
                return new ProductListResource($this->product);
            }),
            'quantity' => $this->quantity,
            'variant' => $this->variant,
            'description' => $this->description,
            'photos' => $photos ? $photos : null,
            'pdf' => $this->pdf,
            'link' => $this->link,
            'email_sent' => $this->email_sent,
        ];
    }
}
