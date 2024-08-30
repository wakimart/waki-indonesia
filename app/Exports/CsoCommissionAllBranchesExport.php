<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\FromCollection;

class CsoCommissionAllBranchesExport implements WithMultipleSheets
{
    public function __construct($branches, $startDate, $endDate, $periode)
    {
    	$this->branches = $branches;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->periode = $periode;
    }

    public function sheets(): array
    {
        return [ 
            new CsoCommissionAllBranchesListCommissionAndBonusExport($this->branches, $this->periode),
            new CsoCommissionAllBranchesListCommissionExport($this->branches),
            new CsoCommissionAllBranchesListBonusExport($this->branches, $this->startDate, $this->endDate, $this->periode),
        ];
    }
}
