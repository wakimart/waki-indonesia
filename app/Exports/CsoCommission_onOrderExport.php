<?php

namespace App\Exports;

use App\Branch;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class CsoCommission_onOrderExport implements FromView, ShouldAutoSize, WithColumnWidths, WithEvents, WithTitle, WithColumnFormatting
{
	public function __construct($CsoCommissions, $startDate, $endDate, $filterBranch)
    {
    	$this->CsoCommissions = $CsoCommissions;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->filterBranch = $filterBranch;
    }

    public function title(): string
    {
        return 'Order Commission';
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
            AfterSheet::class    => function(AfterSheet $event) {
                $event->sheet->getDelegate()->getRowDimension('3')->setRowHeight(-1);

            },
        ];
    }


    public function view(): View
    {
        $periode = date("F Y", strtotime($this->startDate));
        $branch = Branch::find($this->filterBranch);
        $totalSale = Branch::from('branches as b')
            ->selectRaw("SUM(ts.bank_in) as sum_ts_bank_in")
            ->selectRaw("SUM(ts.netto_debit) as sum_ts_netto_debit")
            ->selectRaw("SUM(ts.netto_card) as sum_ts_netto_card")
            ->leftJoin('orders as o', 'o.branch_id', 'b.id')
            ->leftJoin('order_payments as op', 'op.order_id', 'o.id')
            ->leftJoin('total_sales as ts', 'ts.order_payment_id', 'op.id')
            ->whereBetween('op.payment_date', [$this->startDate, $this->endDate])
            ->where([['b.active', true], ['b.id', $this->filterBranch]])
            ->where('o.active', true)
            ->orderBy('b.code')
            ->groupBy('b.id')->first();

        return view("admin.exports.ordercommission_export", ['CsoCommissions' => $this->CsoCommissions
            ,'branch' => $branch
            ,'periode' => $periode
            ,'totalSale' => ($totalSale != null ? $totalSale : 0)
        ]);
    }
}
