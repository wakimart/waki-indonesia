<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TotalSale extends Model
{
    protected $fillable = [
        'order_payment_id', 'bank_in', 'debit', 'netto_debit', 'netto_debit_edited', 'card', 'netto_card', 'netto_card_edited',
    ];

    public function orderPayment()
    {
        return $this->belongsTo('App\OrderPayment', 'order_payment_id', 'id');
    }
}
