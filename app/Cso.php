<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cso extends Model
{
    protected $fillable = [
        'code', 'name', 'branch_id', 'active', 'phone',
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
    public function home_service()
    {
        return $this->hasMany('App\HomeService', 'cso_id', 'id');
    }

    public function home_service2()
    {
        return $this->hasMany('App\HomeService', 'cso2_id', 'id');
    }
    public function user(){
        return $this->hasOne('App\User');
    }
}
