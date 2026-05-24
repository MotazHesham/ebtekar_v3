<?php

namespace Modules\Shipping\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShipmentResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'uuid'            => $this->uuid,
            'order_num'       => $this->order_num,
            'status'          => $this->status,
            'status_label'    => $this->status_label,
            'client_name'     => $this->client_name,
            'phone_number'    => $this->phone_number,
            'governorate'     => $this->governorate,
            'region'          => $this->region,
            'full_address'    => $this->full_address,
            'remaining_cod'   => $this->remaining_cod,
            'last_status_at'  => $this->last_status_at?->toIso8601String(),
            'pending_since'   => $this->pending_since,
            'partner'         => $this->whenLoaded('shippingPartner', fn () => [
                'uuid' => $this->shippingPartner->uuid,
                'name' => $this->shippingPartner->name,
            ]),
            'courier'         => $this->whenLoaded('courier', fn () => [
                'uuid' => $this->courier->uuid,
                'name' => $this->courier->user?->name,
            ]),
        ];
    }
}
