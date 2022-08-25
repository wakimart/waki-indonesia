<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderPayment extends Model
{
    protected $fillable = [
        'order_id', 'total_payment', 'payment_date', 'bank_id', 'cicilan', 'image', 'status', 'bank_account_id'
    ];

    public function order()
    {
        return $this->belongsTo("App\Order");
    }

    public function bank()
    {
        return $this->belongsTo("App\Bank");
    }
}
