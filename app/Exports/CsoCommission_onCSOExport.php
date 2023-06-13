<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class CsoCommission_onCSOExport implements FromView, ShouldAutoSize, WithTitle
{
	public function __construct($CsoCommissions)
    {
    	$this->CsoCommissions = $CsoCommissions;
    }

    public function title(): string
    {
        return 'Cso Commission';
    }

    public function view(): View
    {
        return view("exports.respondent_export", ['CsoCommissions' => $this->CsoCommissions]);
    }
}
