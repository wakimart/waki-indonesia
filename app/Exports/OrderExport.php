<?php

namespace App\Exports;

use App\Order;
use App\Branch;
use App\Cso;
use App\Promo;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class OrderExport implements FromView, ShouldAutoSize
{
	public function __construct($start_date, $end_date, $city, $category, $cso, $promo)
    {
    	$this->start_date = date($start_date);
        $this->end_date = date($end_date);
        $this->city = $city;
        $this->category = $category;
        $this->cso = $cso;
        $this->promo = $promo;
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
                $promos = Promo::all();
                $order = $order->Where(function ($query) use($promos) {
                    for ($i = 0; $i < count($promos); $i++){
                        $query->orwhere('product', 'like',  '%"id":"'.$promos[$i]['id'].'"%');
                    }
                });
            }
            else{
                $order = $order->where('product', 'like', '%"id":"'.$this->promo.'"%');
            }
        }
        return view('admin.exports.order_export', [
            'order' => $order->orderBy('orderDate', 'ASC')->get(),
        ]);
    }
}
