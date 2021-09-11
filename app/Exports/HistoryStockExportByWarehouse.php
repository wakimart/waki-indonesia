<?php

namespace App\Exports;

use App\HistoryStock;
use App\Stock;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class HistoryStockExportByWarehouse implements FromView, ShouldAutoSize
{
	public function __construct($inputWarehouse)
    {
        $this->inputWarehouse = $inputWarehouse;
    }

    public function view(): View
    {
        $historyStockNya = HistoryStock::with('stock')->orderBy("code", "desc");

        if($this->inputWarehouse != null){
            $historyStockNya = $historyStockNya->where('stock_id', $this->inputWarehouse);
        }

        return view('admin.exports.historystock_bywarehouse', [
            'HistoryStocks' => $historyStockNya->get(),
        ]);
    }
}