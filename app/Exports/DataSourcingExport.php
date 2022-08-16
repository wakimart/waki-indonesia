<?php

namespace App\Exports;

use App\DataSourcing;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DataSourcingExport implements FromView, ShouldAutoSize
{
    public function __construct($search)
    {
        $this->search = $search;
    }

    public function view(): View
    {
        $search = $this->search;
        $data_sourcings = DataSourcing::from('data_sourcings as ds')
            ->select('ds.*', 
                'b.code as b_code', 
                'c.code as c_code',
                'c.name as c_name',
                'tc.name as tc_name')
            ->leftJoin('branches as b', 'b.id', 'ds.branch_id')
            ->leftJoin('csos as c', 'c.id', 'ds.cso_id')
            ->leftJoin('type_customers as tc', 'tc.id', 'ds.type_customer_id')
            ->where('ds.active', true);

        if ($search) {
            $data_sourcings->where('ds.name', 'LIKE', '%' . $search . '%');
            $data_sourcings->orWhere('ds.phone', 'LIKE', '%' . $search . '%');
            $data_sourcings->orWhere('b.code', 'LIKE', '%' . $search . '%');
            $data_sourcings->orWhere('c.code', 'LIKE', '%' . $search . '%');
            $data_sourcings->orWhere('c.name', 'LIKE', '%' . $search . '%');
            $data_sourcings->orWhere('tc.name', 'LIKE', '%' . $search . '%');
        }

        $countDataSourcings = $data_sourcings->count();
        $data_sourcings = $data_sourcings->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')->get();

        return view('admin.exports.data_sourcing_export', [
            "countDataSourcings" => $countDataSourcings,
            "data_sourcings" => $data_sourcings,
        ]);
    }
}
