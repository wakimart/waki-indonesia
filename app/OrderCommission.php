<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderCommission extends Model
{
    protected $fillable = [
        'order_id', 'cso_id', 'commission', 'bonus', 'upgrade', 'smgt_nominal', 'excess_price', 'active'
    ];

    public function order()
    {
        return $this->belongsTo("App\Order");
    }

    public function cso()
    {
        return $this->belongsTo("App\Cso");
    }

    public function csoCommission()
    {
        return $this->belongsToMany(CsoCommission::class, 'order_cso_commissions');
    }
}
