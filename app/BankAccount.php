<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    static $PettyCashType = ['1' => 'bank', '2' => 'account'];
    
    protected $fillable = [
        'code', 'name', 'account_number', 'type', 'charge_percentage', 'estimate_transfer', 'bank_id', 'active', 'petty_cash_type'
    ];

    public function bank()
    {
        return $this->belongsTo("App\Bank");
    }

    public function branch()
    {
        return $this->hasOne('App\Branch');
    }
}
