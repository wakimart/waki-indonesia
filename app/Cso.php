<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cso extends Model
{
    protected $fillable = [
        'code', 'name', 'branch_id',
    ];

    public function branch()
    {
        return $this->belongsTo('App\Branch');
    }

    public function deliveryOrder()
    {
        return $this->hasMany('App\DeliveryOrder');
    }

    public function order_sales()
    {
        return $this->hasMany('App\Order');
    }
    public function order_30_sales()
    {
        return $this->hasMany('App\Order', '30_cso_id', 'id');
    }
    public function order_70_sales()
    {
        return $this->hasMany('App\Order', '70_cso_id', 'id');
    }
}
