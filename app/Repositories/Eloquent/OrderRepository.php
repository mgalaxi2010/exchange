<?php

namespace App\Repositories\Eloquent;

use App\Models\Order;
use Illuminate\Database\Eloquent\Model;

class OrderRepository extends BaseRepository
{

    function getModel(): Model
    {
        return new Order();
    }


}
