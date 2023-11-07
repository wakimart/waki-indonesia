<?php

namespace App\Exports;

use App\Order;
use App\Branch;
use App\Cso;
use App\Promo;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class OrderExportForTeleCustomer implements FromView, ShouldAutoSize, WithColumnFormatting
{
	public function __construct($start_date, $end_date, $city, $category, $cso, $promo, $status)
    {
    	$this->start_date = date($start_date);
        $this->end_date = date($end_date);
        $this->city = $city;
        $this->category = $category;
        $this->cso = $cso;
        $this->promo = $promo;
        $this->status = $status;
    }

    public function columnFormats(): array
    {
        return [
            'I' => '#,##0.00',
        ];
    }

    public function view(): View
    {
        $order = Order::whereBetween('orderDate', [$this->start_date, $this->end_date])->where('active', true);
        if($this->city != null){
            $order = $order->where('city', $this->city);
        }
        if($this->cso != null){
            $cso = Cso::where('code', $this->cso)->first();
            $order = $order->where('cso_id', $cso->id);
        }
        if($this->promo != null){
            if($this->promo == "promo"){
                $promo_id = Promo::pluck('id')->toArray();
            }else{
                $promo_id = array();
                $promo_id[] = $this->promo;
            }
            $order = $order->leftJoin('order_details', function($join) {
                $join->on('orders.id', '=', 'order_details.order_id');
            })->whereIn('promo_id', $promo_id);
        }
        if($this->status != null){
            $order = $order->where('status', $this->status);
        }
        return view('admin.exports.order_report_for_tele_customer', [
            'order' => $order->orderBy('orderDate', 'ASC')->get(),
        ]);
    }
}
