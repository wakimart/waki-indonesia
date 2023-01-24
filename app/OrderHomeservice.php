<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderHomeservice extends Model
{
    protected $fillable = [
        'order_id', 'home_service_id',
    ];
}
