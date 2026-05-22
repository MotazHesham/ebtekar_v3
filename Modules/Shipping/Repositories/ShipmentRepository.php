<?php

namespace Modules\Shipping\Repositories;

use App\Foundation\Modules\BaseRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Modules\Shipping\Entities\Shipment;

class ShipmentRepository extends BaseRepository
{
    protected function model(): Model
    {
        return new Shipment;
    }

    public function queryForUser(): Builder
    {
        return Shipment::query()->forUser();
    }

    public function findByOrderable(string $type, int $id): ?Shipment
    {
        return $this->query()
            ->where('orderable_type', $type)
            ->where('orderable_id', $id)
            ->first();
    }
}
