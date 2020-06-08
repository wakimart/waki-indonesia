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
	public function __construct($date, $city, $branch, $cso)
    {
    	$this->date = date($date);
        $this->city = $city;
        $this->branch = $branch;
        $this->cso = $cso;
    }

    public function view(): View
    {
        $HomeServiceNya = HomeService::WhereDate('appointment', $this->date)->where('active', true);
        if($this->city != null){
            $HomeServiceNya = $HomeServiceNya->where('city', $this->city);
        }
        if($this->branch != null){
            $HomeServiceNya = $HomeServiceNya->where('branch_id', $this->branch);
        }
        if($this->cso != null){
            $HomeServiceNya = $HomeServiceNya->where('cso_id', $this->cso);
        }

        return view('admin.exports.homeservice1_export', [
            'HomeServices' => $HomeServiceNya->orderBy('appointment', 'ASC')->get(),
            'Branches' => Branch::Where('active', true)->get(),
            'Csos' => Cso::Where('active', true)->get(),
        ]);
    }
}
