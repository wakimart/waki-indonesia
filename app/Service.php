<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'code','no_mpc', 'name', 'address', 'phone', 'service_date', 'service_option', 'status', 'history_status', 'active',
    ];

    public function product_services()
    {
        return $this->hasMany('App\ProductService');
    }
}
