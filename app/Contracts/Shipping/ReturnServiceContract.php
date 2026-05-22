<?php

namespace App\Contracts\Shipping;

use Modules\Returns\Entities\ReturnCase;
use Modules\Shipping\Entities\Shipment;

interface ReturnServiceContract
{
    public function registerReturn(
        Shipment|int $shipment,
        string $reason,
        ?string $note = null,
        string $shipmentStatus = 'returned',
        ?int $userId = null,
    ): ReturnCase;

    public function markWarehouseReceived(int $returnCaseId, ?int $userId = null): ReturnCase;

    public function closeReturn(int $returnCaseId, ?int $userId = null): ReturnCase;
}
