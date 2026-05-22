<?php

namespace App\Contracts\Shipping;

interface OrderSnapshotProviderContract
{
    public function resolveByBarcode(string $barcode): ?OrderReference;

    public function snapshot(OrderReference $reference): ?OrderSnapshot;

    public function syncDeliveryStatus(OrderReference $reference, string $shipmentStatus): void;
}
