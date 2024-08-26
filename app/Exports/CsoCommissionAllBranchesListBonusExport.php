<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class CsoCommissionAllBranchesListBonusExport implements FromView, ShouldAutoSize, WithColumnWidths, WithTitle, WithColumnFormatting
{
    public function __construct($branches, $startDate, $endDate, $periode)
    {
    	$this->branches = $branches;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->periode = $periode;
    }

    public function title(): string
    {
        return 'Bonus';
    }

    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'E' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'F' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'G' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'H' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'I' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }
    public function columnWidths(): array
    {
        return [
            'A' => 8,
            'B' => 40,
            'C' => 8,
            'D' => 20,
            'E' => 20,
            'F' => 20,
            'G' => 20,
            'H' => 20,
            'I' => 40,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getDelegate()->getRowDimension('3')->setRowHeight(-1);

            },
        ];
    }


    public function view(): View
    {
        return view("admin.exports.cso_commission_all_branches_list_bonus_export", ['branches' => $this->branches, 'startDate' => $this->startDate, 'endDate' => $this->endDate, 'periode' => $this->periode]);
    }
}
