<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $fillable = [
        'product_id', 'type_warehouse', 'quantity', 'active'
    ];

    public function product()
    {
        return $this->belongsTo('App\Product');
    }
}
