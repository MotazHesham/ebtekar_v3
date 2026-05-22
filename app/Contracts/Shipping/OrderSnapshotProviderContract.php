<?php

namespace App\Contracts\Shipping;

interface OrderSnapshotProviderContract
{
    public function resolveByBarcode(string $barcode): ?OrderReference;

    /**
     * Resolve from barcode (O-/S-/C-) or plain order number (QR content).
     */
    public function resolveByScanCode(string $code): ?OrderReference;

    public function snapshot(OrderReference $reference): ?OrderSnapshot;

    public function syncDeliveryStatus(OrderReference $reference, string $shipmentStatus): void;
}
