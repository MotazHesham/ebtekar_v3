<?php

namespace App\Contracts\Shipping;

/**
 * Immutable pointer to a core order/receipt — no Eloquent model exposed to modules.
 */
final class OrderReference
{
    public function __construct(
        public readonly string $type,
        public readonly int $id,
        public readonly string $barcodePrefix,
    ) {
    }

    public function morphType(): string
    {
        return match ($this->barcodePrefix) {
            'O'     => 'App\Models\Order',
            'S'     => 'App\Models\ReceiptSocial',
            'C'     => 'App\Models\ReceiptCompany',
            default => $this->type,
        };
    }
}
