<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderPaymentOnline extends Model
{
    protected $connection = 'server';

    protected $table = 'order_payments';

    protected $fillable = [
        'order_id', 'total_payment', 'payment_date', 'bank_id', 'cicilan', 'image', 'status'
    ];

    public function order()
    {
        return $this->belongsTo("App\OrderOnline", 'order_id');
    }

    public function bank()
    {
        return $this->belongsTo("App\Bank");
    }
}
