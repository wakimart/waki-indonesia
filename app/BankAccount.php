<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    protected $fillable = [
        'code', 'name', 'account_number', 'type', 'charge_percentage', 'estimate_transfer', 'bank_id', 'active'
    ];
}
