<?php

namespace Modules\Settlement\Repositories;

use App\Foundation\Modules\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Modules\Settlement\Entities\Settlement;
use Modules\Settlement\Enums\SettlementStatus;

class SettlementRepository extends BaseRepository
{
    protected function model(): Model
    {
        return new Settlement;
    }

    public function findPendingForCourierAndDate(int $courierId, string $date): ?Settlement
    {
        return $this->query()
            ->where('deliver_man_id', $courierId)
            ->whereDate('settlement_date', $date)
            ->where('status', SettlementStatus::Pending->value)
            ->first();
    }
}
