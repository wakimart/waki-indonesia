<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TotalSaleReportBranchExport implements FromView, ShouldAutoSize, WithColumnWidths, WithStyles
{
    public function __construct($startDate, $endDate, $total_sales, $countTotalSales, $currentBranch)
    {
    	$this->startdate = $startDate;
        $this->endDate = $endDate;
        $this->total_sales = $total_sales;
        $this->countTotalSales = $countTotalSales;
        $this->currentBranch = $currentBranch;
    }

    public function view(): View
    {
        return view('admin.exports.totalsale_branch_export', [
            'startDate' => $this->startdate, 
            'endDate' => $this->endDate, 
            'total_sales' => $this->total_sales, 
            'countTotalSales' => $this->countTotalSales,
            'currentBranch' => $this->currentBranch
        ]);
    }

    public function columnWidths(): array
    {
        return [
            'B' => 30,
            'C' => 15,
            'D' => 15,
            'E' => 15,
            'F' => 15,
            'G' => 15,
            'H' => 15,
            'I' => 15,
            'J' => 15,
        ];
    } 

    public function styles(Worksheet $sheet)
    {
        return [
            "B" => [
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
            "A1:A4" => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP
                ],  
            ],
            "C:J" => ['alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
                ]
            ],
            "A5:J5" => ['alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
                ]
            ],
            
        ];
    }
}
