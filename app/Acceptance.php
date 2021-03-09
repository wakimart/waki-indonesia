<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Acceptance extends Model
{
    protected $fillable = [
        'code', 'no_do', 'no_mpc', 'name', 'address', 'phone', 'upgrade_date', 'oldproduct_id', 'newproduct_id', 'purchase_date', 'arr_condition', 'request_price', 'description', 'image', 'user_id', 'order_id', 'branch_id', 'cso_id', 'status', 'active',
    ];

    public function oldproduct()
    {
        return $this->belongsTo('App\Product');
    }

    public function newproduct()
    {
        return $this->belongsTo('App\Product');
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
}
