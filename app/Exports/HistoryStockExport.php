<?php

namespace App\Exports;

use App\HistoryStock;
use App\Stock;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class HistoryStockExport implements FromView, ShouldAutoSize
{
	public function __construct($inputWarehouse, $inputStock, $dateRange)
    {
        $this->inputWarehouse = $inputWarehouse;
        $this->inputStock = $inputStock;
        $this->dateRange = $dateRange;
    }

    public function view(): View
    {
        $dateRange = $this->dateRange;
        $inputWarehouse = $this->inputWarehouse;
        $inputStock = $this->inputStock;

        $historyStockNya = HistoryStock::with('stock');
        
        if($this->inputWarehouse != null){
            $historyStockNya = $historyStockNya->whereHas('stock', function($query) use ($inputWarehouse){
                $query->where('warehouse_id', $inputWarehouse);
            });
        }

        if($this->inputStock != null){
            $historyStockNya = $historyStockNya->whereHas('stock', function($query) use ($inputStock){
                $query->where('product_id', $inputStock);
            });
        }

        $historyStockNya = $historyStockNya->whereBetween('date', $dateRange);
        
        return view('admin.exports.historystock_export', compact(
            'dateRange',
        ), [
            'HistoryStocks' => $historyStockNya->get(),
        ]);
    }
}