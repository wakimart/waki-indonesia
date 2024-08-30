<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cso extends Model
{
    protected $fillable = [
        'code', 'name', 'branch_id', 'active', 'phone', 'no_rekening'
    ];

    public function branch()
    {
        return $this->belongsTo('App\Branch');
    }

    public function deliveryOrder()
    {
        return $this->hasMany('App\DeliveryOrder');
    }

    public function order_sales()
    {
        return $this->hasMany('App\Order');
    }

    public function order_30_sales()
    {
        return $this->hasMany('App\Order', '30_cso_id', 'id');
    }

    public function order_70_sales()
    {
        return $this->hasMany('App\Order', '70_cso_id', 'id');
    }

    public function home_service()
    {
        return $this->hasMany('App\HomeService', 'cso_id', 'id');
    }

    public function home_service2()
    {
        return $this->hasMany('App\HomeService', 'cso2_id', 'id');
    }

    public function personalHomecare()
    {
        return $this->hasMany('App\PersonalHomecare');
    }

    public function user()
    {
        return $this->hasOne('App\User');
    }

    public function technicianSchedule()
    {
        return $this->hasMany('App\TechnicianSchedule', 'technician_id');
    }

    public function orderCommission()
    {
        return $this->hasMany("App\OrderCommission")->where('active', true);
    }

    public function csoCommission()
    {
        return $this->hasMany("App\CsoCommission")->where('active', true);
    }

    public function orderCancel()
    {
        return $this->hasMany('App\OrderCancel');
    }

    public function commissionAllBranchValue($startDate, $endDate){
        $id = $this->id;
        $order70 = OrderPayment::from('order_payments as op')
            ->selectRaw('IFNULL(SUM(op.commission_percentage), 0) as total_commission_70')
            ->join('orders as o', 'o.id', 'op.order_id')
            ->join('csos as c', 'c.id', 'o.70_cso_id')
            ->where('op.status', 'verified')
            ->where('op.payment_date', '>=', $startDate)
            ->where('op.payment_date', '<=', $endDate)
            ->where('c.id', $id)
            ->first()->total_commission_70;
        $order30 = OrderPayment::from('order_payments as op')
            ->selectRaw('IFNULL(SUM(op.commission_percentage), 0) as total_commission_30')
            ->join('orders as o', 'o.id', 'op.order_id')
            ->join('csos as c', 'c.id', 'o.30_cso_id')
            ->where('op.status', 'verified')
            ->where('op.payment_date', '>=', $startDate)
            ->where('op.payment_date', '<=', $endDate)
            ->where('c.id', $id)
            ->first()->total_commission_30;
        $commissionPerCso = (70 / 100 * $order70) + (30 / 100 * $order30);
        return $commissionPerCso;
    }

    public function cancelAllBranchValue($startDate, $endDate){
        $id = $this->id;
        $cancelPerCso = OrderCancel::selectRaw('IFNULL(SUM(order_cancels.nominal_cancel), 0) as total_cancel')
            ->leftJoin('orders', 'orders.id', 'order_cancels.order_id')
            ->where('order_cancels.cancel_date', '>=', $startDate)
            ->where('order_cancels.cancel_date', '<=', $endDate)
            ->where(function($q) use($id) {
                $q->where(function($p) use($id) {
                    $p->where('orders.70_cso_id', $id);
                })
                ->orWhere(function($p) use($id) {
                    $p->where('order_cancels.cso_id', $id);
                });
            })->first()->total_cancel;
        return $cancelPerCso;
    }

    public function bonusAllBranchValue($startDate, $endDate){
        $id = $this->id;
        $bonusPerCso = $this->orderCommission->filter(function($valueNya, $keyNya) use ($startDate, $endDate, $id){
            $perOr = $valueNya->order;
            if($perOr['status'] != 'reject'){
                if($perOr->orderPayment->sortBy('payment_date')->last()['payment_date'] >= $startDate && $perOr->orderPayment->sortBy('payment_date')->last()['payment_date'] <= $endDate){
                    return $valueNya;
                }
            }
        })->sum(function ($row) {return ($row->bonus + $row->upgrade + $row->smgt_nominal + $row->excess_price);});

        return $bonusPerCso;
    }
}
