<?php

namespace App\Exports;

use App\Order;
use App\Branch;
use App\Cso;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class OrderExport implements FromView, ShouldAutoSize
{
	public function __construct($date, $city, $category, $cso)
    {
    	$this->date = date($date);
        $this->city = $city;
        $this->category = $category;
        $this->cso = $cso;
    }

    public function view(): View
    {
        $order = Order::WhereDate('orderDate', $this->date)->where('active', true);
        if($this->city != null){
            $order = $order->where('city', $this->city);
        }
        // if($this->category != null){
        //     $order = $order->where('branch_id', $this->branch);
        // }
        if($this->cso != null){
            $cso = Cso::where('code', $this->cso)->first();
            $order = $order->where('cso_id', $cso->id);
        }
        return view('admin.exports.order_export', [
            'order' => $order->orderBy('orderDate', 'ASC')->get(),
        ]);
    }
}
