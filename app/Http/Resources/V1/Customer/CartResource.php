<?php

namespace App\Http\Resources\V1\Customer;

use App\Services\OrderSummaryService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
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
            'temp_user_uid' => $this->temp_user_uid,
            'user_id' => $this->user_id,
            'address_id' => $this->address_id,
            'note' => $this->note,
            'coupon_code' => $this->coupon_code,
            'summary' => app(OrderSummaryService::class)->cartSummary($this),
        ];
    }
}
