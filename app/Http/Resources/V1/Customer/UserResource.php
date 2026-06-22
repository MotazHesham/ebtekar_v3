<?php

namespace App\Http\Resources\V1\Customer;

use App\Http\Resources\V1\General\CityResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'token' => $this->whenNotNull($this->token),
            'photo' => $this->photo ? $this->photo->getUrl() : getNonImage(),
            'notification_count' => $this->userUserAlerts()->where('read', false)->count(),
            'cart_items_count' => $this->cart?->cartItems()->count() ?? 0,
        ];
    }
}
