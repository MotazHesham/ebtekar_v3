<?php

namespace Modules\Timeline\Services;

use App\Contracts\Shipping\TimelineRecorderContract;
use Carbon\Carbon;
use Modules\Timeline\Entities\ShipmentNote;
use Modules\Timeline\Entities\ShipmentStatusHistory;
use Modules\Timeline\Entities\TimelineEvent;

class TimelineRecorder implements TimelineRecorderContract
{
    public function recordCreated(int $shipmentId, ?int $userId = null, ?string $body = null): void
    {
        $this->insertEvent($shipmentId, TimelineEvent::TYPE_CREATED, null, 'pending', $body, $userId);
    }

    public function recordStatusChange(
        int $shipmentId,
        ?string $oldStatus,
        string $newStatus,
        ?int $userId = null,
        ?string $body = null,
        array $meta = []
    ): void {
        $this->insertEvent($shipmentId, TimelineEvent::TYPE_STATUS_CHANGE, $oldStatus, $newStatus, $body, $userId, $meta);

        ShipmentStatusHistory::create([
            'delivery_order_id' => $shipmentId,
            'user_id'           => $userId,
            'old_status'        => $oldStatus,
            'new_status'        => $newStatus,
            'meta'              => $meta,
        ]);
    }

    public function recordAssignment(int $shipmentId, string $courierLabel, ?int $userId = null): void
    {
        $body = __('delivery.timeline.assigned_to', ['name' => $courierLabel]);
        $this->insertEvent($shipmentId, TimelineEvent::TYPE_ASSIGNED, null, null, $body, $userId);
    }

    public function recordNote(int $shipmentId, string $body, ?int $userId = null, ?int $parentId = null): void
    {
        ShipmentNote::create([
            'delivery_order_id' => $shipmentId,
            'user_id'           => $userId ?: auth()->id(),
            'parent_id'         => $parentId,
            'body'              => $body,
        ]);

        $this->insertEvent($shipmentId, TimelineEvent::TYPE_NOTE_ADDED, null, null, $body, $userId);
    }

    protected function insertEvent(
        int $shipmentId,
        string $type,
        ?string $oldStatus,
        ?string $newStatus,
        ?string $body,
        ?int $userId,
        array $meta = []
    ): void {
        TimelineEvent::create([
            'delivery_order_id' => $shipmentId,
            'user_id'           => $userId ?: auth()->id(),
            'event_type'        => $type,
            'old_status'        => $oldStatus,
            'new_status'        => $newStatus,
            'body'              => $body,
            'meta'              => $meta,
            'created_at'        => Carbon::now(),
        ]);
    }
}
