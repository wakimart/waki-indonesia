<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StockInOutProduct extends Model
{
    protected $fillable = [
        'stock_in_out_id', 'stock_from_id', 'stock_to_id', 
        'product_id', 'quantity',
        'koli', 'active', 'order_detail_id',
    ];

    public function orderDetail()
    {
        return $this->belongsTo('App\OrderDetail');
    }

    public function stockInOut()
    {
        return $this->belongsTo('App\StockInOut', 'stock_in_out_id', 'id');
    }

    public function stockFrom()
    {
        return $this->belongsTo('App\Stock', 'stock_from_id', 'id');
    }

    public function stockTo()
    {
        return $this->belongsTo('App\Stock', 'stock_to_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo('App\Product', 'product_id', 'id');
    }
}
