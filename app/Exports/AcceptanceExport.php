<?php

namespace App\Exports;

use App\Acceptance;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AcceptanceExport implements FromView, ShouldAutoSize
{	
	public function __construct($status, $dateRange)
    {
        $this->status = $status;
        $this->dateRange = $dateRange;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        $acceptances = Acceptance::where('active', true);

        if($this->status != null){
            if($this->status == "approved-non_commission"){
                $acceptances = $acceptances->where([['status', 'approved'], ['without_commission', true]]);
            }
            else{
                $acceptances = $acceptances->where('status', $this->status);
            }
        }

        if($this->dateRange[0] != null && $this->dateRange[1] != null){
            $acceptances = $acceptances->whereBetween('upgrade_date', $this->dateRange);
        }

        //dd($acceptances->get());

        return view('admin.exports.acceptance_export', [
            'acceptances' => $acceptances->orderBy('branch_id')->get(),
        ]);
    }
}
