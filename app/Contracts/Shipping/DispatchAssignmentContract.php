<?php

namespace App\Contracts\Shipping;

use Modules\Dispatch\Entities\DispatchBatch;
use Modules\Dispatch\DTO\AssignmentResult;
use Modules\Shipping\Entities\Shipment;

interface DispatchAssignmentContract
{
    public function assignOne(Shipment|int $shipment, int $courierId, ?int $userId = null): AssignmentResult;

    /**
     * @param  int[]  $shipmentIds
     */
    public function assignBulk(array $shipmentIds, int $courierId, ?int $userId = null): DispatchBatch;

    /**
     * @param  int[]  $shipmentIds
     */
    public function autoAssign(array $shipmentIds, ?int $userId = null, ?int $shippingPartnerId = null): DispatchBatch;
}
