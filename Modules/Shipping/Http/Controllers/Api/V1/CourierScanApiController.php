<?php

namespace Modules\Shipping\Http\Controllers\Api\V1;

use App\Contracts\Shipping\OrderSnapshotProviderContract;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Modules\Returns\Enums\ReturnReason;
use Modules\Shipping\Entities\Shipment;
use Modules\Shipping\Http\Resources\ShipmentResource;
use Modules\Shipping\Services\ShipmentStatusOperationsService;

class CourierScanApiController extends Controller
{
    public function __construct(
        protected OrderSnapshotProviderContract $orders,
        protected ShipmentStatusOperationsService $statusOps,
    ) {
    }

    public function lookup(Request $request)
    {
        $request->validate(['code' => ['required', 'string']]);

        $reference = $this->orders->resolveByScanCode($request->code);
        if (! $reference) {
            return response()->json(['message' => __('tracking::scan.order_not_found', ['code' => $request->code])], 404);
        }

        $shipment = Shipment::query()
            ->forUser()
            ->where('orderable_type', $reference->morphType())
            ->where('orderable_id', $reference->id)
            ->with(['shippingPartner', 'courier.user'])
            ->first();

        if (! $shipment) {
            return response()->json(['message' => __('tracking::scan.shipment_not_found', ['code' => $request->code])], 404);
        }

        return new ShipmentResource($shipment);
    }

    public function apply(Request $request)
    {
        $request->validate([
            'code'          => ['required', 'string'],
            'action'        => ['required', Rule::in(['delivered', 'returned'])],
            'return_reason' => ['nullable', Rule::in(array_column(ReturnReason::cases(), 'value'))],
            'note'          => ['nullable', 'string', 'max:1000'],
        ]);

        $reference = $this->orders->resolveByScanCode($request->code);
        abort_unless($reference, 404, __('tracking::scan.order_not_found', ['code' => $request->code]));

        $shipment = Shipment::query()
            ->forUser()
            ->where('orderable_type', $reference->morphType())
            ->where('orderable_id', $reference->id)
            ->firstOrFail();

        try {
            if ($request->action === 'delivered') {
                $shipment = $this->statusOps->markDelivered($shipment, auth()->id(), $request->note);
            } else {
                $shipment = $this->statusOps->registerReturnFromCourier(
                    $shipment,
                    $request->return_reason ?: ReturnReason::CustomerUnavailable->value,
                    $request->note,
                    auth()->id()
                );
            }
        } catch (\InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json([
            'message'  => __('delivery.messages.status_updated'),
            'shipment' => new ShipmentResource($shipment->load(['shippingPartner', 'courier.user'])),
        ]);
    }
}
