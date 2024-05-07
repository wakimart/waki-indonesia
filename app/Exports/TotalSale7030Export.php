<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class TotalSale7030Export implements FromView, ShouldAutoSize, WithColumnFormatting
{
    public function __construct($startDate, $endDate, $allCso, $countCso)
    {
    	$this->startdate = $startDate;
        $this->endDate = $endDate;
        $this->allCso = $allCso;
        $this->countCso = $countCso;
    }

    public function view(): View
    {
        return view('admin.exports.orderreport7030_export', [
            'startDate' => $this->startdate, 
            'endDate' => $this->endDate, 
            'allCso' => $this->allCso, 
            'countCso' => $this->countCso
        ]);
    }
    // public function columnWidths(): array
    // {
    //     return [
    //         'B' => 30,
    //         'C' => 20,
    //         'D' => 20,
    //         'E' => 20,
    //     ];
    // }

    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED,
            'E' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED,
            'F' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED,
        ];
    }

    // public function styles(Worksheet $sheet)
    // {
    //     return [
    //         "B" => [
    //             'alignment' => [
    //                 'wrapText' => true,
    //                 'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
    //                 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP
    //             ],  
    //         ],
    //         "A" => ['alignment' => [
    //                 'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    //                 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
    //             ]
    //         ],
    //         "A1:A3" => [
    //             'alignment' => [
    //                 'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
    //                 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP
    //             ],  
    //         ],
    //         "C:E" => ['alignment' => [
    //                 'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
    //                 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
    //             ]
    //         ],
    //         "A4:E4" => ['alignment' => [
    //                 'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    //                 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
    //             ]
    //         ],
            
    //     ];
    // }

}
