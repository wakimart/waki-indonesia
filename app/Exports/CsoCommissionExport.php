<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\FromCollection;

class CsoCommissionExport implements WithMultipleSheets
{
	public function __construct($CsoCommissions)
    {
    	$this->CsoCommissions = $CsoCommissions;
    }

    public function sheets(): array
    {
        return [ 
            	new CsoCommission_onCSOExport($this->CsoCommissions),
            	new CsoCommission_onOrderExport($this->CsoCommissions)
			];
    }
}
