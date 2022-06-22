<?php

namespace App\Exports;

use App\DataTherapy;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DataTherapyExport implements FromView, ShouldAutoSize
{
    public function __construct($search)
    {
        $this->search = $search;
    }

    public function view(): View
    {
        $search = $this->search;
        $data_therapies = DataTherapy::from('data_therapies as dt')
            ->select('dt.*', 
                'b.code as b_code', 
                'c.code as c_code',
                'c.name as c_name',
                'tc.name as tc_name')
            ->leftJoin('branches as b', 'b.id', 'dt.branch_id')
            ->leftJoin('csos as c', 'c.id', 'dt.cso_id')
            ->leftJoin('type_customers as tc', 'tc.id', 'dt.type_customer_id')
            ->where('dt.active', true);

    if ($search) {
        $data_therapies->where('dt.name', 'LIKE', '%' . $search . '%');
        $data_therapies->orWhere('dt.no_ktp', 'LIKE', '%' . $search . '%');
        $data_therapies->orWhere('dt.phone', 'LIKE', '%' . $search . '%');
        $data_therapies->orWhere('b.code', 'LIKE', '%' . $search . '%');
        $data_therapies->orWhere('c.code', 'LIKE', '%' . $search . '%');
        $data_therapies->orWhere('c.name', 'LIKE', '%' . $search . '%');
        $data_therapies->orWhere('tc.name', 'LIKE', '%' . $search . '%');
    }

    $countDataTherapies = $data_therapies->count();
    $data_therapies = $data_therapies->orderBy('created_at', 'desc')
        ->orderBy('id', 'desc')->get();

    return view('admin.exports.data_therapy_export', [
        "countDataTherapies" => $countDataTherapies,
        "data_therapies" => $data_therapies,
    ]);
    }
}
