<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TotalSaleByBankExport implements FromView, ShouldAutoSize, WithColumnWidths, WithStyles
{
    public function __construct($startDate, $endDate, $total_sales)
    {
    	$this->startdate = $startDate;
        $this->endDate = $endDate;
        $this->total_sales = $total_sales;
    }

    public function view(): View
    {
        return view('admin.exports.totalsale_bybank_export', [
            'startDate' => $this->startdate, 
            'endDate' => $this->endDate, 
            'total_sales' => $this->total_sales, 
        ]);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 3,
            'B' => 15,
            'C' => 10,
            'D' => 11.2,
            'E' => 11.2,
            'F' => 11.2,
            'G' => 11.2,
            'H' => 11.2,
        ];
    } 

    public function styles(Worksheet $sheet)
    {
        return [];
    }
}
