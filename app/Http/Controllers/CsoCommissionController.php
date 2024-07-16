<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use App\Exports\CsoCommissionExport;
use App\Exports\CsoCommissionEachExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\CsoCommission;
use App\OrderCommission;
use App\OrderPayment;
use App\Branch;
use App\OrderCancel;
use App\TotalSale;
use App\Order;
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
        $totalSaleBranch = 0;

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

            foreach ($CsoCommissions as $key => $Cso_Commission) {
                // break;
                $bonusPerCso = 0;
                $commissionPerCso = 0;
                $cancelPerCso = 0;

                if(count($Cso_Commission->orderCommission) > 0){
                    $bonusPerCso = $Cso_Commission->orderCommission->sum(function ($row) {return ($row->bonus + $row->upgrade + $row->smgt_nominal + $row->excess_price);});
                }

                //Khusus Untuk Commission Per CSO
                if($Cso_Commission->commission == 0){
                    $order70 = OrderPayment::from('order_payments as op')
                        ->selectRaw('IFNULL(SUM(op.commission_percentage), 0) as total_commission_70')
                        // ->select('o.code', 'op.commission_percentage', 'o.temp_no', 'ts.*')
                        ->join('orders as o', 'o.id', 'op.order_id')
                        ->join('csos as c', 'c.id', 'o.70_cso_id')
                        // ->join('total_sales as ts', 'ts.order_payment_id', 'op.id')
                        ->where('op.status', 'verified')
                        ->where('op.payment_date', '>=', $startDate)
                        ->where('op.payment_date', '<=', $endDate)
                        ->where('c.id', $Cso_Commission->cso['id'])
                        // ->get()->toArray();//->pluck('code', 'commission_percentage', 'temp_no');
                        ->first()->total_commission_70;

                    $order30 = OrderPayment::from('order_payments as op')
                        ->selectRaw('IFNULL(SUM(op.commission_percentage), 0) as total_commission_30')
                        // ->select('o.code', 'op.commission_percentage', 'o.temp_no', 'ts.*')
                        ->join('orders as o', 'o.id', 'op.order_id')
                        ->join('csos as c', 'c.id', 'o.30_cso_id')
                        // ->join('total_sales as ts', 'ts.order_payment_id', 'op.id')
                        ->where('op.status', 'verified')
                        ->where('op.payment_date', '>=', $startDate)
                        ->where('op.payment_date', '<=', $endDate)
                        ->where('c.id', $Cso_Commission->cso['id'])
                        // ->get()->toArray();//->pluck('code', 'commission_percentage', 'temp_no');
                        ->first()->total_commission_30;
                    // dd([$order70, $order30]);
                    $commissionPerCso = (70 / 100 * $order70) + (30 / 100 * $order30);
                    // dd($commissionPerCso);


                    // $order70 = $Cso_Commission->cso->order_70_sales->where('orderDate', '>=', $startDate)->where('orderDate', '<=', $endDate);
                    // foreach ($order70 as $perOrder) {
                    //     $commissionPerCso += 70 / 100 * $perOrder->orderPayment->sum('commission_percentage');
                    // }
                    // $order30 = $Cso_Commission->cso->order_30_sales->where('orderDate', '>=', $startDate)->where('orderDate', '<=', $endDate);
                    // foreach ($order30 as $perOrder) {
                    //     $commissionPerCso += 30 / 100 * $perOrder->orderPayment->sum('commission_percentage');
                    // }

                    // cancel
                    $cancel70 = Order::selectRaw('IFNULL(SUM(nominal_cancel), 0) as total_cancel_70')
                                ->where('status', 'cancel')
                                ->where('orderDate', '>=', $startDate)
                                ->where('orderDate', '<=', $endDate)
                                ->where('70_cso_id', $Cso_Commission->cso['id'])
                                ->first()->total_cancel_70;
                    $cancel30 = Order::selectRaw('IFNULL(SUM(nominal_cancel), 0) as total_cancel_30')
                                ->where('status', 'cancel')
                                ->where('orderDate', '>=', $startDate)
                                ->where('orderDate', '<=', $endDate)
                                ->where('30_cso_id', $Cso_Commission->cso['id'])
                                ->first()->total_cancel_30;
                    $cancelPerCso = (70 / 100 * $cancel70) + (30 / 100 * $cancel30);
                }
                else{
                    $commissionPerCso = $Cso_Commission->commission;
                }

                $Cso_Commission['bonusPerCso'] = $bonusPerCso;
                $Cso_Commission['commissionPerCso'] = $commissionPerCso;
                $Cso_Commission['cancelPerCso'] = $cancelPerCso;

                $totalSaleBranch = Branch::from('branches as b')
                    ->selectRaw("SUM(ts.bank_in) as sum_ts_bank_in")
                    ->selectRaw("SUM(ts.netto_debit) as sum_ts_netto_debit")
                    ->selectRaw("SUM(ts.netto_card) as sum_ts_netto_card")
                    ->leftJoin('orders as o', 'o.branch_id', 'b.id')
                    ->leftJoin('order_payments as op', 'op.order_id', 'o.id')
                    ->leftJoin('total_sales as ts', 'ts.order_payment_id', 'op.id')
                    ->whereBetween('op.payment_date', [$startDate, $endDate])
                    ->where([['b.active', true], ['b.id', $request->input('filter_branch')]])
                    ->where([['o.active', true], ['o.status', '!=', 'reject']])
                    ->orderBy('b.code')
                    ->groupBy('b.id')->first();
                $totalSaleBranch = $totalSaleBranch['sum_ts_bank_in'] + $totalSaleBranch['sum_ts_netto_debit'] + $totalSaleBranch['sum_ts_netto_card'];
            }
        }

        return view('admin.list_csocommission', compact('startDate', 'endDate', 'branches', 'CsoCommissions', 'totalSaleBranch'));
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

    //old testing for all the cso include in 1 xls
	public function exportCsoCommission_old(Request $request){
		$startDate = $request->has('filter_month') ? date($request->input('filter_month').'-01') : date('Y-m-01');
        $endDate = date('Y-m-t', strtotime($startDate));
        $branch = $request->has('filter_branch') ? $request->input('filter_branch') : null;
        $CsoCommissions = null;

        if($branch){
	        $CsoCommissions = CsoCommission::select('cso_commissions.*')
	        	->where('cso_commissions.created_at', '>=', $startDate)
	        	->where('cso_commissions.created_at', '<=', $endDate)
	        	->where('cso_commissions.active', true)
	        	->leftJoin('csos', 'csos.id', '=', 'cso_commissions.cso_id')
	        	->leftJoin('branches', 'branches.id', '=', 'csos.branch_id')
	        	->where('branches.id', $branch)
	        	->get();

            foreach ($CsoCommissions as $key => $Cso_Commission) {
                $bonusPerCso = 0;
                $commissionPerCso = 0;

                if(count($Cso_Commission->orderCommission) > 0){
                    $bonusPerCso = $Cso_Commission->orderCommission->sum(function ($row) {return ($row->bonus + $row->upgrade + $row->smgt_nominal + $row->excess_price);});
                }

                //Khusus Untuk Commission Per CSO
                if($Cso_Commission->commission == 0){
                    $order70 = OrderPayment::from('order_payments as op')
                        ->selectRaw('IFNULL(SUM(op.commission_percentage), 0) as total_commission_70')
                        ->join('orders as o', 'o.id', 'op.order_id')
                        ->join('csos as c', 'c.id', 'o.70_cso_id')
                        ->where('op.status', 'verified')
                        ->where('op.payment_date', '>=', $startDate)
                        ->where('op.payment_date', '<=', $endDate)
                        ->where('c.id', $Cso_Commission->cso['id'])
                        ->first()->total_commission_70;

                    $order30 = OrderPayment::from('order_payments as op')
                        ->selectRaw('IFNULL(SUM(op.commission_percentage), 0) as total_commission_30')
                        ->join('orders as o', 'o.id', 'op.order_id')
                        ->join('csos as c', 'c.id', 'o.30_cso_id')
                        ->where('op.status', 'verified')
                        ->where('op.payment_date', '>=', $startDate)
                        ->where('op.payment_date', '<=', $endDate)
                        ->where('c.id', $Cso_Commission->cso['id'])
                        ->first()->total_commission_30;
                    $commissionPerCso = (70 / 100 * $order70) + (30 / 100 * $order30);
                }
                else{
                    $commissionPerCso = $Cso_Commission->commission;
                }

                $Cso_Commission['bonusPerCso'] = $bonusPerCso;
                $Cso_Commission['commissionPerCso'] = $commissionPerCso;
            }
        }

        return Excel::download(new CsoCommissionExport($CsoCommissions, $startDate, $endDate, $branch), 'Commission Report.xlsx');
	}

    public function exportCsoCommission(Request $request){
        $startDate = $request->has('filter_month') ? date($request->input('filter_month').'-01') : date('Y-m-01');
        $endDate = date('Y-m-t', strtotime($startDate));
        $branch = $request->has('filter_branch') ? $request->input('filter_branch') : null;
        $branchName = $request->has('filter_branch') ? Branch::find($request->input('filter_branch')) : "";
        $allCsoCommission = null;

        if($branch){
            $ordersNya = Order::from('orders as o')
                ->where('o.status', '!=', 'reject')
                ->where('o.orderDate', '>=', $startDate)
                ->where('o.orderDate', '<=', $endDate)
                ->select('c_30.id as c_30', 'c_70.id as c_70')
                ->leftJoin('csos as c_30', 'c_30.id', 'o.30_cso_id')
                ->leftJoin('csos as c_70', 'c_70.id', 'o.70_cso_id')
                ->where('o.branch_id', $branch)->get();
            $c_70 = $ordersNya->pluck('c_70')->toArray();
            $c_30 = $ordersNya->pluck('c_30')->toArray();
            $allCsoCommission = Cso::whereIn('id', array_unique(array_merge($c_70, $c_30)))->orderBy('code')->get();

            foreach ($allCsoCommission as $key => $perCsoCommission) {
                $bonusPerCso = 0;
                $commissionPerCso = 0;
                $cancelPerCso = 0;

                $bonusPerCso = $perCsoCommission->orderCommission->filter(function($valueNya, $keyNya) use ($startDate, $endDate, $branch, $perCsoCommission){
                    $perOr = $valueNya->order;
                    if($perOr['status'] != 'reject' && $perOr['branch_id'] == $branch){
                        // foreach ($perOr->orderPayment as $perPaymentNya) {
                        //     if($perPaymentNya['payment_date'] >= $startDate && $perPaymentNya['payment_date'] <= $endDate){
                        //         dd($perPaymentNya);
                        //     }
                        // }
                        // if($perOr->orderPayment->where('payment_date', '>=', $startDate)->where('payment_date', '<=', $endDate)->count() > 0){
                        //     return $valueNya;
                        // }
                        if($perOr->orderPayment->sortBy('payment_date')->last()['payment_date'] >= $startDate && $perOr->orderPayment->sortBy('payment_date')->last()['payment_date'] <= $endDate){
                            // dd($perOr->orderPayment->sortBy('payment_date')->last());
                            return $valueNya;
                        }
                    }
                })->sum(function ($row) {return ($row->bonus + $row->upgrade + $row->smgt_nominal + $row->excess_price);});

                $order70 = OrderPayment::from('order_payments as op')
                    ->selectRaw('IFNULL(SUM(op.commission_percentage), 0) as total_commission_70')
                    ->join('orders as o', 'o.id', 'op.order_id')
                    ->join('csos as c', 'c.id', 'o.70_cso_id')
                    ->where('op.status', 'verified')
                    ->where('op.payment_date', '>=', $startDate)
                    ->where('op.payment_date', '<=', $endDate)
                    ->where('o.branch_id', $branch)
                    ->where('c.id', $perCsoCommission['id'])
                    ->first()->total_commission_70;

                $order30 = OrderPayment::from('order_payments as op')
                    ->selectRaw('IFNULL(SUM(op.commission_percentage), 0) as total_commission_30')
                    ->join('orders as o', 'o.id', 'op.order_id')
                    ->join('csos as c', 'c.id', 'o.30_cso_id')
                    ->where('op.status', 'verified')
                    ->where('op.payment_date', '>=', $startDate)
                    ->where('op.payment_date', '<=', $endDate)
                    ->where('o.branch_id', $branch)
                    ->where('c.id', $perCsoCommission['id'])
                    ->first()->total_commission_30;
                $commissionPerCso = (70 / 100 * $order70) + (30 / 100 * $order30);

                //penjualan
                $penjualan70 = TotalSale::from('total_sales as ts')
                    ->selectRaw("IFNULL(SUM(ts.bank_in), 0) as sum_ts_bank_in")
                    ->selectRaw("IFNULL(SUM(ts.netto_debit), 0) as sum_ts_netto_debit")
                    ->selectRaw("IFNULL(SUM(ts.netto_card), 0) as sum_ts_netto_card")
                    ->leftJoin('order_payments as op', 'op.id', 'ts.order_payment_id')
                    ->leftJoin('orders as o', 'o.id', 'op.order_id')
                    ->where('op.status', 'verified')
                    ->where('op.payment_date', '>=', $startDate)
                    ->where('op.payment_date', '<=', $endDate)
                    ->where('o.branch_id', $branch)
                    ->where('o.70_cso_id', $perCsoCommission['id'])
                    ->first();

                $penjualan30 = TotalSale::from('total_sales as ts')
                    ->selectRaw("IFNULL(SUM(ts.bank_in), 0) as sum_ts_bank_in")
                    ->selectRaw("IFNULL(SUM(ts.netto_debit), 0) as sum_ts_netto_debit")
                    ->selectRaw("IFNULL(SUM(ts.netto_card), 0) as sum_ts_netto_card")
                    ->leftJoin('order_payments as op', 'op.id', 'ts.order_payment_id')
                    ->leftJoin('orders as o', 'o.id', 'op.order_id')
                    ->where('op.status', 'verified')
                    ->where('op.payment_date', '>=', $startDate)
                    ->where('op.payment_date', '<=', $endDate)
                    ->where('o.branch_id', $branch)
                    ->where('o.30_cso_id', $perCsoCommission['id'])
                    ->first();
                $total_penjualan = (70 / 100 * ($penjualan70['sum_ts_bank_in'] + $penjualan70['sum_ts_netto_debit'] + $penjualan70['sum_ts_netto_card'])) + (30 / 100 * ($penjualan30['sum_ts_bank_in'] + $penjualan30['sum_ts_netto_debit'] + $penjualan30['sum_ts_netto_card']));

                // cancel old
                // $cancel70 = Order::selectRaw('IFNULL(SUM(nominal_cancel), 0) as total_cancel_70')
                //             ->where('status', 'cancel')
                //             ->where('orderDate', '>=', $startDate)
                //             ->where('orderDate', '<=', $endDate)
                //             ->where('branch_id', $branch)
                //             ->where('70_cso_id', $perCsoCommission['id'])
                //             ->first()->total_cancel_70;
                // $cancel30 = Order::selectRaw('IFNULL(SUM(nominal_cancel), 0) as total_cancel_30')
                //             ->where('status', 'cancel')
                //             ->where('orderDate', '>=', $startDate)
                //             ->where('orderDate', '<=', $endDate)
                //             ->where('branch_id', $branch)
                //             ->where('30_cso_id', $perCsoCommission['id'])
                //             ->first()->total_cancel_30;
                // $cancelPerCso = (70 / 100 * $cancel70) + (30 / 100 * $cancel30);
                // $perCsoCommission['cancelPerCso'] = $cancel70;

                //new CANCEL
                $perCsoCommission['cancelPerCso'] = OrderCancel::selectRaw('IFNULL(SUM(order_cancels.nominal_cancel), 0) as total_cancel')
                    ->leftJoin('orders', 'orders.id', 'order_cancels.order_id')
                    ->where('order_cancels.cancel_date', '>=', $startDate)
                    ->where('order_cancels.cancel_date', '<=', $endDate)
                    ->where(function($q) use($branch, $perCsoCommission) {
                        $q->where(function($p) use($branch, $perCsoCommission) {
                            $p->where('orders.branch_id', $branch)
                                ->where('orders.70_cso_id', $perCsoCommission['id']);
                        })
                        ->orWhere(function($p) use($branch, $perCsoCommission) {
                            $p->where('order_cancels.branch_id', $branch)
                                ->where('order_cancels.cso_id', $perCsoCommission['id']);
                        });
                    })->first()->total_cancel;

                $perCsoCommission['total_sales'] = $total_penjualan;
                $perCsoCommission['bonusPerCso'] = $bonusPerCso;
                $perCsoCommission['commissionPerCso'] = $commissionPerCso;
            }
        }

        return Excel::download(new CsoCommissionEachExport($allCsoCommission, $startDate, $endDate, $branch), 'Commission Report Periode '. date('m-Y', strtotime($startDate)) .' ('. $branchName['code'] .').xlsx');
    }

    //not done
    public function exportBonusCommission(Request $request){
        $startDate = $request->has('filter_month') ? date($request->input('filter_month').'-01') : date('Y-m-01');
        $endDate = date('Y-m-t', strtotime($startDate));
        $allCsoCommission = null;

        $allBranches = Branch::from('branches as b')
            ->select('b.*')
            ->leftJoin('orders as o', 'o.branch_id', 'b.id')
            ->leftJoin('order_payments as op', 'op.order_id', 'o.id')
            ->leftJoin('total_sales as ts', 'ts.order_payment_id', 'op.id')
            ->whereBetween('op.payment_date', [$startDate, $endDate])
            ->where('b.active', true)
            ->where('o.active', true)
            ->orderBy('b.code')
            ->groupBy('b.id')->get();

        foreach ($allBranches as $perBranch) {
            $ordersNya = Order::from('orders as o')
                ->where('o.status', '!=', 'reject')
                ->where('o.orderDate', '>=', $startDate)
                ->where('o.orderDate', '<=', $endDate)
                ->select('c_30.id as c_30', 'c_70.id as c_70')
                ->leftJoin('csos as c_30', 'c_30.id', 'o.30_cso_id')
                ->leftJoin('csos as c_70', 'c_70.id', 'o.70_cso_id')
                ->where('o.branch_id', $perBranch['id'])->get();
            $c_70 = $ordersNya->pluck('c_70')->toArray();
            $c_30 = $ordersNya->pluck('c_30')->toArray();
            $allCsoCommission = Cso::whereIn('id', array_unique(array_merge($c_70, $c_30)))->orderBy('code')->get();

            foreach ($allCsoCommission as $key => $perCsoCommission) {
                $bonusPerCso = 0;
                $commissionPerCso = 0;

                $bonusPerCso = $perCsoCommission->orderCommission->filter(function($valueNya, $keyNya) use ($startDate, $endDate, $perBranch){
                    $perOr = $valueNya->order;
                    if($perOr['orderDate'] >= $startDate && $perOr['orderDate'] <= $endDate && $perOr['status'] != 'reject' && $perOr['branch_id'] == $perBranch['id']){
                        return $valueNya;
                    }
                })->sum(function ($row) {return ($row->bonus + $row->upgrade + $row->smgt_nominal + $row->excess_price);});

                $order70 = OrderPayment::from('order_payments as op')
                    ->selectRaw('IFNULL(SUM(op.commission_percentage), 0) as total_commission_70')
                    ->join('orders as o', 'o.id', 'op.order_id')
                    ->join('csos as c', 'c.id', 'o.70_cso_id')
                    ->where('op.status', 'verified')
                    ->where('op.payment_date', '>=', $startDate)
                    ->where('op.payment_date', '<=', $endDate)
                    ->where('o.branch_id', $perBranch['id'])
                    ->where('c.id', $perCsoCommission['id'])
                    ->first()->total_commission_70;

                $order30 = OrderPayment::from('order_payments as op')
                    ->selectRaw('IFNULL(SUM(op.commission_percentage), 0) as total_commission_30')
                    ->join('orders as o', 'o.id', 'op.order_id')
                    ->join('csos as c', 'c.id', 'o.30_cso_id')
                    ->where('op.status', 'verified')
                    ->where('op.payment_date', '>=', $startDate)
                    ->where('op.payment_date', '<=', $endDate)
                    ->where('o.branch_id', $perBranch['id'])
                    ->where('c.id', $perCsoCommission['id'])
                    ->first()->total_commission_30;
                $commissionPerCso = (70 / 100 * $order70) + (30 / 100 * $order30);

                $perCsoCommission['bonusPerCso'] = $bonusPerCso;
                $perCsoCommission['commissionPerCso'] = $commissionPerCso;
            }
            $perBranch['cso'] = $allCsoCommission;
        }
        $cso_id = 70;
        dd($allBranches->filter(function ($valueNya, $keyNya) use ($cso_id)
        {
            if($valueNya['cso']->where('id', $cso_id)){
                return $valueNya;
            }
        }));

        return Excel::download(new CsoCommissionEachExport($allCsoCommission, $startDate, $endDate, $branch), 'Commission Report Periode '. date('m-Y', strtotime($startDate)) .' ('. $branchName['code'] .').xlsx');
    }

    public function cutCommissionOrder(Request $request)
    {
        DB::beginTransaction();
        try {
            $startDate = $request->has('commissionCut-month') ? date($request->input('commissionCut-month').'-01') : date('Y-m-01');
            $endDate = date('Y-m-t', strtotime($startDate));
            $branch = $request->has('commissionCut-branch') ? $request->input('commissionCut-branch') : null;
            $cutCommissionPercentage = $request->has('commissionCut-cutPercentage') ? $request->input('commissionCut-cutPercentage') : 0;
            $totalSaleMinimal = $request->has('commissionCut-minimalTotalSale') ? $request->input('commissionCut-minimalTotalSale') : 0;
            $totalSaleBranch = $request->has('commissionCut-totalSaleBranch') ? $request->input('commissionCut-totalSaleBranch') : 0;

            // update order commission (recalculate to cut by request)
            if($totalSaleBranch < $totalSaleMinimal){
                // get order commission data
                $orderCommissions = OrderCommission::from('order_commissions as oc')
                    ->select('oc.*')
                    ->join('orders as o', 'o.id', 'oc.order_id')
                    ->where('o.status', '!=', 'reject')
                    ->where('o.orderDate', '>=', $startDate)
                    ->where('o.orderDate', '<=', $endDate)
                    ->where('o.branch_id', $branch)
                    ->where('o.active', true)
                    ->orderBy('o.id', 'asc')
                    ->get();

                // array for percentage calculation cut commission
                foreach($orderCommissions as $idx => $perOrderCommission){
                    // update order commission
                    $perOrderCommission->bonus = $perOrderCommission->bonus - ( $cutCommissionPercentage * $perOrderCommission->bonus / 100 );
                    $perOrderCommission->upgrade = $perOrderCommission->upgrade - ( $cutCommissionPercentage * $perOrderCommission->upgrade / 100 );
                    $perOrderCommission->remarks = "Cut Commission ". $cutCommissionPercentage ."% below than minimal Total Sale Rp. ". number_format($totalSaleMinimal);
                    $perOrderCommission->update();
                }
            }

            DB::commit();
            return Redirect::back()->with("success", "Order commission successfully updated.");
        } catch (\Exception $ex) {
            DB::rollBack();
            return Redirect::back()->withErrors("Something wrong when update order commission type, please call Team IT. [". $ex->getMessage() ."]");
        }
    }
}
