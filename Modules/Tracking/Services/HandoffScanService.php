<?php

namespace Modules\Tracking\Services;

use App\Contracts\Shipping\OrderSnapshotProviderContract;
use App\Contracts\Shipping\ShipmentServiceContract;
use Illuminate\Support\Facades\Request;
use Modules\Shipping\Entities\ShippingPartner;
use Modules\Tracking\DTO\ScanResponse;
use Modules\Tracking\Entities\TrackingScan;
use Modules\Tracking\Enums\ScanResult;
use Modules\Tracking\Enums\ScanType;
use Modules\Tracking\Events\ScanHandoffCompleted;
use Modules\Tracking\Repositories\TrackingScanRepository;

class HandoffScanService
{
    public function __construct(
        protected OrderSnapshotProviderContract $orderSnapshots,
        protected ShipmentServiceContract $shipments,
        protected TrackingScanRepository $scans,
    ) {
    }

    public function process(string $barcode, int $shippingPartnerId, ?int $userId = null): ScanResponse
    {
        $userId = $userId ?: auth()->id();

        if (! ShippingPartner::where('id', $shippingPartnerId)->where('is_active', true)->exists()) {
            return $this->fail($barcode, ScanResult::Error, __('tracking::scan.invalid_partner'), $userId);
        }

        $reference = $this->orderSnapshots->resolveByScanCode($barcode);
        if (! $reference) {
            return $this->fail($barcode, ScanResult::Missing, __('tracking::scan.order_not_found', ['code' => $barcode]), $userId);
        }

        $shipment = $this->shipments->createFromOrderReference($reference, $shippingPartnerId, $userId);

        $lastHandoff = $this->scans->lastHandoffForShipment($shipment->id);
        if ($lastHandoff && $lastHandoff->result === ScanResult::Success->value) {
            return $this->recordAndRespond(
                $shipment->id,
                $shippingPartnerId,
                $userId,
                $barcode,
                ScanType::Handoff,
                ScanResult::Duplicate,
                false,
                "<div class='alert alert-warning'>" . e($shipment->order_num) . ' — ' . __('tracking::scan.duplicate_handoff') . '</div>',
                $shipment->order_num
            );
        }

        if ($shipment->shipping_partner_id && (int) $shipment->shipping_partner_id !== $shippingPartnerId) {
            \Modules\Tracking\Events\ScanMismatchDetected::dispatch(
                $barcode,
                $shippingPartnerId,
                (int) $shipment->shipping_partner_id,
                'already_assigned_other_partner',
                $userId
            );

            return $this->recordAndRespond(
                $shipment->id,
                $shippingPartnerId,
                $userId,
                $barcode,
                ScanType::Handoff,
                ScanResult::Mismatch,
                false,
                "<div class='alert alert-danger'>" . e($shipment->order_num) . ' — ' . __('tracking::scan.wrong_partner') . '</div>',
                $shipment->order_num
            );
        }

        $shipment = $this->shipments->handoffToPartner($shipment, $shippingPartnerId, $userId);
        $shipment->load('shippingPartner');

        ScanHandoffCompleted::dispatch($shipment, $shippingPartnerId, $userId, $barcode);

        return $this->recordAndRespond(
            $shipment->id,
            $shippingPartnerId,
            $userId,
            $barcode,
            ScanType::Handoff,
            ScanResult::Success,
            true,
            "<div class='alert alert-success'>" . e($shipment->order_num) . ' — ' . __('tracking::scan.handoff_ok') . '</div>',
            $shipment->order_num
        );
    }

    protected function fail(string $barcode, ScanResult $result, string $text, ?int $userId): ScanResponse
    {
        TrackingScan::create([
            'user_id'             => $userId,
            'scan_type'           => ScanType::Handoff->value,
            'barcode'             => $barcode,
            'result'              => $result->value,
            'message'             => strip_tags($text),
            'shipping_partner_id' => null,
            'meta'                => ['ip' => Request::ip()],
            'created_at'          => now(),
        ]);

        return new ScanResponse(false, $result, "<div class='alert alert-danger'>{$text}</div>");
    }

    protected function recordAndRespond(
        int $shipmentId,
        int $partnerId,
        ?int $userId,
        string $barcode,
        ScanType $type,
        ScanResult $result,
        bool $ok,
        string $html,
        ?string $orderNum
    ): ScanResponse {
        TrackingScan::create([
            'delivery_order_id'   => $shipmentId,
            'shipping_partner_id' => $partnerId,
            'user_id'             => $userId,
            'scan_type'           => $type->value,
            'barcode'             => $barcode,
            'result'              => $result->value,
            'message'             => strip_tags($html),
            'meta'                => ['ip' => Request::ip(), 'agent' => Request::userAgent()],
            'created_at'          => now(),
        ]);

        return new ScanResponse($ok, $result, $html, $shipmentId, $orderNum);
    }
}
