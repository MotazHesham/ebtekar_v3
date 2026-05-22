<?php

namespace Modules\Dispatch\Services;

use App\Contracts\Shipping\CourierQueryContract;
use App\Contracts\Shipping\DispatchAssignmentContract;
use App\Contracts\Shipping\ShipmentServiceContract;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Courier\Entities\Courier;
use Modules\Dispatch\DTO\AssignmentResult;
use Modules\Dispatch\Entities\DispatchBatch;
use Modules\Dispatch\Entities\DispatchBatchItem;
use Modules\Dispatch\Enums\BatchItemResult;
use Modules\Dispatch\Enums\BatchType;
use Modules\Shipping\Entities\Shipment;
use Modules\Shipping\Enums\ShipmentStatus;
use Modules\Shipping\Repositories\ShipmentRepository;

class DispatchAssignmentService implements DispatchAssignmentContract
{
    public function __construct(
        protected ShipmentRepository $shipments,
        protected ShipmentServiceContract $shipmentService,
        protected CourierQueryContract $couriers,
    ) {
    }

    public function assignOne(Shipment|int $shipment, int $courierId, ?int $userId = null): AssignmentResult
    {
        $shipment = $shipment instanceof Shipment ? $shipment : $this->shipments->query()->findOrFail($shipment);
        $userId   = $userId ?: auth()->id();

        $validation = $this->validateAssignment($shipment, $courierId);
        if (! $validation['ok']) {
            return new AssignmentResult(false, BatchItemResult::Error, $validation['message'], $shipment);
        }

        try {
            $updated = $this->shipmentService->assignCourier($shipment, $courierId, $userId);

            return new AssignmentResult(
                true,
                BatchItemResult::Success,
                __('dispatch::messages.assigned', ['num' => $updated->order_num]),
                $updated,
                $courierId
            );
        } catch (\Throwable $e) {
            return new AssignmentResult(false, BatchItemResult::Error, $this->shortMessage($e->getMessage()), $shipment);
        }
    }

    public function assignBulk(array $shipmentIds, int $courierId, ?int $userId = null): DispatchBatch
    {
        $userId = $userId ?: auth()->id();
        $ids    = array_values(array_unique(array_map('intval', $shipmentIds)));

        $batch = DispatchBatch::create([
            'type'                => BatchType::ManualBulk->value,
            'status'              => 'completed',
            'courier_id'          => $courierId,
            'created_by_user_id'  => $userId,
            'total_count'         => count($ids),
            'success_count'       => 0,
            'failed_count'        => 0,
        ]);

        return $this->processBatch($batch, $ids, fn (Shipment $s) => $courierId);
    }

    public function autoAssign(array $shipmentIds, ?int $userId = null, ?int $shippingPartnerId = null): DispatchBatch
    {
        $userId = $userId ?: auth()->id();
        $ids    = array_values(array_unique(array_map('intval', $shipmentIds)));

        $batch = DispatchBatch::create([
            'type'                => BatchType::Auto->value,
            'status'              => 'completed',
            'shipping_partner_id' => $shippingPartnerId,
            'created_by_user_id'  => $userId,
            'total_count'         => count($ids),
            'success_count'       => 0,
            'failed_count'        => 0,
        ]);

        $loads = $this->couriers->activeLoadCounts($shippingPartnerId);

        return $this->processBatch($batch, $ids, function (Shipment $shipment) use (&$loads) {
            $partnerId = $shipment->shipping_partner_id;
            $eligible  = $this->couriers->eligibleForDispatch($partnerId ? (int) $partnerId : null);

            if ($eligible->isEmpty()) {
                return null;
            }

            $courier = $eligible
                ->sortBy(fn ($c) => [$loads[$c->id] ?? 0, $c->id])
                ->first();

            if ($courier->capacity_max && ($loads[$courier->id] ?? 0) >= $courier->capacity_max) {
                return null;
            }

            $loads[$courier->id] = ($loads[$courier->id] ?? 0) + 1;

            return (int) $courier->id;
        });
    }

    /**
     * @param  callable(Shipment): ?int  $courierResolver
     */
    protected function processBatch(DispatchBatch $batch, array $shipmentIds, callable $courierResolver): DispatchBatch
    {
        $success = 0;
        $failed  = 0;

        DB::transaction(function () use ($batch, $shipmentIds, $courierResolver, &$success, &$failed) {
            foreach ($shipmentIds as $shipmentId) {
                $shipment = $this->shipments->query()->find($shipmentId);

                if (! $shipment) {
                    $this->recordItem($batch, $shipmentId, null, BatchItemResult::Error, __('dispatch::messages.shipment_not_found'));
                    $failed++;
                    continue;
                }

                $courierId = $courierResolver($shipment);

                if (! $courierId) {
                    $this->recordItem($batch, $shipment->id, null, BatchItemResult::Skipped, __('dispatch::messages.no_courier_available'));
                    $failed++;
                    continue;
                }

                $result = $this->assignOne($shipment, $courierId, $batch->created_by_user_id);

                $this->recordItem($batch, $shipment->id, $courierId, $result->result, $result->message);

                if ($result->ok) {
                    $success++;
                } else {
                    $failed++;
                }
            }
        });

        $batch->update([
            'success_count' => $success,
            'failed_count'  => $failed,
            'status'        => $failed > 0 && $success > 0 ? 'partial' : ($success > 0 ? 'completed' : 'failed'),
        ]);

        return $batch->fresh(['items']);
    }

    protected function recordItem(DispatchBatch $batch, int $shipmentId, ?int $courierId, BatchItemResult $result, string $message): void
    {
        DispatchBatchItem::create([
            'dispatch_batch_id' => $batch->id,
            'delivery_order_id' => $shipmentId,
            'courier_id'        => $courierId,
            'result'            => $result->value,
            'message'           => $this->shortMessage($message),
            'created_at'        => now(),
        ]);
    }

    protected function shortMessage(string $message): string
    {
        return Str::limit(strip_tags($message), 500);
    }

    /**
     * @return array{ok: bool, message: string}
     */
    protected function validateAssignment(Shipment $shipment, int $courierId): array
    {
        if ($shipment->status !== ShipmentStatus::ReceivedAtWarehouse->value) {
            return [
                'ok'      => false,
                'message' => __('dispatch::messages.invalid_status', ['num' => $shipment->order_num]),
            ];
        }

        $courier = Courier::with('user')->active()->find($courierId);
        if (! $courier) {
            return ['ok' => false, 'message' => __('dispatch::messages.courier_inactive')];
        }

        if ($shipment->shipping_partner_id && $courier->shipping_partner_id
            && (int) $courier->shipping_partner_id !== (int) $shipment->shipping_partner_id) {
            return ['ok' => false, 'message' => __('dispatch::messages.partner_mismatch')];
        }

        if ($courier->capacity_max) {
            $load = $courier->shipments()
                ->where('status', ShipmentStatus::OutWithCourier->value)
                ->count();

            if ($load >= $courier->capacity_max) {
                return ['ok' => false, 'message' => __('dispatch::messages.courier_at_capacity')];
            }
        }

        return ['ok' => true, 'message' => ''];
    }
}
