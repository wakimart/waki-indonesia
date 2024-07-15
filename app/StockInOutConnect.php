<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StockInOutConnect extends Model
{
    protected $fillable = [
        'stock_out_id', 'stock_in_id', 'status'
    ];

    public function stockOut()
    {
        return $this->belongsTo('App\StockInOut', 'stock_out_id', 'id');
    }
    public function stockIn()
    {
        return $this->belongsTo('App\StockInOut', 'stock_in_id', 'id');
    }
}
