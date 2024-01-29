<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    public function totalSales($isBranch)
    {
        $startDate = date('Y-m-01', strtotime($this->routine_date));
        $endDate = date('Y-m-d', strtotime($this->routine_date));

        if($isBranch){
            $total_sales_temp = Branch::from('branches as br')
                ->select('br.*'
                    , 'b.id as b_id'
                    , 'b.code as b_code'
                    , 'b.name as b_name'
                    , 'b.estimate_transfer as b_estimate_transfer'
                    , 'o.id as o_id'
                    , 'o.code as o_code'
                    , 'o.created_at as o_created_at'
                    , 'op.payment_date as op_payment_date'
                    , 'op.estimate_transfer_date as op_estimate_transfer_date'
                    , DB::raw('CONCAT(c.code," - ",c.name) as c_name')
                    , 'ts.bank_in as ts_bank_in'
                    , 'ts.debit as ts_debit'
                    , 'ts.netto_debit as ts_netto_debit'
                    , 'ts.card as ts_card'
                    , 'ts.netto_card as ts_netto_card')
                ->join('orders as o', 'o.branch_id', 'br.id')
                ->join('order_payments as op', 'op.order_id', 'o.id')
                ->join('bank_accounts as b', 'b.id', 'op.bank_account_id')
                ->join('total_sales as ts', 'ts.order_payment_id', 'op.id')
                ->leftjoin('csos as c', 'c.id', 'o.cso_id')
                ->whereBetween('op.payment_date', [$startDate, $endDate])
                ->where('op.status', '!=', 'rejected')
                ->where('o.active', true)
                ->groupBy('br.id', 'b.id', 'op.id')
                ->orderBy('br.code', 'asc')
                ->orderBy('b.code', 'asc')
                ->orderBy('op.payment_date', 'asc')->get();

            $total_sales = [];

            foreach ($total_sales_temp as $ts_temp) {
                if($ts_temp['id'] != $this->bankAccount->branch['id'])
                    continue;
                if (!isset($total_sales[$ts_temp['id']])) 
                    $total_sales[$ts_temp['id']] = $ts_temp->toArray();
                if (!isset($total_sales[$ts_temp['id']]['banks'][$ts_temp['b_id']])) 
                    $total_sales[$ts_temp['id']]['banks'][$ts_temp['b_id']] = $ts_temp->toArray();
                $total_sales[$ts_temp['id']]['banks'][$ts_temp['b_id']]['orders'][] = $ts_temp;
            }
        }else{
            $total_sales_temp = OrderPayment::from('order_payments as op')
                ->select('op.*', 'o.code as o_code', 'c.code as c_code', 'c.name as c_name'
                    , 'br.id as br_id', 'br.code as br_code', 'br.name as br_name'
                    , 'bacc.id as bacc_id', 'bacc.code as bacc_code', 'bacc.name as bacc_name', 'bacc.account_number as bacc_account_number', 'bacc.estimate_transfer as bacc_estimate_transfer'
                    , 'ts.bank_in as ts_bank_in'
                    , 'ts.debit as ts_debit'
                    , 'ts.netto_debit as ts_netto_debit'
                    , 'ts.card as ts_card'
                    , 'ts.netto_card as ts_netto_card'
                )
                ->join('orders as o', 'o.id', 'op.order_id')
                ->join('total_sales as ts', 'ts.order_payment_id', 'op.id')
                ->join('csos as c', 'c.id', 'o.Cso_id')
                ->join('branches as br', 'br.id', 'o.branch_id')
                ->join('bank_accounts as bacc', 'bacc.id', 'op.bank_account_id')
                ->whereBetween('op.payment_date', [$startDate, $endDate])
                ->where('op.bank_account_id', $this->bank_account_id)
                ->where('o.active', true)
                ->where('o.status', '!=', 'reject')
                ->orderBy('op.payment_date')
                ->groupBy('op.id')
                ->get();

            $total_sales = [];
            foreach ($total_sales_temp as $ts_temp) {
                if (!isset($total_sales[$ts_temp['br_id']])) 
                    $total_sales[$ts_temp['br_id']] = $ts_temp->toArray();
                $total_sales[$ts_temp['br_id']]['orders'][] = $ts_temp;
            }
        }

        return $total_sales;
    }
}
