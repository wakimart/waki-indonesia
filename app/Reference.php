<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reference extends Model
{
    protected $fillable = [
        'name', 'age', 'phone', 'province', 'city', 'deliveryorder_id',
    ];

    public function deliveryorder()
    {
        return $this->belongsTo('App\DeliveryOrder');
    }
}
