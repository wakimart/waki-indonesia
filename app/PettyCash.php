<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PettyCash extends Model
{
    protected $fillable = [
        'code', 'temp_no', 'transaction_date', 'type', 'bank_account_id', 'user_id', 'active',
    ];

    public function bankAccount()
    {
        return $this->belongsTo('App\BankAccount', 'bank_account_id', 'id');
    }
    
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function pettyCashDetail()
    {
        return $this->hasMany('App\PettyCashDetail', 'petty_cash_id', 'id');
    }
}
