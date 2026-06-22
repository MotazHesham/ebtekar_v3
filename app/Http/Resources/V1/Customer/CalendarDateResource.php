<?php

namespace App\Http\Resources\V1\Customer;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CalendarDateResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'type_label' => $this->type_label,
            'description' => $this->description,
            'date' => $this->event_date,
            'reminder_days_before' => $this->effective_reminder_days_before,
            'uses_default_reminder' => is_null($this->reminder_days_before),
            'created_at' => $this->created_at ? Carbon::parse($this->created_at)->format('Y-m-d H:i:s') : null,
        ];
    }
}
