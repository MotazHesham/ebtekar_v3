<?php

namespace Modules\Tracking\DTO;

use Modules\Tracking\Enums\ScanResult;

final class ScanResponse
{
    public function __construct(
        public readonly bool $ok,
        public readonly ScanResult $result,
        public readonly string $htmlMessage,
        public readonly ?int $shipmentId = null,
        public readonly ?string $orderNum = null,
    ) {
    }

    public function toArray(): array
    {
        return [
            'status'  => $this->ok ? 1 : 0,
            'result'  => $this->result->value,
            'message' => $this->htmlMessage,
            'order_num' => $this->orderNum,
            'shipment_id' => $this->shipmentId,
        ];
    }
}
