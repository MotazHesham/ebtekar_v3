<?php

namespace App\Contracts\Shipping;

use Illuminate\Support\Collection;

interface CourierQueryContract
{
    /**
     * Active couriers eligible for dispatch (optionally scoped to a shipping partner).
     */
    public function eligibleForDispatch(?int $shippingPartnerId = null): Collection;

    /**
     * Active shipment count per courier (out_with_courier), keyed by courier id.
     *
     * @return array<int, int>
     */
    public function activeLoadCounts(?int $shippingPartnerId = null): array;
}
