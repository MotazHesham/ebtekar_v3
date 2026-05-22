<?php

namespace Modules\Shipping\Repositories;

use App\Foundation\Modules\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Modules\Shipping\Entities\ShippingPartner;

class ShippingPartnerRepository extends BaseRepository
{
    protected function model(): Model
    {
        return new ShippingPartner;
    }

    public function activePluck(): array
    {
        return $this->query()->where('is_active', true)->pluck('name', 'id')->all();
    }
}
