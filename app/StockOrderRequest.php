<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StockOrderRequest extends Model
{
    static $status = ['1' => 'pending', '2' => 'approved'];
    
    protected $fillable = [
        'product_qty',
        'order_id',
        'status,'
    ];

    public function order()
    {
        return $this->belongsTo('App\Order');
    }

    public function stockInOut()
    {
        return $this->hasOne('App\StockInOut');
    }
}
