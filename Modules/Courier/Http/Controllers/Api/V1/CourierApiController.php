<?php

namespace Modules\Courier\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Modules\Courier\Entities\Courier;

class CourierApiController extends Controller
{
    public function index()
    {
        return response()->json([
            'data' => Courier::with('user')->active()->get()->map(fn ($c) => [
                'uuid'  => $c->uuid,
                'name'  => $c->user?->name,
                'phone' => $c->user?->phone_number,
                'status'=> $c->status,
            ]),
        ]);
    }

    public function show(Courier $courier)
    {
        $courier->load('user', 'shippingPartner');

        return response()->json([
            'uuid'   => $courier->uuid,
            'name'   => $courier->user?->name,
            'phone'  => $courier->user?->phone_number,
            'status' => $courier->status,
            'partner'=> $courier->shippingPartner?->only(['uuid', 'name']),
        ]);
    }
}
