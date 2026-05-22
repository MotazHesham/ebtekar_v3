<?php

namespace Modules\Shipping\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Modules\Shipping\Entities\ShippingPartner;
use Modules\Shipping\Enums\ShipmentStatus;

class ShippingPartnerApiController extends Controller
{
    public function dashboard()
    {
        $partner = ShippingPartner::where('user_id', auth()->id())->firstOrFail();

        return response()->json([
            'partner' => ['uuid' => $partner->uuid, 'name' => $partner->name],
            'stats'   => [
                'today_received'  => $partner->shipments()->whereDate('handed_to_partner_at', today())->count(),
                'today_delivered' => $partner->shipments()->whereDate('delivered_at', today())->count(),
                'on_delivery'     => $partner->shipments()->where('status', ShipmentStatus::OutWithCourier->value)->count(),
            ],
        ]);
    }
}
