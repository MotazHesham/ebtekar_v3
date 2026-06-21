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
            'title' => $this->getTitle(),
            'content' => $this->getContent(),
            'icon' => $this->getIcon(),
            'icon_color' => $this->getIconColor(),
            'created_at' => $this->created_at->diffForHumans(),
            'read_at' => $this->read_at,
            'type' => $this->type,
            'extra_data' => json_decode($this->data),
        ];
    }
}
