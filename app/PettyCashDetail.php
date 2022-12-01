<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PettyCashDetail extends Model
{
    protected $fillable = [
        'petty_cash_id', 'petty_cash_out_bank_account_id', 'petty_cash_out_type_id', 'nominal', 'description', 'evidence_image',
    ];

    public function pettyCash()
    {
        return $this->belongsTo('App\PettyCash', 'petty_cash_id', 'id');
    }

    public function pettyCashOutBankAccount()
    {
        return $this->belongsTo('App\BankAccount', 'petty_cash_out_bank_account_id', 'id');
    }

    public function pettyCashOutType()
    {
        return $this->belongsTo('App\PettyCashOutType', 'petty_cash_out_type_id', 'id');
    }

    public function pettyCashIn()
    {
        return $this->hasOne('App\PettyCash', 'petty_cash_detail_out_id', 'id');
    }
}
