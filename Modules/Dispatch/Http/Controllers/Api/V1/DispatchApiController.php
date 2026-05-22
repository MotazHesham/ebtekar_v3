<?php

namespace Modules\Dispatch\Http\Controllers\Api\V1;

use App\Contracts\Shipping\DispatchAssignmentContract;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Dispatch\Http\Requests\AssignCourierRequest;
use Modules\Dispatch\Http\Requests\AutoAssignCourierRequest;
use Modules\Dispatch\Http\Requests\BulkAssignCourierRequest;

class DispatchApiController extends Controller
{
    public function assign(AssignCourierRequest $request, DispatchAssignmentContract $dispatch)
    {
        $result = $dispatch->assignOne(
            (int) $request->shipment_id,
            (int) $request->courier_id
        );

        return response()->json($result->toArray(), $result->ok ? 200 : 422);
    }

    public function assignBulk(BulkAssignCourierRequest $request, DispatchAssignmentContract $dispatch)
    {
        $batch = $dispatch->assignBulk(
            $request->input('shipment_ids'),
            (int) $request->courier_id
        );

        return response()->json([
            'batch_id'      => $batch->id,
            'type'          => $batch->type,
            'status'        => $batch->status,
            'success_count' => $batch->success_count,
            'failed_count'  => $batch->failed_count,
            'items'         => $batch->items->map(fn ($i) => [
                'shipment_id' => $i->delivery_order_id,
                'courier_id'  => $i->courier_id,
                'result'      => $i->result,
                'message'     => $i->message,
            ]),
        ]);
    }

    public function autoAssign(AutoAssignCourierRequest $request, DispatchAssignmentContract $dispatch)
    {
        $batch = $dispatch->autoAssign(
            $request->input('shipment_ids'),
            auth()->id(),
            $request->input('shipping_partner_id') ? (int) $request->input('shipping_partner_id') : null
        );

        return response()->json([
            'batch_id'      => $batch->id,
            'type'          => $batch->type,
            'status'        => $batch->status,
            'success_count' => $batch->success_count,
            'failed_count'  => $batch->failed_count,
            'items'         => $batch->items->map(fn ($i) => [
                'shipment_id' => $i->delivery_order_id,
                'courier_id'  => $i->courier_id,
                'result'      => $i->result,
                'message'     => $i->message,
            ]),
        ]);
    }
}
