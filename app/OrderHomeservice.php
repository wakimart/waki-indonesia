<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderHomeservice extends Model
{
    protected $fillable = [
        'order_id', 'home_service_id',
    ];

    public function homeService()
    {
        return $this->belongsTo('App\HomeService', 'home_service_id', 'id');
    }
}
