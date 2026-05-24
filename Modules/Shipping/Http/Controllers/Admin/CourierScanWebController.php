<?php

namespace Modules\Shipping\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Gate;
use Illuminate\Http\Request;
use Modules\Returns\Enums\ReturnReason;
use Modules\Shipping\Http\Controllers\Api\V1\CourierScanApiController;
use Symfony\Component\HttpFoundation\Response;

class CourierScanWebController extends Controller
{
    public function __construct(protected CourierScanApiController $scanApi)
    {
    }

    public function index()
    {
        abort_unless(
            Gate::allows('delivery_order_mark_delivered') || Gate::allows('delivery_order_edit'),
            Response::HTTP_FORBIDDEN,
            '403 Forbidden'
        );

        return view('shipping::admin.courier.scan', [
            'returnReasons' => ReturnReason::labels(),
            'isLocal'       => app()->environment('local'),
        ]);
    }

    public function lookup(Request $request)
    {
        $this->assertScanAccess();

        return $this->scanApi->lookup($request);
    }

    public function apply(Request $request)
    {
        $this->assertScanAccess();

        return $this->scanApi->apply($request);
    }

    protected function assertScanAccess(): void
    {
        abort_unless(
            Gate::allows('delivery_order_mark_delivered') || Gate::allows('delivery_order_edit'),
            Response::HTTP_FORBIDDEN,
            '403 Forbidden'
        );
    }
}
