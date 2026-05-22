<?php

namespace App\Contracts\Shipping;

interface TimelineRecorderContract
{
    public function recordCreated(int $shipmentId, ?int $userId = null, ?string $body = null): void;

    public function recordStatusChange(
        int $shipmentId,
        ?string $oldStatus,
        string $newStatus,
        ?int $userId = null,
        ?string $body = null,
        array $meta = []
    ): void;

    public function recordAssignment(int $shipmentId, string $courierLabel, ?int $userId = null): void;

    public function recordNote(int $shipmentId, string $body, ?int $userId = null, ?int $parentId = null): void;
}
