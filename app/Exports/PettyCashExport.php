<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PettyCashExport implements FromView, ShouldAutoSize, WithColumnWidths, WithStyles
{
    public function __construct($startDate, $endDate, $currentBank, $pettyCashes, $lastPTCClosedBook)
    {
    	$this->startdate = $startDate;
        $this->endDate = $endDate;
        $this->currentBank = $currentBank;
        $this->pettyCashes = $pettyCashes;
        $this->lastPTCClosedBook = $lastPTCClosedBook;
    }

    public function view(): View
    {
        return view('admin.exports.petty_cash_export', [
            'startDate' => $this->startdate, 
            'endDate' => $this->endDate, 
            'currentBank' => $this->currentBank, 
            'pettyCashes' => $this->pettyCashes, 
            'lastPTCClosedBook' => $this->lastPTCClosedBook, 
        ]);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 3,
            'B' => 10,
            'C' => 10,
            'D' => 17,
            'E' => 11.2,
            'F' => 11.2,
            'G' => 11.2,
            'H' => 11.2,
            'I' => 11.2,
        ];
    } 

    public function styles(Worksheet $sheet)
    {
        return [];
    }
}
