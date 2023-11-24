<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\FromCollection;

class CsoCommissionEachExport implements WithMultipleSheets
{
    public function __construct($allCsoCommission, $startDate, $endDate, $filterBranch)
    {
    	$this->allCsoCommission = $allCsoCommission;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->filterBranch = $filterBranch;
    }

    public function sheets(): array
    {
        return [ 
            	new CsoCommissionEach_onCSOExport($this->allCsoCommission),
            	new CsoCommissionEach_onOrderExport($this->allCsoCommission, $this->startDate, $this->endDate, $this->filterBranch)
			];
    }
}
