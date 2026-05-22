<?php

namespace Modules\Settlement\Services;

use App\Contracts\Shipping\SettlementServiceContract;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Modules\Courier\Entities\Courier;
use Modules\Settlement\Entities\Settlement;
use Modules\Settlement\Entities\SettlementLine;
use Modules\Settlement\Enums\SettlementStatus;
use Modules\Settlement\Events\CourierSettlementClosed;
use Modules\Settlement\Repositories\SettlementRepository;
use Modules\Shipping\Entities\Shipment;
use Modules\Shipping\Enums\ShipmentStatus;

class SettlementService implements SettlementServiceContract
{
    public function __construct(protected SettlementRepository $settlements)
    {
    }

    public function preview(int $courierId, ?string $date = null, bool $includeAllUnsettled = false): array
    {
        $shipments = $this->eligibleShipments($courierId, $date, $includeAllUnsettled);

        return [
            'count'            => $shipments->count(),
            'expected_amount'  => round((float) $shipments->sum('remaining_cod'), 2),
            'shipment_ids'     => $shipments->pluck('id')->all(),
        ];
    }

    public function eligibleShipments(int $courierId, ?string $date = null, bool $includeAllUnsettled = false): Collection
    {
        $date = $date ? Carbon::parse($date)->toDateString() : now()->toDateString();

        $query = Shipment::query()
            ->where('deliver_man_id', $courierId)
            ->where('status', ShipmentStatus::Delivered->value)
            ->whereNull('settled_at');

        if (! $includeAllUnsettled) {
            $query->whereDate('delivered_at', $date);
        }

        return $query->orderBy('order_num')->get();
    }

    public function openSettlement(int $courierId, ?string $date = null, bool $includeAllUnsettled = false, ?int $userId = null): Settlement
    {
        Courier::active()->findOrFail($courierId);

        $dateStr = $date ? Carbon::parse($date)->toDateString() : now()->toDateString();
        $userId  = $userId ?: auth()->id();

        $existing = $this->settlements->findPendingForCourierAndDate($courierId, $dateStr);

        if ($existing) {
            $this->syncLines($existing, $courierId, $dateStr, $includeAllUnsettled);

            return $existing->fresh(['lines.shipment', 'courier.user']);
        }

        return DB::transaction(function () use ($courierId, $dateStr, $includeAllUnsettled, $userId) {
            $settlement = Settlement::create([
                'deliver_man_id'      => $courierId,
                'settled_by_user_id'  => null,
                'settlement_date'     => $dateStr,
                'expected_amount'     => 0,
                'collected_amount'    => 0,
                'difference_amount'   => 0,
                'status'              => SettlementStatus::Pending->value,
            ]);

            $this->syncLines($settlement, $courierId, $dateStr, $includeAllUnsettled);

            return $settlement->fresh(['lines.shipment', 'courier.user']);
        });
    }

    public function confirmSettlement(int $settlementId, float $collectedAmount, ?string $notes = null, ?int $userId = null): Settlement
    {
        $userId = $userId ?: auth()->id();

        return DB::transaction(function () use ($settlementId, $collectedAmount, $notes, $userId) {
            $settlement = Settlement::with('lines.shipment')->lockForUpdate()->findOrFail($settlementId);

            if ($settlement->status !== SettlementStatus::Pending->value) {
                throw new \InvalidArgumentException(__('settlement::messages.already_closed'));
            }

            if ($settlement->lines->isEmpty()) {
                throw new \InvalidArgumentException(__('settlement::messages.no_lines'));
            }

            $expected = round((float) $settlement->lines->sum('expected_amount'), 2);
            $collected = round($collectedAmount, 2);
            $diff      = round($collected - $expected, 2);

            $settlement->update([
                'expected_amount'    => $expected,
                'collected_amount'   => $collected,
                'difference_amount'  => $diff,
                'status'             => SettlementStatus::Confirmed->value,
                'settled_by_user_id' => $userId,
                'notes'              => $notes,
            ]);

            $now = now();

            foreach ($settlement->lines as $line) {
                $line->update(['status' => 'settled', 'collected_amount' => $line->expected_amount]);

                if ($line->shipment) {
                    $line->shipment->update(['settled_at' => $now]);
                }
            }

            CourierSettlementClosed::dispatch($settlement->fresh(['courier.user', 'lines']), $userId);

            return $settlement->fresh(['courier.user', 'lines.shipment', 'settledBy']);
        });
    }

    protected function syncLines(Settlement $settlement, int $courierId, string $dateStr, bool $includeAllUnsettled): void
    {
        $eligible = $this->eligibleShipments($courierId, $dateStr, $includeAllUnsettled);
        $existingIds = $settlement->lines()->pluck('delivery_order_id')->all();

        foreach ($eligible as $shipment) {
            if (in_array($shipment->id, $existingIds, true)) {
                continue;
            }

            SettlementLine::create([
                'delivery_settlement_id' => $settlement->id,
                'delivery_order_id'      => $shipment->id,
                'expected_amount'        => (float) ($shipment->remaining_cod ?? 0),
                'status'                 => 'pending',
                'created_at'             => now(),
            ]);
        }

        $settlement->update([
            'expected_amount' => round((float) $settlement->lines()->sum('expected_amount'), 2),
        ]);
    }
}
