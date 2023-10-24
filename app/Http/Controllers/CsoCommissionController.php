<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Exports\CsoCommissionExport;
use Maatwebsite\Excel\Facades\Excel;
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
                ->where('csos.active', true)
	        	->get();        	
        }

        return view('admin.list_csocommission', compact('startDate', 'endDate', 'branches', 'CsoCommissions'));
	}

	public function show($id){
		$cso_commission = CsoCommission::find($id);
        return view('admin.detail_csocommission', compact('cso_commission'));
	}

	public function edit($id){
        if($id){
			$cso_commission = CsoCommission::find($id);
            if(isset($cso_commission)){
                return response()->json([
                    'month' => date('Y-m', strtotime($cso_commission->created_at)),
                    'cso' => $cso_commission->cso['code']. '-' .$cso_commission->cso['name'],
                    'commission' => $cso_commission->commission == 0 ? $cso_commission->orderCommission->sum('commission') : $cso_commission->commission,
                    'pajak' => $cso_commission->pajak,
                ], 200);
            }
        }
        return response()->json(['error' => 'Invalid ID'], 500);
	}

	public function update(Request $request, $id){
        if($id){
            $cso_commission = CsoCommission::find($id);
            if(isset($cso_commission)){
                DB::beginTransaction();
                try {
                    $cso_commission->commission = $request->commission;
                    $cso_commission->pajak = $request->pajak;
                    $cso_commission->update();

                    DB::commit();
                    return redirect()->back()->with('success', 'CSO Commission Berhasil Di Ubah');
                } catch (\Exception $ex) {
                    DB::rollback();
                    return response()->json(['error' =>  $ex->getMessage(), 500]);
                }
                
            }
        }
        return response()->json(['error' => 'Invalid ID'], 500);
	}

	public function destroy($id){
        if($id){
            $cso_commission = CsoCommission::find($id);
            if(isset($cso_commission)){
                DB::beginTransaction();
                try {
                    $cso_commission->active = false;
                    $cso_commission->update();

                    DB::commit();
                    return redirect()->back()->with('success', 'CSO Commission Berhasil Di Hapus');
                } catch (\Exception $ex) {
                    DB::rollback();
                    return response()->json(['error' =>  $ex->getMessage(), 500]);
                }
                
            }
        }
        return response()->json(['error' => 'Invalid ID'], 500);
	}

	public function exportCsoCommission(Request $request){
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

        return Excel::download(new CsoCommissionExport($CsoCommissions), 'Commission Report.xlsx');
	}
}
