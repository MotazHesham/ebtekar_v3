<?php

namespace Modules\Tracking\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Shipping\Support\ShippingTables as ST;
use Modules\Tracking\Services\HandoffScanService;
use Modules\Tracking\Services\ReceiveScanService;

class ScanApiController extends Controller
{
    public function handoff(Request $request, HandoffScanService $handoff)
    {
        $request->validate([
            'code'                => ['required', 'string'],
            'shipping_partner_id' => ['required', 'integer', ST::exists(ST::SHIPPING_PARTNERS)],
        ]);

        return response()->json(
            $handoff->process($request->input('code'), (int) $request->input('shipping_partner_id'))->toArray()
        );
    }

    public function receive(Request $request, ReceiveScanService $receive)
    {
        $request->validate([
            'code'                => ['required', 'string'],
            'shipping_partner_id' => ['nullable', 'integer', ST::exists(ST::SHIPPING_PARTNERS)],
        ]);

        return response()->json(
            $receive->process(
                $request->input('code'),
                $request->input('shipping_partner_id') ? (int) $request->input('shipping_partner_id') : null
            )->toArray()
        );
    }
}
