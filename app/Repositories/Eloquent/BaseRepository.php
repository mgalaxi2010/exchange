<?php

namespace App\Repositories\Eloquent;

use App\Repositories\EloquentRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository implements EloquentRepositoryInterface
{
    abstract function getModel(): Model;

    public function create(array $attributes): Model
    {
        return $this->getModel()->query()->create($attributes);
    }

}
