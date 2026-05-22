<?php

namespace Modules\Tracking\Services;

use App\Contracts\Shipping\OrderSnapshotProviderContract;
use App\Contracts\Shipping\ShipmentServiceContract;
use App\Contracts\Shipping\TimelineRecorderContract;
use Illuminate\Support\Facades\Request;
use Modules\Shipping\Entities\Shipment;
use Modules\Shipping\Entities\ShippingPartner;
use Modules\Shipping\Enums\ShipmentStatus;
use Modules\Shipping\Repositories\ShipmentRepository;
use Modules\Tracking\DTO\ScanResponse;
use Modules\Tracking\Entities\TrackingScan;
use Modules\Tracking\Enums\ScanResult;
use Modules\Tracking\Enums\ScanType;
use Modules\Tracking\Events\ScanMismatchDetected;

class ReceiveScanService
{
    public function __construct(
        protected OrderSnapshotProviderContract $orderSnapshots,
        protected ShipmentRepository $shipmentRepository,
        protected ShipmentServiceContract $shipments,
        protected TimelineRecorderContract $timeline,
    ) {
    }

    public function process(string $barcode, ?int $shippingPartnerId = null, ?int $userId = null): ScanResponse
    {
        $userId = $userId ?: auth()->id();
        $partnerId = $this->resolvePartnerId($shippingPartnerId, $userId);

        if (! $partnerId) {
            return $this->fail($barcode, ScanResult::Error, __('tracking::scan.partner_required'), $userId, null);
        }

        $reference = $this->orderSnapshots->resolveByScanCode($barcode);
        if (! $reference) {
            return $this->fail($barcode, ScanResult::Missing, __('tracking::scan.order_not_found', ['code' => $barcode]), $userId, $partnerId);
        }

        $shipment = $this->shipmentRepository->findByOrderable($reference->morphType(), $reference->id);
        if (! $shipment) {
            return $this->fail($barcode, ScanResult::Missing, __('tracking::scan.shipment_not_found', ['code' => $barcode]), $userId, $partnerId);
        }

        if (! $shipment->shipping_partner_id) {
            return $this->fail($barcode, ScanResult::Error, __('tracking::scan.not_handed_off', ['num' => $shipment->order_num]), $userId, $partnerId, $shipment);
        }

        if ((int) $shipment->shipping_partner_id !== (int) $partnerId) {
            ScanMismatchDetected::dispatch($barcode, $partnerId, (int) $shipment->shipping_partner_id, 'receive_wrong_partner', $userId);

            return $this->record($shipment, $partnerId, $userId, $barcode, ScanResult::Mismatch, false,
                "<div class='alert alert-danger'>" . e($shipment->order_num) . ' — ' . __('tracking::scan.wrong_partner') . '</div>');
        }

        if ($shipment->status === ShipmentStatus::ReceivedAtWarehouse->value) {
            return $this->record($shipment, $partnerId, $userId, $barcode, ScanResult::Duplicate, false,
                "<div class='alert alert-warning'>" . e($shipment->order_num) . ' — ' . __('tracking::scan.duplicate_receive') . '</div>');
        }

        if (! in_array($shipment->status, [
            ShipmentStatus::HandedToPartner->value,
            ShipmentStatus::Pending->value,
        ], true)) {
            return $this->record($shipment, $partnerId, $userId, $barcode, ScanResult::Error, false,
                "<div class='alert alert-danger'>" . e($shipment->order_num) . ' — ' . __('tracking::scan.invalid_status_receive') . '</div>');
        }

        $old = $shipment->status;
        $shipment = $this->shipments->transitionStatus($shipment, ShipmentStatus::ReceivedAtWarehouse->value, $userId);

        $this->timeline->recordStatusChange(
            $shipment->id,
            $old,
            ShipmentStatus::ReceivedAtWarehouse->value,
            $userId,
            __('tracking::scan.receive_timeline'),
            ['scan' => ScanType::Receive->value]
        );

        return $this->record($shipment, $partnerId, $userId, $barcode, ScanResult::Success, true,
            "<div class='alert alert-success'>" . e($shipment->order_num) . ' — ' . __('tracking::scan.receive_ok') . '</div>');
    }

    protected function resolvePartnerId(?int $explicit, ?int $userId): ?int
    {
        if ($explicit) {
            return $explicit;
        }

        $user = auth()->user();
        if ($user && $user->user_type === 'shipping_partner') {
            return ShippingPartner::where('user_id', $user->id)->value('id');
        }

        return null;
    }

    protected function fail(string $barcode, ScanResult $result, string $text, ?int $userId, ?int $partnerId, ?Shipment $shipment = null): ScanResponse
    {
        TrackingScan::create([
            'delivery_order_id'   => $shipment?->id,
            'shipping_partner_id' => $partnerId,
            'user_id'             => $userId,
            'scan_type'           => ScanType::Receive->value,
            'barcode'             => $barcode,
            'result'              => $result->value,
            'message'             => strip_tags($text),
            'meta'                => ['ip' => Request::ip()],
            'created_at'          => now(),
        ]);

        return new ScanResponse(false, $result, "<div class='alert alert-danger'>{$text}</div>", $shipment?->id);
    }

    protected function record(
        Shipment $shipment,
        int $partnerId,
        ?int $userId,
        string $barcode,
        ScanResult $result,
        bool $ok,
        string $html
    ): ScanResponse {
        TrackingScan::create([
            'delivery_order_id'   => $shipment->id,
            'shipping_partner_id' => $partnerId,
            'user_id'             => $userId,
            'scan_type'           => ScanType::Receive->value,
            'barcode'             => $barcode,
            'result'              => $result->value,
            'message'             => strip_tags($html),
            'meta'                => ['ip' => Request::ip()],
            'created_at'          => now(),
        ]);

        return new ScanResponse($ok, $result, $html, $shipment->id, $shipment->order_num);
    }
}
