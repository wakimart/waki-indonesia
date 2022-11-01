<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FinancialRoutineTransaction extends Model
{
    protected $fillable = [
        'transaction_date', 'bank_account_id', 'financial_routine_id',
        'description', 'transaction', 'user_id',
    ];

    public function bankAccount()
    {
        return $this->belongsTo('App\BankAccount', 'bank_account_id', 'id');
    }

    public function financialRoutine()
    {
        return $this->belongsTo('App\FinancialRoutine', 'financial_routine_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
}
