<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    static $Type = ['1' => 'pembelian', '2' => 'prize', '3' => 'upgrade'];

    protected $fillable = [
        'order_id', 'product_id', 'promo_id', 'qty', 'type', 'other', 'home_service_id', 'delivery_cso_id', 'request_hs_acc', 'offline_stock_id'
    ];

    public function homeService()
    {
        return $this->belongsTo('App\HomeService');
    }

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

    public function productNameNya()
    {
        if ($this->product_id != null) {
            return Product::where('id', $this->product_id)->value('name');
        } else if ($this->promo_id != null) {
            $promo_products = Promo::find($this->promo_id);
            return $promo_products->code . " - (". implode(", ", $promo_products->productName()) . ")";
        } else {
            return $this->other;
        }
    }
}
