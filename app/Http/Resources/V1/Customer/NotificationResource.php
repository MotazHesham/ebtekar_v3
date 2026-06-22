<?php

namespace App\Http\Resources\V1\Customer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
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
            'title' => $this->title,
            'alert_text' => $this->alert_text,
            'alert_link' => $this->alert_link,
            'data' => $this->data,
            'type' => $this->type,
            'created_at' => $this->created_at,
        ];
    }
}
