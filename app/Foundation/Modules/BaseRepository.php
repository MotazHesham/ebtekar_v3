<?php

namespace App\Foundation\Modules;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository
{
    abstract protected function model(): Model;

    public function query(): Builder
    {
        return $this->model()->newQuery();
    }

    public function find(int $id): ?Model
    {
        return $this->query()->find($id);
    }

    public function findByUuid(string $uuid): ?Model
    {
        return $this->query()->where('uuid', $uuid)->first();
    }
}
