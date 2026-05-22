<?php

namespace Modules\Tracking\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Modules\Shipping\Entities\ShippingPartner;
use Modules\Shipping\Support\ShippingTables as ST;
use Modules\Tracking\Services\ReceiveScanService;
use Symfony\Component\HttpFoundation\Response;

class ScanWebController extends Controller
{
    public function receiveScanner()
    {
        abort_if(Gate::denies('delivery_scan_receive'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $partners = null;
        if (auth()->user()?->user_type !== 'shipping_partner') {
            $partners = ShippingPartner::where('is_active', true)->orderBy('name')->get();
        }

        $manualInput = app()->environment('local');

        return view('tracking::admin.receive_scanner', compact('partners', 'manualInput'));
    }

    public function receiveOutput(Request $request, ReceiveScanService $receiveScan)
    {
        abort_if(Gate::denies('delivery_scan_receive'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'code'                => ['required', 'string'],
            'shipping_partner_id' => ['nullable', 'integer', ST::exists(ST::SHIPPING_PARTNERS)],
        ]);

        return response()->json(
            $receiveScan->process(
                $request->input('code'),
                $request->input('shipping_partner_id') ? (int) $request->input('shipping_partner_id') : null
            )->toArray()
        );
    }
}
