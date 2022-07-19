<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Acceptance extends Model
{
    protected $fillable = [
        'code', 'no_do', 'no_mpc', 'name', 'address', 'phone', 'upgrade_date', 'oldproduct_id', 'newproduct_id', 'purchase_date', 'arr_condition', 'request_price', 'description', 'image', 'user_id', 'order_id', 'branch_id', 'cso_id', 'status', 'active', 'province', 'city', 'district', 'other_product', "product_addons_id", "area"
    ];

    static $Area = ['0' => 'null', '1' => 'surabaya', '2' => 'jakarta'];

    protected $casts = [
        'arr_condition' => 'json',
        'image' => 'json',
    ];

    public function oldproduct()
    {
        return $this->belongsTo('App\Product');
    }

    public function newproduct()
    {
        return $this->belongsTo('App\Product');
    }

    public function productAddons()
    {
        return $this->belongsTo("App\Product", "product_addons_id");
    }

    public function cso()
    {
        return $this->belongsTo('App\Cso');
    }
    public function order()
    {
        return $this->belongsTo('App\Order');
    }
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    public function branch()
    {
        return $this->belongsTo('App\Branch');
    }
    public function provinceObj()
    {
        return $this->belongsTo('App\RajaOngkir_Province', 'province', 'province_id');
    }
    public function cityObj()
    {
        return $this->belongsTo('App\RajaOngkir_City', 'city', 'city_id');
    }
    public function districObj()
    {
        return $this->belongsTo('App\RajaOngkir_Subdistrict', 'district', 'subdistrict_id');
    }
    public function acceptanceLog()
    {
        return $this->hasMany('App\AcceptanceStatusLog');
    }
}
