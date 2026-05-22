<?php

namespace Modules\Returns\Repositories;

use App\Foundation\Modules\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Modules\Returns\Entities\ReturnCase;
use Modules\Returns\Enums\ReturnCaseStatus;
use Modules\Shipping\Entities\Shipment;

class ReturnCaseRepository extends BaseRepository
{
    protected function model(): Model
    {
        return new ReturnCase;
    }

    public function findShipmentOrFail(int $id): Shipment
    {
        return Shipment::findOrFail($id);
    }

    public function openCaseForShipment(int $shipmentId): ?ReturnCase
    {
        return $this->query()
            ->where('delivery_order_id', $shipmentId)
            ->whereIn('status', [
                ReturnCaseStatus::Open->value,
                ReturnCaseStatus::WarehouseReceived->value,
            ])
            ->first();
    }
}
