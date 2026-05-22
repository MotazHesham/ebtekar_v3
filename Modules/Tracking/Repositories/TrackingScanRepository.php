<?php

namespace Modules\Tracking\Repositories;

use App\Foundation\Modules\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Modules\Tracking\Entities\TrackingScan;
use Modules\Tracking\Enums\ScanType;

class TrackingScanRepository extends BaseRepository
{
    protected function model(): Model
    {
        return new TrackingScan;
    }

    public function lastHandoffForShipment(int $shipmentId): ?TrackingScan
    {
        return $this->query()
            ->where('delivery_order_id', $shipmentId)
            ->where('scan_type', ScanType::Handoff->value)
            ->orderByDesc('id')
            ->first();
    }
}
