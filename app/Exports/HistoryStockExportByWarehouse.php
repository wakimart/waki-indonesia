<?php

namespace App\Exports;

use App\HistoryStock;
use App\Stock;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class HistoryStockExportByWarehouse implements FromView, ShouldAutoSize
{
	public function __construct($inputWarehouse, $dateRange)
    {
        $this->inputWarehouse = $inputWarehouse;
        $this->dateRange = $dateRange;
    }

    public function view(): View
    {
        $historyStockNya = HistoryStock::orderBy("date", "desc")
            ->whereNotNull('code')
            ->leftJoin('stocks', 'stocks.warehouse_id', '=', 'warehouse_id');

        if($this->inputWarehouse != null){
            $historyStockNya = $historyStockNya->where('warehouse_id', $this->inputWarehouse);
        }

        if($this->dateRange[0] != null && $this->dateRange[1] != null){
            $historyStockNya = $historyStockNya->whereBetween('date', $this->dateRange);
        }

        return view('admin.exports.historystock_bywarehouse', [
            'HistoryStocks' => $historyStockNya->get(),
        ]);
    }
}