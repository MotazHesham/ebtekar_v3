<?php

namespace Modules\Returns\Http\Controllers\Api\V1;

use App\Contracts\Shipping\ReturnServiceContract;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Modules\Returns\Entities\ReturnCase;
use Modules\Returns\Enums\ReturnReason;
use Modules\Returns\Http\Requests\StoreReturnRequest;
use Modules\Returns\Http\Requests\UploadReturnAttachmentRequest;
use Modules\Shipping\Entities\Shipment;

class ReturnApiController extends Controller
{
    public function store(StoreReturnRequest $request, ReturnServiceContract $returns)
    {
        try {
            $case = $returns->registerReturn(
                (int) $request->shipment_id,
                $request->reason,
                $request->note,
                $request->input('shipment_status', ReturnReason::shipmentStatusFor($request->reason))
            );
        } catch (\InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json($this->formatCase($case), 201);
    }

    public function storeByShipmentUuid(string $uuid, Request $request, ReturnServiceContract $returns)
    {
        abort_unless(Gate::allows('delivery_return_access'), 403);

        $validated = $request->validate([
            'reason'          => ['required', Rule::in(array_column(ReturnReason::cases(), 'value'))],
            'note'            => ['nullable', 'string', 'max:2000'],
            'shipment_status' => ['nullable', Rule::in(['returned', 'refused'])],
        ]);

        $shipment = Shipment::where('uuid', $uuid)->firstOrFail();

        try {
            $case = $returns->registerReturn(
                $shipment->id,
                $validated['reason'],
                $validated['note'] ?? null,
                $validated['shipment_status'] ?? ReturnReason::shipmentStatusFor($validated['reason'])
            );
        } catch (\InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json($this->formatCase($case), 201);
    }

    public function show(ReturnCase $return)
    {
        $return->load(['shipment', 'courier.user', 'media']);

        return response()->json($this->formatCase($return));
    }

    public function upload(UploadReturnAttachmentRequest $request, ReturnCase $return)
    {
        foreach ($request->file('attachments', []) as $file) {
            $return->addMedia($file)->toMediaCollection('return_proofs');
        }

        return response()->json(['message' => __('returns::messages.attachment_uploaded')]);
    }

    public function markWarehouse(ReturnCase $return, ReturnServiceContract $returns)
    {
        try {
            $updated = $returns->markWarehouseReceived($return->id);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json($this->formatCase($updated));
    }

    public function close(ReturnCase $return, ReturnServiceContract $returns)
    {
        try {
            $updated = $returns->closeReturn($return->id);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json($this->formatCase($updated));
    }

    protected function formatCase(ReturnCase $case): array
    {
        return [
            'id'           => $case->id,
            'uuid'         => $case->uuid,
            'status'       => $case->status,
            'reason'       => $case->reason,
            'reason_label' => $case->reason_label,
            'note'         => $case->note,
            'shipment'     => $case->shipment ? [
                'id'        => $case->shipment->id,
                'uuid'      => $case->shipment->uuid,
                'order_num' => $case->shipment->order_num,
                'status'    => $case->shipment->status,
            ] : null,
            'attachments'  => $case->media?->map(fn ($m) => [
                'id'        => $m->id,
                'url'       => $m->getUrl(),
                'thumb'     => $m->getUrl('thumb'),
            ]) ?? [],
        ];
    }
}
