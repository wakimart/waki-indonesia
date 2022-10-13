<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StockInOut extends Model
{
    protected $fillable = [
        'warehouse_from_id', 'warehouse_to_id', 'code', 'temp_no',
        'date', 'type', 'description', 'user_id', 'active',
    ];

    public function warehouseFrom()
    {
        return $this->belongsTo('App\Warehouse', 'warehouse_from_id', 'id');
    }

    public function warehouseTo()
    {
        return $this->belongsTo('App\Warehouse', 'warehouse_to_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function stockInOutProduct()
    {
        return $this->hasMany('App\StockInOutProduct', 'stock_in_out_id', 'id')
            ->where('stock_in_out_products.active', true);
    }
}
