<?php

namespace App\Exports;

use App\HomeService;
use App\Branch;
use App\Cso;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class HomeServicesExport implements FromView, ShouldAutoSize
{
	public function __construct($date)
    {
    	$this->date = date($date);
    }

    public function view(): View
    {
        return view('admin.exports.homeservice1_export', [
            'HomeServices' => HomeService::WhereDate('appointment', $this->date)->where('active', true)->orderBy('appointment', 'ASC')->get(),
            'Branches' => Branch::Where('active', true)->get(),
            'Csos' => Cso::Where('active', true)->get(),
        ]);
    }
}
