<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $fillable = [
        "warehouse_id",
        'product_id',
        'type_warehouse',
        'quantity',
        'active',
    ];

    public function product()
    {
        return $this->belongsTo('App\Product');
    }

    public function warehouse()
    {
        return $this->belongsTo("App\Warehouse");
    }

    public function historyStock()
    {
        return $this->hasMany("App\HistoryStock");
    }
}
