<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OrderReportCsoExport implements FromView, ShouldAutoSize, WithColumnWidths, WithStyles
{
    public function __construct($startDate, $endDate, $order_reports, $countOrderReports, $currentBranch, $currentCso)
    {
    	$this->startdate = $startDate;
        $this->endDate = $endDate;
        $this->order_reports = $order_reports;
        $this->countOrderReports = $countOrderReports;
        $this->currentBranch = $currentBranch;
        $this->currentCso = $currentCso;
    }

    public function view(): View
    {
        return view('admin.exports.orderreport_cso_export', [
            'startDate' => $this->startdate, 
            'endDate' => $this->endDate, 
            'order_reports' => $this->order_reports, 
            'countOrderReports' => $this->countOrderReports,
            'currentBranch' => $this->currentBranch,
            'currentCso' => $this->currentCso
        ]);
    }
    public function columnWidths(): array
    {
        return [
            'B' => 20,
            'C' => 30,
            'D' => 20,
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
            "D" => ['alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
                ]
            ],
            "A6:D6" => ['alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
                ]
            ],
            
        ];
    }

}
