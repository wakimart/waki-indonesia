<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\FromCollection;

class CsoCommissionExport implements WithMultipleSheets
{
	public function __construct($CsoCommissions, $startDate, $endDate, $filterBranch)
    {
    	$this->CsoCommissions = $CsoCommissions;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->filterBranch = $filterBranch;
    }

    public function sheets(): array
    {
        return [ 
            	new CsoCommission_onCSOExport($this->CsoCommissions),
            	new CsoCommission_onOrderExport($this->CsoCommissions, $this->startDate, $this->endDate, $this->filterBranch)
			];
    }
}
