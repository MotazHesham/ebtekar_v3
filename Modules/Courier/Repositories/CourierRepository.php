<?php

namespace Modules\Courier\Repositories;

use App\Foundation\Modules\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Modules\Courier\Entities\Courier;

class CourierRepository extends BaseRepository
{
    protected function model(): Model
    {
        return new Courier;
    }

    public function activeWithUsers()
    {
        return $this->query()->with('user')->active()->get();
    }
}
