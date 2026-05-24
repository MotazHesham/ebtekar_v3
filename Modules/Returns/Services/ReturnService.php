<?php

namespace Modules\Returns\Services;

use App\Contracts\Shipping\ReturnServiceContract;
use App\Contracts\Shipping\ShipmentServiceContract;
use App\Contracts\Shipping\TimelineRecorderContract;
use Illuminate\Support\Facades\DB;
use Modules\Returns\Entities\ReturnCase;
use Modules\Returns\Enums\ReturnCaseStatus;
use Modules\Returns\Enums\ReturnReason;
use Modules\Returns\Events\ShipmentReturned;
use Modules\Returns\Repositories\ReturnCaseRepository;
use Modules\Shipping\Entities\Shipment;
use Modules\Shipping\Enums\ShipmentStatus;

class ReturnService implements ReturnServiceContract
{
    /** @var string[] */
    protected array $returnableShipmentStatuses = [
        ShipmentStatus::OutWithCourier->value,
        ShipmentStatus::CustomerUnavailable->value,
        ShipmentStatus::Postponed->value,
        ShipmentStatus::Retry->value,
        ShipmentStatus::Delivered->value,
    ];

    public function __construct(
        protected ReturnCaseRepository $returnCases,
        protected ShipmentServiceContract $shipments,
        protected TimelineRecorderContract $timeline,
    ) {
    }

    public function registerReturn(
        Shipment|int $shipment,
        string $reason,
        ?string $note = null,
        string $shipmentStatus = 'returned',
        ?int $userId = null,
    ): ReturnCase {
        $userId   = $userId ?: auth()->id();
        $shipment = $shipment instanceof Shipment
            ? $shipment
            : $this->returnCases->findShipmentOrFail((int) $shipment);

        if (! in_array($shipment->status, $this->returnableShipmentStatuses, true)) {
            throw new \InvalidArgumentException(__('returns::messages.invalid_shipment_status'));
        }

        if (! in_array($shipmentStatus, [ShipmentStatus::Returned->value, ShipmentStatus::Refused->value], true)) {
            $shipmentStatus = ReturnReason::shipmentStatusFor($reason);
        }

        $openCase = $this->returnCases->openCaseForShipment($shipment->id);
        if ($openCase) {
            throw new \InvalidArgumentException(__('returns::messages.open_case_exists'));
        }

        return DB::transaction(function () use ($shipment, $reason, $note, $shipmentStatus, $userId) {
            $returnCase = ReturnCase::create([
                'delivery_order_id'   => $shipment->id,
                'deliver_man_id'      => $shipment->deliver_man_id,
                'shipping_partner_id' => $shipment->shipping_partner_id,
                'created_by_user_id'  => $userId,
                'reason'              => $reason,
                'note'                => $note,
                'shipment_status'     => $shipmentStatus,
                'status'              => ReturnCaseStatus::Open->value,
            ]);

            $shipment = $this->shipments->transitionStatus(
                $shipment,
                $shipmentStatus,
                $userId,
                __('returns::messages.return_timeline', ['reason' => ReturnReason::tryFrom($reason)?->label() ?? $reason])
            );

            $shipment->update([
                'return_reason' => $reason,
                'return_note'   => $note,
            ]);

            ShipmentReturned::dispatch($shipment->fresh(), $returnCase, $userId);

            return $returnCase->fresh(['shipment', 'courier.user', 'shippingPartner']);
        });
    }

    public function markWarehouseReceived(int $returnCaseId, ?int $userId = null): ReturnCase
    {
        $userId = $userId ?: auth()->id();
        $case   = ReturnCase::with('shipment')->findOrFail($returnCaseId);

        if ($case->status !== ReturnCaseStatus::Open->value) {
            throw new \InvalidArgumentException(__('returns::messages.invalid_case_status'));
        }

        $case->update([
            'status'                => ReturnCaseStatus::WarehouseReceived->value,
            'warehouse_received_at' => now(),
        ]);

        if ($case->shipment) {
            $case->shipment->update(['returned_to_warehouse_at' => now()]);
            $this->timeline->recordNote(
                $case->shipment->id,
                __('returns::messages.warehouse_received_timeline'),
                $userId
            );
        }

        return $case->fresh(['shipment', 'courier.user']);
    }

    public function closeReturn(int $returnCaseId, ?int $userId = null): ReturnCase
    {
        $case = ReturnCase::findOrFail($returnCaseId);

        if (! in_array($case->status, [
            ReturnCaseStatus::Open->value,
            ReturnCaseStatus::WarehouseReceived->value,
        ], true)) {
            throw new \InvalidArgumentException(__('returns::messages.invalid_case_status'));
        }

        $case->update([
            'status'    => ReturnCaseStatus::Closed->value,
            'closed_at' => now(),
        ]);

        return $case->fresh(['shipment', 'media']);
    }

    public function updateCase(int $returnCaseId, array $data, ?int $userId = null): ReturnCase
    {
        $userId = $userId ?: auth()->id();
        $case   = ReturnCase::with('shipment')->findOrFail($returnCaseId);
        $changes = [];

        foreach (['reason', 'note', 'status'] as $field) {
            if (! array_key_exists($field, $data) || $data[$field] === null || $data[$field] === '') {
                continue;
            }
            if ($case->{$field} !== $data[$field]) {
                $changes[$field] = ['old' => $case->{$field}, 'new' => $data[$field]];
            }
        }

        if ($changes === []) {
            return $case;
        }

        return DB::transaction(function () use ($case, $data, $changes, $userId) {
            $payload = [];
            foreach (['reason', 'note', 'status'] as $field) {
                if (array_key_exists($field, $data) && $data[$field] !== null && $data[$field] !== '') {
                    $payload[$field] = $data[$field];
                }
            }
            $case->update($payload);

            if ($case->shipment && isset($changes['reason'])) {
                $case->shipment->update(['return_reason' => $case->reason]);
            }

            $this->logCaseActivity(
                $case,
                __('returns::messages.admin_updated', [
                    'changes' => collect($changes)->map(fn ($c, $f) => "{$f}: {$c['old']} → {$c['new']}")->implode(', '),
                ]),
                $userId
            );

            return $case->fresh(['shipment', 'courier.user', 'shippingPartner']);
        });
    }

    public function deleteCase(int $returnCaseId, ?int $userId = null): void
    {
        $userId = $userId ?: auth()->id();
        $case   = ReturnCase::with('shipment')->findOrFail($returnCaseId);

        DB::transaction(function () use ($case, $userId) {
            if ($case->shipment && in_array($case->shipment->status, [ShipmentStatus::Returned->value, ShipmentStatus::Refused->value], true)) {
                $case->shipment->update([
                    'return_reason' => null,
                    'return_note'   => null,
                    'returned_at'   => null,
                ]);
                $this->shipments->transitionStatus(
                    $case->shipment->fresh(),
                    ShipmentStatus::OutWithCourier->value,
                    $userId,
                    __('returns::messages.admin_deleted_case')
                );
            }

            $this->logCaseActivity($case, __('returns::messages.admin_deleted'), $userId);
            $case->delete();
        });
    }

    public function reopenCase(int $returnCaseId, ?int $userId = null): ReturnCase
    {
        $userId = $userId ?: auth()->id();
        $case   = ReturnCase::with('shipment')->findOrFail($returnCaseId);

        if ($case->status !== ReturnCaseStatus::Closed->value) {
            throw new \InvalidArgumentException(__('returns::messages.cannot_reopen'));
        }

        $oldStatus = $case->status;
        $case->update([
            'status'    => ReturnCaseStatus::Open->value,
            'closed_at' => null,
        ]);

        $this->logCaseActivity(
            $case,
            __('returns::messages.admin_reopened', ['from' => $oldStatus, 'to' => ReturnCaseStatus::Open->value]),
            $userId
        );

        return $case->fresh(['shipment', 'courier.user']);
    }

    protected function logCaseActivity(ReturnCase $case, string $message, ?int $userId): void
    {
        if (! $case->shipment) {
            return;
        }

        $this->timeline->recordNote($case->shipment->id, $message, $userId);
    }
}
