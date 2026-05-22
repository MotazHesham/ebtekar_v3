<?php

namespace App\Contracts\Shipping;

/**
 * Read-only snapshot from core order for shipment creation/update.
 */
final class OrderSnapshot
{
    public function __construct(
        public readonly string $orderNum,
        public readonly ?string $clientName,
        public readonly ?string $phoneNumber,
        public readonly ?string $governorate,
        public readonly ?string $region,
        public readonly float $codAmount,
        public readonly float $depositAmount,
        public readonly float $remainingCod,
        public readonly float $shippingCost,
        public readonly ?string $paymentStatus,
        public readonly ?string $clientNote,
        public readonly ?string $productsSummary,
    ) {
    }
}
