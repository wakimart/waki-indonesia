<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    static $Type = ['1' => 'pembelian', '2' => 'prize', '3' => 'upgrade', '4' => 'takeaway'];

    protected $fillable = [
        'order_id', 'product_id', 'promo_id', 'qty', 'type', 'other', 'stock_id', 'order_detail_id'
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

    public function stockInOut()
    {
        return $this->belongsTo('App\StockInOut', 'stock_id');
    }
}
