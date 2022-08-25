<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CreditCard extends Model
{
    protected $fillable = [
        'code', 'name', 'bank_account_id', 'cicilan', 'charge_percentage_sales', 'charge_percentage_company', 'estimate_transfer', 'description'
    ];

    public function bankAccount()
    {
        return $this->belongsTo("App\BankAccount");
    }
}
