<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OrderReportBranchExport implements FromView, ShouldAutoSize, WithColumnWidths, WithStyles
{
    public function __construct($startDate, $endDate, $order_reports, $countOrderReports, $currentBranch)
    {
    	$this->startdate = $startDate;
        $this->endDate = $endDate;
        $this->order_reports = $order_reports;
        $this->countOrderReports = $countOrderReports;
        $this->currentBranch = $currentBranch;
    }

    public function view(): View
    {
        return view('admin.exports.orderreport_branch_export', [
            'startDate' => $this->startdate, 
            'endDate' => $this->endDate, 
            'order_reports' => $this->order_reports, 
            'countOrderReports' => $this->countOrderReports,
            'currentBranch' => $this->currentBranch
        ]);
    }
    public function columnWidths(): array
    {
        return [
            'B' => 30,
            'C' => 20,
            'D' => 20,
            'E' => 20,
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
            "C:E" => ['alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
                ]
            ],
            "A5:E5" => ['alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
                ]
            ],
            
        ];
    }

}

