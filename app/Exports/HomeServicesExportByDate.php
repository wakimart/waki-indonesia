<?php

namespace App\Exports;

use App\HomeService;
use App\Branch;
use App\Cso;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class HomeServicesExportByDate implements FromView, ShouldAutoSize
{
	public function __construct($city, $branch, $cso, $search, $dateRange)
    {
        $this->city = $city;
        $this->branch = $branch;
        $this->cso = $cso;
        $this->search = $search;
        $this->dateRange = $dateRange;
    }

    public function view(): View
    {
        $HomeServiceNya = HomeService::leftjoin('csos', 'home_services.cso_id', '=', 'csos.id')
                            ->leftjoin('branches', 'home_services.branch_id', '=', 'branches.id')
                            ->select('csos.code as csos_code', 'csos.name as csos_name', 'branches.code as branches_code', 'branches.name as branches_name','home_services.id as id', 'home_services.code as code', 'home_services.no_member as no_member', 'home_services.name as name', 'home_services.city as city', 'home_services.address as address', 'home_services.phone as phone', 'home_services.cso_phone as cso_phone', 'home_services.appointment as appointment', 'home_services.created_at as created_at', 'home_services.created_at as created_at', 'home_services.cash as cash', 'home_services.cash_description as cash_description', 'home_services.description as description');
        $HomeServiceNya = $HomeServiceNya->where('home_services.active', true);
        if($this->city != null){
            $HomeServiceNya = $HomeServiceNya->where('home_services.city', $this->city);
        }
        if($this->branch != null){
            $HomeServiceNya = $HomeServiceNya->where('home_services.branch_id', $this->branch);
        }
        if($this->cso != null){
            $HomeServiceNya = $HomeServiceNya->where('home_services.cso_id', $this->cso);
        }
        
        if($this->search != null){
            $HomeServiceNya = $HomeServiceNya->where('home_services.name', 'like', '%'.$this->search.'%')
            ->orWhere('home_services.phone', 'like', '%'.$this->search.'%')
            ->orWhere('home_services.code', 'like', '%'.$this->search.'%');
        }
        if($this->dateRange != null){
            $HomeServiceNya = $HomeServiceNya->whereBetween('appointment', $this->dateRange);
        }
        
        return view('admin.exports.homeservice2_export', [
            'HomeServices' => $HomeServiceNya->orderBy('appointment', 'ASC')->get(),
        ]);
    }
}