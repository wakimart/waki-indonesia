<?php

namespace App\Exports;

use App\HomeService;
use App\Branch;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class HomeServicesCompareExport implements FromView, ShouldAutoSize
{
    public function __construct($dateRange)
    {
        $this->dateRange = $dateRange;
    }

    public function view(): View
    {
        
        $result = [];
        $totalPerBranch = [];
        $type_customer = ['VVIP (Type A)','WAKi Customer (Type B)','New Customer (Type C)'];
        $type_homeservices = ['Home service','Home Tele Voucher','Home Eksklusif Therapy','Home Free Family Therapy','Home Demo Health & Safety with WAKi','Home Voucher','Home Tele Free Gift','Home Refrensi Product','Home Delivery','Home Free Refrensi Therapy VIP','Home WAKi Di Rumah Aja'];
        $activeBranch = Branch::where('active', true)->get();

        foreach ($type_customer as $type_cust) {
            $result[$type_cust] = [];
            foreach ($type_homeservices as $type_hs) {
                $result[$type_cust][$type_hs] = [];
                foreach ($activeBranch as $branchNya) {
                    $result[$type_cust][$type_hs][$branchNya['code']] = HomeService::where([['type_customer', $type_cust], ['type_homeservices', $type_hs], ['branch_id', $branchNya['id']], ['active', true]])->whereBetween('appointment', $this->dateRange)->count();
                    if(isset($totalPerBranch[$branchNya['code']])){
                        $totalPerBranch[$branchNya['code']] += $result[$type_cust][$type_hs][$branchNya['code']];
                    }
                    else{
                        $totalPerBranch[$branchNya['code']] = $result[$type_cust][$type_hs][$branchNya['code']];
                    }
                }
            }
        }

        return view('admin.exports.homeservice3_export', [
            'result' => $result,
            'totalPerBranch' => $totalPerBranch,
        ]);
    }
}
