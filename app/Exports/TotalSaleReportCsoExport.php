<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TotalSaleReportCsoExport implements FromView, ShouldAutoSize, WithColumnWidths, WithStyles
{
    public function __construct($startDate, $endDate, $total_sales, $countTotalSales, $currentBranch, $currentCso)
    {
    	$this->startdate = $startDate;
        $this->endDate = $endDate;
        $this->total_sales = $total_sales;
        $this->countTotalSales = $countTotalSales;
        $this->currentBranch = $currentBranch;
        $this->currentCso = $currentCso;
    }

    public function view(): View
    {
        return view('admin.exports.totalsale_cso_export', [
            'startDate' => $this->startdate, 
            'endDate' => $this->endDate, 
            'total_sales' => $this->total_sales, 
            'countTotalSales' => $this->countTotalSales,
            'currentBranch' => $this->currentBranch,
            'currentCso' => $this->currentCso
        ]);
    }

    public function columnWidths(): array
    {
        return [
            'B' => 20,
            'C' => 30,
            'D' => 15,
            'E' => 15,
            'F' => 15,
            'G' => 15,
            'H' => 15,
            'I' => 15,
        ];
    } 

    public function styles(Worksheet $sheet)
    {
        return [
            "B:C" => [
                'alignment' => [
                    'wrapText' => true,
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP
                ],  
            ],
            "A" => ['alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
                ]
            ],
            "A1:A5" => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP
                ],  
            ],
            "D:I" => ['alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
                ]
            ],
            "A6:I6" => ['alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
                ]
            ],
            
        ];
    }
}
