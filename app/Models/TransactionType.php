<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionType extends Model
{
    protected $guarded = ['id'];
    protected $table = "transaction_types";

}
