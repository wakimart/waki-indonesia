<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\CsoCommission;
use App\Branch;
use App\Cso;

class CsoCommissionController extends Controller
{
	public static function CreateMonthlyCommission(){
        DB::beginTransaction();

        try {
        	$lastCreated = CsoCommission::latest('created_at')->first();
			$createCsoCommission = false;
			if($lastCreated == null){
				$createCsoCommission = true;
			}
			elseif(date("n", strtotime($lastCreated->created_at)) < date("n")){
				$createCsoCommission = true;
			}

			if($createCsoCommission){
				$allActiveCso = Cso::where('active', true)->get();
				foreach ($allActiveCso as $key => $cso) {
					$data = [];
					$data['cso_id'] = $cso->id;
					$data['created_at'] = date("Y-m-01 00:00:00");
					$data['updated_at'] = date("Y-m-01 00:00:00");
					CsoCommission::create($data);
				}
	            DB::commit();
			}
        } catch (Exception $e) {
            DB::rollback();
        }
	}

	public function index(Request $request){
		$branches = Branch::where('active', true)->get();
		$startDate = $request->has('filter_month') ? date($request->input('filter_month').'-01') : date('Y-m-01');
        $endDate = date('Y-m-t', strtotime($startDate));
        $CsoCommissions = null;

        if($request->has('filter_branch')){
	        $CsoCommissions = CsoCommission::select('cso_commissions.*')
	        	->where('cso_commissions.created_at', '>=', $startDate)
	        	->where('cso_commissions.created_at', '<=', $endDate)
	        	->where('cso_commissions.active', true)
	        	->leftJoin('csos', 'csos.id', '=', 'cso_commissions.cso_id')
	        	->leftJoin('branches', 'branches.id', '=', 'csos.branch_id')
	        	->where('branches.id', $request->input('filter_branch'))
	        	->get();        	
        }

        return view('admin.list_csocommission', compact('startDate', 'endDate', 'branches', 'CsoCommissions'));
	}

	public function show(Request $request){
		
	}
}
