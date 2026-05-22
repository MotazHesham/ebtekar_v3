<?php

namespace Modules\Shipping\Services;

use App\Contracts\Shipping\OrderReference;
use App\Contracts\Shipping\OrderSnapshotProviderContract;
use App\Contracts\Shipping\ShipmentServiceContract;
use App\Contracts\Shipping\TimelineRecorderContract;
use Carbon\Carbon;
use Illuminate\Support\Facades\Request;
use Modules\Courier\Entities\Courier;
use Modules\Shipping\Entities\Shipment;
use Modules\Shipping\Enums\ShipmentStatus;
use Modules\Shipping\Events\ShipmentAssignedToCourier;
use Modules\Shipping\Events\ShipmentCreated;
use Modules\Shipping\Events\ShipmentStatusChanged;
use Modules\Shipping\Repositories\ShipmentRepository;

class ShipmentService implements ShipmentServiceContract
{
    public function __construct(
        protected ShipmentRepository $shipments,
        protected OrderSnapshotProviderContract $orderSnapshots,
        protected TimelineRecorderContract $timeline,
    ) {
    }

    public function createFromOrderReference(OrderReference $reference, ?int $shippingPartnerId = null, ?int $userId = null): Shipment
    {
        $existing = $this->shipments->findByOrderable($reference->morphType(), $reference->id);
        if ($existing) {
            return $existing;
        }

        $snapshot = $this->orderSnapshots->snapshot($reference);
        if (! $snapshot) {
            throw new \InvalidArgumentException('Order reference not found.');
        }

        $shipment = Shipment::create([
            'orderable_type'      => $reference->morphType(),
            'orderable_id'        => $reference->id,
            'shipping_partner_id' => $shippingPartnerId,
            'status'              => ShipmentStatus::Pending->value,
            'last_status_at'      => now(),
            'order_num'           => $snapshot->orderNum,
            'client_name'         => $snapshot->clientName,
            'phone_number'        => $snapshot->phoneNumber,
            'governorate'         => $snapshot->governorate,
            'region'              => $snapshot->region,
            'cod_amount'          => $snapshot->codAmount,
            'deposit_amount'      => $snapshot->depositAmount,
            'remaining_cod'       => $snapshot->remainingCod,
            'shipping_cost'       => $snapshot->shippingCost,
            'payment_status'      => $snapshot->paymentStatus,
        ]);

        $this->timeline->recordCreated($shipment->id, $userId, __('delivery.timeline.created'));
        ShipmentCreated::dispatch($shipment, $userId);

        return $shipment;
    }

    public function handoffToPartner(Shipment $shipment, int $shippingPartnerId, ?int $userId = null): Shipment
    {
        $shipment->shipping_partner_id = $shippingPartnerId;
        $shipment->save();

        return $this->transitionStatus($shipment, ShipmentStatus::HandedToPartner->value, $userId);
    }

    public function transitionStatus(Shipment $shipment, string $newStatus, ?int $userId = null, ?string $note = null): Shipment
    {
        $oldStatus = $shipment->status;
        $enum      = ShipmentStatus::from($newStatus);
        $now       = Carbon::now();

        $shipment->status         = $newStatus;
        $shipment->last_status_at = $now;

        if ($column = $enum->timestampColumn()) {
            if (! $shipment->{$column}) {
                $shipment->{$column} = $now;
            }
        }

        if (in_array($newStatus, [
            ShipmentStatus::CustomerUnavailable->value,
            ShipmentStatus::Postponed->value,
            ShipmentStatus::Retry->value,
        ], true) && ! $shipment->first_attempt_at) {
            $shipment->first_attempt_at = $now;
        }

        $shipment->save();

        $body = $note ?: __('delivery.timeline.status_changed', [
            'from' => __('delivery_order_status.' . $oldStatus),
            'to'   => __('delivery_order_status.' . $newStatus),
        ]);

        $this->timeline->recordStatusChange($shipment->id, $oldStatus, $newStatus, $userId, $body, [
            'ip'       => Request::ip(),
            'agent'    => Request::userAgent(),
            'timezone' => config('app.timezone'),
        ]);

        ShipmentStatusChanged::dispatch($shipment, $oldStatus, $newStatus, $userId, $note);

        return $shipment->fresh();
    }

    public function assignCourier(Shipment $shipment, int $courierId, ?int $userId = null): Shipment
    {
        $courier = Courier::with('user')->findOrFail($courierId);

        $shipment->deliver_man_id        = $courierId;
        $shipment->assigned_by_user_id   = $userId ?: auth()->id();
        $shipment->save();

        $label = $courier->user?->name ?? '#' . $courierId;
        $this->timeline->recordAssignment($shipment->id, $label, $userId);

        ShipmentAssignedToCourier::dispatch($shipment, $courierId, $label, $userId);

        if ($shipment->status !== ShipmentStatus::OutWithCourier->value) {
            return $this->transitionStatus($shipment, ShipmentStatus::OutWithCourier->value, $userId);
        }

        return $shipment->fresh();
    }
}
