<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cso extends Model
{
    protected $fillable = [
        'code', 'name', 'branch_id',
    ];

    public function branch()
    {
        return $this->belongsTo('App\Branch');
    }

    public function deliveryOrder()
    {
        return $this->hasMany('App\DeliveryOrder');
    }
}
