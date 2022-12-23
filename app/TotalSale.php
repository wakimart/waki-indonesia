<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TotalSale extends Model
{
    protected $fillable = [
        'order_payment_id', 'bank_in', 'debit', 'netto_debit', 'card', 'netto_card',
    ];

    public function orderPayment()
    {
        return $this->belongsTo('App\OrderPayment', 'order_payment_id', 'id');
    }
}
