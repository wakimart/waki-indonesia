<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CsoCommission extends Model
{
    protected $fillable = [
        'cso_id', 'commission', 'pajak', 'active', 'created_at', 'updated_at'
    ];

    public function cso()
    {
        return $this->belongsTo("App\Cso");
    }

    public function orderCommission()
    {
        return $this->belongsToMany(OrderCommission::class, 'order_cso_commissions');
    }
}
