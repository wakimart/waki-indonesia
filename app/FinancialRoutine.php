<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FinancialRoutine extends Model
{
    protected $fillable = [
        'routine_date', 'bank_account_id', 'financial_routine_id', 
        'total_sale', 'bank_interest', 'bank_tax', 
        'etc_in', 'etc_out', 'remains_saldo', 'remains_sales',
        'description', 'user_id', 'active',
    ];
    
    public function bankAccount()
    {
        return $this->belongsTo('App\BankAccount', 'bank_account_id', 'id');
    }

    public function financialRoutine()
    {
        return $this->belongsTo('App\FinancialRoutine', 'financial_routine_id', 'id');
    }

    public function financialRoutineTransaction()
    {
        return $this->hasMany('App\FinancialRoutineTransaction', 'financial_routine_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function updateRemainsSaldo()
    {
        $sub_total_fr_details = FinancialRoutineTransaction::where('financial_routine_id', $this->id)->sum('transaction');
        $lastMonthFinancialRoutine = FinancialRoutine::find($this->financial_routine_id);
        $this->remains_saldo = (
                $lastMonthFinancialRoutine->remains_sales + 
                $lastMonthFinancialRoutine->remains_saldo + 
                $this->total_sale + $this->etc_in + $this->bank_interest
            ) - (
                $sub_total_fr_details + 
                $this->bank_tax + $this->etc_out + $this->remains_sales
            );
    }
}
