<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderCancel extends Model
{

    protected $fillable = [
        'order_id', 'branch_id', 'cso_id', 'temp_no', 'cancel_date', 'nominal_cancel'
    ];

    public function order()
    {
        return $this->belongsTo("App\Order");
    }

    public function branch()
    {
        return $this->belongsTo("App\Branch");
    }

    public function cso()
    {
        return $this->belongsTo("App\Cso");
    }
}
