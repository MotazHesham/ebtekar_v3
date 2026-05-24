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

    /**
     * @param  array{reason?: string, note?: string, status?: string}  $data
     */
    public function updateCase(int $returnCaseId, array $data, ?int $userId = null): ReturnCase;

    public function deleteCase(int $returnCaseId, ?int $userId = null): void;

    public function reopenCase(int $returnCaseId, ?int $userId = null): ReturnCase;
}
