<?php

namespace App\Contracts\Shipping;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Modules\Settlement\Entities\Settlement;

interface SettlementServiceContract
{
    /**
     * @return array{count: int, expected_amount: float, shipment_ids: int[]}
     */
    public function preview(int $courierId, ?string $date = null, bool $includeAllUnsettled = false): array;

    public function openSettlement(int $courierId, ?string $date = null, bool $includeAllUnsettled = false, ?int $userId = null): Settlement;

    public function confirmSettlement(int $settlementId, float $collectedAmount, ?string $notes = null, ?int $userId = null): Settlement;

    public function eligibleShipments(int $courierId, ?string $date = null, bool $includeAllUnsettled = false): Collection;
}
