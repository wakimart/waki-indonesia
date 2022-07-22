<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    static $Type = ['1' => 'pembelian', '2' => 'prize', '3' => 'upgrade'];

    protected $fillable = [
        'order_id', 'product_id', 'promo_id', 'qty', 'type', 'other'
    ];

    public function order()
    {
        return $this->belongsTo("App\Order");
    }

    public function product()
    {
        return $this->belongsTo("App\Product");
    }

    public function promo()
    {
        return $this->belongsTo("App\Promo");
    }
}
