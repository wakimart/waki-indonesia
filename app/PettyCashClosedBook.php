<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PettyCashClosedBook extends Model
{
    protected $fillable = [
        'bank_account_id', 'date', 'nominal', 'user_id',
    ];

    public function bankAccount()
    {
        return $this->belongsTo('App\BankAccount', 'bank_account_id', 'id');
    }

    public function pettyCashes()
    {
        return $this->hasMany('App\PettyCash');
    }
}
