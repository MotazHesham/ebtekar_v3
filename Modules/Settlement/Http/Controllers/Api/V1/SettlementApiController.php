<?php

namespace Modules\Settlement\Http\Controllers\Api\V1;

use App\Contracts\Shipping\SettlementServiceContract;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Settlement\Entities\Settlement;
use Modules\Settlement\Http\Requests\ConfirmSettlementRequest;
use Modules\Settlement\Http\Requests\OpenSettlementRequest;

class SettlementApiController extends Controller
{
    public function previewToday(int $courierId, Request $request, SettlementServiceContract $settlements)
    {
        $date = $request->input('date', now()->toDateString());

        return response()->json(
            $settlements->preview($courierId, $date, $request->boolean('include_all_unsettled'))
        );
    }

    public function open(OpenSettlementRequest $request, SettlementServiceContract $settlements)
    {
        $settlement = $settlements->openSettlement(
            (int) $request->courier_id,
            $request->settlement_date,
            $request->boolean('include_all_unsettled')
        );

        return response()->json($this->formatSettlement($settlement), 201);
    }

    public function show(Settlement $settlement)
    {
        $settlement->load(['lines.shipment', 'courier.user']);

        return response()->json($this->formatSettlement($settlement));
    }

    public function confirm(ConfirmSettlementRequest $request, Settlement $settlement, SettlementServiceContract $settlements)
    {
        try {
            $updated = $settlements->confirmSettlement(
                $settlement->id,
                (float) $request->collected_amount,
                $request->notes
            );
        } catch (\InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json($this->formatSettlement($updated));
    }

    protected function formatSettlement(Settlement $settlement): array
    {
        return [
            'id'                => $settlement->id,
            'courier_id'        => $settlement->deliver_man_id,
            'settlement_date'   => $settlement->settlement_date?->toDateString(),
            'status'            => $settlement->status,
            'expected_amount'   => (float) $settlement->expected_amount,
            'collected_amount'  => (float) $settlement->collected_amount,
            'difference_amount' => (float) $settlement->difference_amount,
            'notes'             => $settlement->notes,
            'lines'             => $settlement->lines?->map(fn ($line) => [
                'shipment_id'     => $line->delivery_order_id,
                'order_num'       => $line->shipment?->order_num,
                'expected_amount' => (float) $line->expected_amount,
                'status'          => $line->status,
            ]) ?? [],
        ];
    }
}
