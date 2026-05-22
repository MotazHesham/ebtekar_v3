<?php

namespace Modules\Shipping\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Shipping\Entities\Shipment;
use Modules\Shipping\Http\Resources\ShipmentResource;

class ShipmentApiController extends Controller
{
    public function index(Request $request)
    {
        $shipments = Shipment::query()
            ->forUser()
            ->when($request->status, fn ($q, $s) => $q->where('status', $s))
            ->latest('last_status_at')
            ->paginate($request->integer('per_page', 25));

        return ShipmentResource::collection($shipments);
    }

    public function show(Shipment $shipment)
    {
        $this->authorizeShipment($shipment);
        $shipment->load(['shippingPartner', 'courier.user']);

        return new ShipmentResource($shipment);
    }

    public function updateStatus(Request $request, Shipment $shipment)
    {
        $this->authorizeShipment($shipment);
        $request->validate(['status' => 'required|string']);

        $shipment = app(\App\Contracts\Shipping\ShipmentServiceContract::class)
            ->transitionStatus($shipment, $request->status, auth()->id(), $request->note);

        return new ShipmentResource($shipment);
    }

    protected function authorizeShipment(Shipment $shipment): void
    {
        $scoped = Shipment::query()->forUser()->where('id', $shipment->id)->exists();
        abort_unless($scoped || auth()->user()?->is_admin, 403);
    }
}
