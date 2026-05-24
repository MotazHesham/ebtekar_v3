<?php

namespace Modules\Shipping\Services;

use App\Contracts\Shipping\ReturnServiceContract;
use App\Contracts\Shipping\ShipmentServiceContract;
use App\Contracts\Shipping\TimelineRecorderContract;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Modules\Returns\Enums\ReturnReason;
use Modules\Shipping\Entities\Shipment;
use Modules\Shipping\Enums\ShipmentStatus;

class ShipmentStatusOperationsService
{
    public function __construct(
        protected ShipmentServiceContract $shipments,
        protected ReturnServiceContract $returns,
        protected TimelineRecorderContract $timeline,
    ) {
    }

    public function markDelivered(Shipment $shipment, ?int $userId = null, ?string $note = null): Shipment
    {
        $this->assertCanMarkDelivered();

        if (! in_array($shipment->status, $this->deliverableStatuses(), true)) {
            throw new \InvalidArgumentException(__('delivery.errors.cannot_mark_delivered'));
        }

        return $this->shipments->transitionStatus(
            $shipment,
            ShipmentStatus::Delivered->value,
            $userId,
            $note ?: __('delivery.timeline.marked_delivered')
        );
    }

    public function registerReturnFromCourier(Shipment $shipment, string $reason, ?string $note = null, ?int $userId = null): Shipment
    {
        $this->assertCanMarkDelivered();

        $returnCase = $this->returns->registerReturn(
            $shipment,
            $reason,
            $note,
            ReturnReason::shipmentStatusFor($reason),
            $userId
        );

        return $returnCase->shipment->fresh();
    }

    public function revertHandoff(Shipment $shipment, ?int $userId = null, ?string $note = null): Shipment
    {
        abort_unless(Gate::allows('delivery_order_revert_handoff'), 403);

        if ($shipment->status !== ShipmentStatus::HandedToPartner->value) {
            throw new \InvalidArgumentException(__('delivery.errors.cannot_revert_handoff'));
        }

        return DB::transaction(function () use ($shipment, $userId, $note) {
            $shipment->update([
                'shipping_partner_id'   => null,
                'deliver_man_id'        => null,
                'assigned_by_user_id'   => null,
                'handed_to_partner_at'  => null,
                'received_by_partner_at' => null,
            ]);

            return $this->shipments->transitionStatus(
                $shipment->fresh(),
                ShipmentStatus::Pending->value,
                $userId,
                $note ?: __('delivery.timeline.revert_handoff')
            );
        });
    }

    public function cancelDelivered(Shipment $shipment, ?int $userId = null, ?string $note = null): Shipment
    {
        abort_unless(Gate::allows('delivery_order_cancel_delivered'), 403);

        if ($shipment->status !== ShipmentStatus::Delivered->value) {
            throw new \InvalidArgumentException(__('delivery.errors.not_delivered'));
        }

        $shipment->update(['delivered_at' => null]);

        return $this->shipments->transitionStatus(
            $shipment->fresh(),
            ShipmentStatus::OutWithCourier->value,
            $userId,
            $note ?: __('delivery.timeline.cancel_delivered')
        );
    }

    public function cancelReturn(Shipment $shipment, ?int $userId = null, ?string $note = null): Shipment
    {
        abort_unless(Gate::allows('delivery_order_cancel_return'), 403);

        if (! in_array($shipment->status, [ShipmentStatus::Returned->value, ShipmentStatus::Refused->value], true)) {
            throw new \InvalidArgumentException(__('delivery.errors.not_return'));
        }

        $shipment->update([
            'return_reason' => null,
            'return_note'   => null,
            'returned_at'   => null,
        ]);

        return $this->shipments->transitionStatus(
            $shipment->fresh(),
            ShipmentStatus::OutWithCourier->value,
            $userId,
            $note ?: __('delivery.timeline.cancel_return')
        );
    }

    /** @return list<string> */
    protected function deliverableStatuses(): array
    {
        return [
            ShipmentStatus::HandedToPartner->value,
            ShipmentStatus::ReceivedAtWarehouse->value,
            ShipmentStatus::OutWithCourier->value,
            ShipmentStatus::CustomerUnavailable->value,
            ShipmentStatus::Postponed->value,
            ShipmentStatus::Retry->value,
        ];
    }

    protected function assertCanMarkDelivered(): void
    {
        abort_unless(
            Gate::allows('delivery_order_mark_delivered') || Gate::allows('delivery_order_edit'),
            403
        );
    }
}
