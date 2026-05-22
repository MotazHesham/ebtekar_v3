<?php

namespace Modules\Dispatch\DTO;

use Modules\Dispatch\Enums\BatchItemResult;
use Modules\Shipping\Entities\Shipment;

final class AssignmentResult
{
    public function __construct(
        public readonly bool $ok,
        public readonly BatchItemResult $result,
        public readonly string $message,
        public readonly ?Shipment $shipment = null,
        public readonly ?int $courierId = null,
    ) {
    }

    public function toArray(): array
    {
        return [
            'ok'          => $this->ok,
            'result'      => $this->result->value,
            'message'     => $this->message,
            'shipment_id' => $this->shipment?->id,
            'courier_id'  => $this->courierId,
        ];
    }
}
