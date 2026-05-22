<?php

namespace Modules\Courier\Services;

use App\Contracts\Shipping\CourierQueryContract;
use Illuminate\Support\Collection;
use Modules\Courier\Entities\Courier;
use Modules\Shipping\Enums\ShipmentStatus;

class CourierQueryService implements CourierQueryContract
{
    public function eligibleForDispatch(?int $shippingPartnerId = null): Collection
    {
        $query = Courier::query()->with('user')->active();

        if ($shippingPartnerId) {
            $query->where(function ($q) use ($shippingPartnerId) {
                $q->whereNull('shipping_partner_id')
                    ->orWhere('shipping_partner_id', $shippingPartnerId);
            });
        }

        return $query->orderBy('id')->get();
    }

    public function activeLoadCounts(?int $shippingPartnerId = null): array
    {
        $query = Courier::query()
            ->active()
            ->withCount([
                'shipments as active_load' => function ($q) use ($shippingPartnerId) {
                    $q->where('status', ShipmentStatus::OutWithCourier->value);
                    if ($shippingPartnerId) {
                        $q->where('shipping_partner_id', $shippingPartnerId);
                    }
                },
            ]);

        if ($shippingPartnerId) {
            $query->where(function ($q) use ($shippingPartnerId) {
                $q->whereNull('shipping_partner_id')
                    ->orWhere('shipping_partner_id', $shippingPartnerId);
            });
        }

        return $query->pluck('active_load', 'id')->map(fn ($c) => (int) $c)->all();
    }
}
