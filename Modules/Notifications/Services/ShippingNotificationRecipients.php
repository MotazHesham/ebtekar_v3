<?php

namespace Modules\Notifications\Services;

use App\Models\User;
use Illuminate\Support\Collection;
use Modules\Courier\Entities\Courier;
use Modules\Shipping\Entities\Shipment;
use Modules\Shipping\Entities\ShippingPartner;

class ShippingNotificationRecipients
{
    /**
     * @return Collection<int, User>
     */
    public function forShipment(Shipment $shipment, bool $includeOps = true): Collection
    {
        $users = collect();

        if ($shipment->deliver_man_id) {
            $courierUser = Courier::with('user')->find($shipment->deliver_man_id)?->user;
            if ($courierUser) {
                $users->push($courierUser);
            }
        }

        if ($shipment->shipping_partner_id) {
            $partnerUser = ShippingPartner::with('user')->find($shipment->shipping_partner_id)?->user;
            if ($partnerUser) {
                $users->push($partnerUser);
            }
        }

        if ($includeOps) {
            $users = $users->merge($this->operationsStaff());
        }

        return $users->filter()->unique('id')->values();
    }

    /**
     * @return Collection<int, User>
     */
    public function operationsStaff(): Collection
    {
        return User::query()
            ->whereNotNull('device_token')
            ->where(function ($q) {
                $q->where('is_admin', 1)
                    ->orWhere('user_type', 'dispatcher')
                    ->orWhereHas('roles.permissions', function ($p) {
                        $p->whereIn('title', [
                            'delivery_managment_access',
                            'delivery_order_access',
                            'delivery_assign_courier',
                        ]);
                    });
            })
            ->get();
    }

    /**
     * @return string[]
     */
    public function deviceTokensFor(Collection $users): array
    {
        return $users->pluck('device_token')->filter()->unique()->values()->all();
    }
}
