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

    public function creditCard(){
        return $this->belongsTo('App\CreditCard');
    }

    public function bankAccount(){
        return $this->belongsTo('App\BankAccount');
    }

    public function totalSale()
    {
        return $this->hasOne('App\TotalSale', 'order_payment_id', 'id');
    }
}
