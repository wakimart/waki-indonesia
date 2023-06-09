<?php

namespace App\Http\Controllers;

use App\BankAccount;
use App\FinancialRoutine;
use App\FinancialRoutineTransaction;
use App\HistoryUpdate;
use App\Order;
use App\OrderPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class FinancialRoutineController extends Controller
{
    public function index(Request $request)
    {
        $banks = BankAccount::leftjoin('branches','branches.bank_account_id', '=','bank_accounts.id' )
            ->where([['bank_accounts.active', true], ['branches.bank_account_id', '=', null]])
            ->select('bank_accounts.*')->get();
        $financialRoutines = FinancialRoutine::where('active', true)
            ->whereIn('bank_account_id', $banks->pluck('id')->toArray());

        if ($request->filter_date) {
            $financialRoutines->whereBetween('routine_date', [
                date('Y-m-01', strtotime($request->filter_date)),
                date('Y-m-t', strtotime($request->filter_date)),
            ]);
        }
        $currentBank="";
        if ($request->filter_bank) {
            $currentBank = BankAccount::where("code", $request->filter_bank)->first();
            $financialRoutines->where('bank_account_id', $currentBank['id']);
        }

        $financialRoutines = $financialRoutines->orderBy('created_at', 'desc')->paginate(10);
        
        return view('admin.list_financialroutine', compact('banks', 'financialRoutines', 'currentBank'));
    }

    public function create(Request $request)
    {
        $banks = BankAccount::leftjoin('branches','branches.bank_account_id', '=','bank_accounts.id' )
                ->where([['bank_accounts.active', true], ['branches.bank_account_id', '=', null]])
                ->select('bank_accounts.*')->get();
        $banksPettyCash = BankAccount::where('active', true)->get();
        return view('admin.add_financialroutine', compact('banks', 'banksPettyCash'));
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'bank' => 'required|exists:bank_accounts,id',
            'last_financial_routine_id' => 'required|exists:financial_routines,id',
            'bank_interest' => 'required',
            'etc_in' => 'required',
            'bank_tax' => 'required',
            'etc_out' => 'required',
            'remains_sale' => 'required',
            'routine_date' => [
                'required', 
                Rule::unique('financial_routines')
                    ->where('active', true)
                    ->where(function($query) use ($request) {
                        $query->whereBetween('routine_date', [
                            date('Y-m-01', strtotime($request->routine_date)),
                            date('Y-m-t', strtotime($request->routine_date)),
                        ]);
                    })
                    ->where('bank_account_id', $request->bank)
            ],
        ]);
        if($validator->fails()){
            $arr_Errors = $validator->errors()->all();
            $arr_Keys = $validator->errors()->keys();
            $arr_Hasil = [];
            for ($i = 0; $i < count($arr_Keys); $i++) {
                $arr_Hasil[$arr_Keys[$i]] = $arr_Errors[$i];
            }
            return response()->json(['errors' => $arr_Hasil]);
        }
        DB::beginTransaction();
        try {
            $user = Auth::user();
            $financialRoutine = new FinancialRoutine();
            $financialRoutine->routine_date = $request->routine_date;
            $financialRoutine->bank_account_id = $request->bank;
            $financialRoutine->financial_routine_id = $request->last_financial_routine_id;
            $financialRoutine->total_sale = $request->total_sale;
            $financialRoutine->bank_interest = $request->bank_interest;
            $financialRoutine->bank_tax = $request->bank_tax;
            $financialRoutine->etc_in = $request->etc_in;
            $financialRoutine->etc_out = $request->etc_out;
            $financialRoutine->remains_sales = $request->remains_sale;
            $financialRoutine->description = $request->description;
            $financialRoutine->user_id = $user->id;
            $financialRoutine->save();

            $arr_fr_tdetails = $request->arr_fr_tdetails ?? [];
            foreach ($arr_fr_tdetails as $arr_fr_detail) {
                $frTransaction = new FinancialRoutineTransaction();
                $frTransaction->transaction_date = $request->input('tdetail_date_' . $arr_fr_detail);
                $frTransaction->bank_account_id = $request->input('tdetail_bank_' . $arr_fr_detail);
                $frTransaction->transaction = $request->input('tdetail_nominal_' . $arr_fr_detail);
                $frTransaction->description = $request->input('tdetail_description_' . $arr_fr_detail);
                $frTransaction->financial_routine_id = $financialRoutine->id;
                $frTransaction->user_id = $user->id;
                $frTransaction->save();
            }

            // Update Remains Saldo
            $financialRoutine->updateRemainsSaldo();
            $financialRoutine->save();

            DB::commit();
            return response()->json(['success' => $financialRoutine]);
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json(["errors" => $ex->getMessage()], 500);
        }
    }

    public function show(Request $request)
    {
        if (empty($request->id)) {
            return redirect()
                ->route("list_financial_routine")
                ->with("danger", "Data not found.");
        }

        $banks = BankAccount::where('active', true)->get();
        $financialRoutine = FinancialRoutine::where('id', $request->id)->first();
        $historyUpdateFR = HistoryUpdate::leftjoin('users','users.id', '=','history_updates.user_id' )
        ->select('history_updates.method', 'history_updates.created_at','history_updates.meta as meta' ,'users.name as name')
        ->where('type_menu', 'Financial Routine')->where('menu_id', $financialRoutine->id)->get();

        return view('admin.detail_financialroutine', compact('banks', 'financialRoutine', 'historyUpdateFR'));
    }

    public function edit(Request $request)
    {
        if (empty($request->id)) {
            return redirect()
                ->route("list_financial_routine")
                ->with("danger", "Data not found.");
        }

        $banks = BankAccount::leftjoin('branches','branches.bank_account_id', '=','bank_accounts.id' )
            ->where([['bank_accounts.active', true], ['branches.bank_account_id', '=', null]])
            ->select('bank_accounts.*')->get();
        $banksPettyCash = BankAccount::where('id', 7)->get();
        $financialRoutine = FinancialRoutine::where('id', $request->id)->first();

        return view('admin.edit_financialroutine', compact('banks', 'financialRoutine', 'banksPettyCash'));
    }

    public function update(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'idFinancialRoutine' => 'required|exists:financial_routines,id',
            'bank' => 'required|exists:bank_accounts,id',
            'last_financial_routine_id' => 'required|exists:financial_routines,id',
            'bank_interest' => 'required',
            'etc_in' => 'required',
            'bank_tax' => 'required',
            'etc_out' => 'required',
            'remains_sale' => 'required',
            'routine_date' => [
                'required', 
                Rule::unique('financial_routines')
                    ->where(function($query) use ($request){
                        $query->where('id', '!=', $request->idFinancialRoutine);
                    })
                    ->where('active', true)
                    ->where(function($query) use ($request) {
                        $query->whereBetween('routine_date', [
                            date('Y-m-01', strtotime($request->routine_date)),
                            date('Y-m-t', strtotime($request->routine_date)),
                        ]);
                    })
                    ->where('bank_account_id', $request->bank)
            ],
        ]);
        if($validator->fails()){
            $arr_Errors = $validator->errors()->all();
            $arr_Keys = $validator->errors()->keys();
            $arr_Hasil = [];
            for ($i = 0; $i < count($arr_Keys); $i++) {
                $arr_Hasil[$arr_Keys[$i]] = $arr_Errors[$i];
            }
            return response()->json(['errors' => $arr_Hasil]);
        }
        DB::beginTransaction();
        try {
            $user = Auth::user();
            $financialRoutine = FinancialRoutine::find($request->idFinancialRoutine);
            $dataBefore = FinancialRoutine::find($request->idFinancialRoutine);
            $financialRoutine->routine_date = $request->routine_date;
            $financialRoutine->bank_account_id = $request->bank;
            $financialRoutine->financial_routine_id = $request->last_financial_routine_id;
            $financialRoutine->total_sale = $request->total_sale;
            $financialRoutine->bank_interest = $request->bank_interest;
            $financialRoutine->bank_tax = $request->bank_tax;
            $financialRoutine->etc_in = $request->etc_in;
            $financialRoutine->etc_out = $request->etc_out;
            $financialRoutine->remains_sales = $request->remains_sale;
            $financialRoutine->description = $request->description;
            $financialRoutine->user_id = $user->id;
            $financialRoutine->save();

            $financialRoutineTransactions = FinancialRoutineTransaction::where('financial_routine_id', $financialRoutine->id)->get();

            $arr_fr_tdetails = $request->arr_fr_tdetails ?? [];
            foreach ($arr_fr_tdetails as $arr_fr_detail) {
                // Create or Update Financial Routine Transaction
                if ($request->input('fr_tdetail_id_' . $arr_fr_detail)) {
                    $frTransaction = FinancialRoutineTransaction::find($request->input('fr_tdetail_id_' . $arr_fr_detail));
                } else {
                    $frTransaction = new FinancialRoutineTransaction();
                }
                $frTransaction->transaction_date = $request->input('tdetail_date_' . $arr_fr_detail);
                $frTransaction->bank_account_id = $request->input('tdetail_bank_' . $arr_fr_detail);
                $frTransaction->transaction = $request->input('tdetail_nominal_' . $arr_fr_detail);
                $frTransaction->description = $request->input('tdetail_description_' . $arr_fr_detail);
                $frTransaction->financial_routine_id = $financialRoutine->id;
                $frTransaction->user_id = $user->id;
                $frTransaction->save();
            }

            // Hapus Old Financial Routine Transaction
            $diff_id_frTransaction = array_diff($financialRoutineTransactions->pluck('id')->toArray(), ($request->fr_tdetail_id ?? []));
            if ($diff_id_frTransaction) {
                FinancialRoutineTransaction::where('financial_routine_id', $financialRoutine->id)
                    ->whereIn('id', $diff_id_frTransaction)->delete();
            }

            // Update Remains Saldo
            $financialRoutine->updateRemainsSaldo();
            $financialRoutine->save();

            $dataChanges = array_diff(json_decode($financialRoutine, true), json_decode($dataBefore, true));
            $key = "financialRoutineTransaction";
            $orderChild = $financialRoutine->$key->keyBy('id');
            $child = $financialRoutineTransactions->keyBy('id');
            foreach ($child as $i=>$c) {
                $array_diff_c = isset($orderChild[$i]) 
                    ? array_diff(json_decode($orderChild[$i], true), json_decode($c, true)) 
                    : "deleted";
                if ($array_diff_c == "deleted") {
                    $dataChanges[$key][$c['id']."_deleted"] = $c;
                } else if ($array_diff_c) {
                    $dataChanges[$key][$c['id']] = $array_diff_c;
                }
            }
            // if ($orderChild > $child) {
                $array_diff_c = array_diff($orderChild->pluck('id')->toArray(), $child->pluck('id')->toArray());
                if ($array_diff_c) {
                    $dataChanges[$key]["added"] = $array_diff_c;
                }
            // }

            $historyUpdate['type_menu'] = "Financial Routine";
            $historyUpdate['method'] = "Update";
            $historyUpdate['meta'] = json_encode([
                'user'=>$user['id'],
                'createdAt' => date("Y-m-d h:i:s"),
                'dataChange'=> $dataChanges
            ]);

            $historyUpdate['user_id'] = $user['id'];
            $historyUpdate['menu_id'] = $financialRoutine->id;
            $createData = HistoryUpdate::create($historyUpdate);

            DB::commit();
            return response()->json(['success' => "Financial Routine successfully added."]);
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json(["errors" => $ex->getMessage()], 500);
        }
    }

    public function destroy(Request $request)
    {
        if (empty($request->id)) {
            return redirect()
                ->route("list_financial_routine")
                ->with("danger", "Data not found.");
        }

        DB::beginTransaction();

        try {
            $financialRoutine = FinancialRoutine::where("id", $request->id)->first();
            $financialRoutine->active = false;
            $financialRoutine->save();

            DB::commit();

            $user = Auth::user();
            $historyUpdate= [];
            $historyUpdate['type_menu'] = "Financial Routine";
            $historyUpdate['method'] = "Delete";
            $dataChanges = array('active' => $financialRoutine->active);
            $historyUpdate['meta'] = json_encode(['user'=>$user['id'],'createdAt' => date("Y-m-d h:i:s"), 'dataChange'=> $dataChanges]);
            $historyUpdate['user_id'] = $user['id'];
            $historyUpdate['menu_id'] = $financialRoutine->id;
            $createData = HistoryUpdate::create($historyUpdate);

            return redirect()
                ->route("list_financial_routine")
                ->with("success", "Financial Routine successfully deleted.");
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->route("list_financial_routine")
                ->with("danger", $e->getMessage());
        }
    }

    public function print(Request $request)
    {
        if (empty($request->id)) {
            return redirect()
                ->route("list_financial_routine")
                ->with("danger", "Data not found.");
        }

        $financialRoutine = FinancialRoutine::find($request->id);

        $startDate = date('Y-m-01', strtotime($financialRoutine->routine_date));
        $endDate = date('Y-m-d', strtotime($financialRoutine->routine_date));
        $total_sales_temp = OrderPayment::from('order_payments as op')
            ->select('op.*', 'o.code as o_code', 'c.code as c_code', 'c.name as c_name'
                , 'br.id as br_id', 'br.code as br_code', 'br.name as br_name'
                , 'bacc.id as bacc_id', 'bacc.code as bacc_code', 'bacc.name as bacc_name', 'bacc.account_number as bacc_account_number', 'bacc.estimate_transfer as bacc_estimate_transfer'
                , 'ts.bank_in as ts_bank_in'
                , 'ts.debit as ts_debit'
                , 'ts.netto_debit as ts_netto_debit'
                , 'ts.card as ts_card'
                , 'ts.netto_card as ts_netto_card'
            )
            ->join('orders as o', 'o.id', 'op.order_id')
            ->join('total_sales as ts', 'ts.order_payment_id', 'op.id')
            ->join('csos as c', 'c.id', 'o.Cso_id')
            ->join('branches as br', 'br.id', 'o.branch_id')
            ->join('bank_accounts as bacc', 'bacc.id', 'op.bank_account_id')
            ->whereBetween('op.payment_date', [$startDate, $endDate])
            ->where('op.bank_account_id', $financialRoutine->bank_account_id)
            ->where('o.active', true)
            ->where('o.status', '!=', 'reject')
            ->orderBy('op.payment_date')
            ->groupBy('op.id')
            ->get();

        $total_sales = [];
        foreach ($total_sales_temp as $ts_temp) {
            if (!isset($total_sales[$ts_temp['br_id']])) 
                $total_sales[$ts_temp['br_id']] = $ts_temp->toArray();
            $total_sales[$ts_temp['br_id']]['orders'][] = $ts_temp;
        }

        return view('admin.print_financialroutine', compact('financialRoutine', 'total_sales'));
    }

    public function reportAllBankBranch(Request $request)
    {
        $financialRoutines  = [];
        if ($request->filter_date != null && $request->filter_fr_by != null) {
            $filter_fr_by = $request->filter_fr_by == 'bank' ? '=' : '!=';
            $banks = BankAccount::leftjoin('branches','branches.bank_account_id', '=','bank_accounts.id' )
                ->where([['bank_accounts.active', true], ['branches.bank_account_id', $filter_fr_by, null]])
                ->select('bank_accounts.*')->get();
            $financialRoutines = FinancialRoutine::select('financial_routines.*')
                ->where('financial_routines.active', true)
                ->whereIn('financial_routines.bank_account_id', $banks->pluck('id')->toArray())
                ->whereBetween('financial_routines.routine_date', [
                    date('Y-m-01', strtotime($request->filter_date)),
                    date('Y-m-t', strtotime($request->filter_date)),
                ])
                ->join('bank_accounts', 'bank_accounts.id', 'financial_routines.bank_account_id')
                ->orderBy('bank_accounts.code')
                ->get();
        }
        return view('admin.report_all_bankbranch_financialroutine', compact('financialRoutines'));
    }

    public function checkRoutineDate(Request $request)
    {
        if ($request->routine_date && $request->bank) {
            $financialRoutine = FinancialRoutine::where('active', true)
                ->whereBetween('routine_date', [
                    date('Y-m-01', strtotime($request->routine_date)),
                    date('Y-m-t', strtotime($request->routine_date)),
                ])
                ->where('bank_account_id', $request->bank);
            
            $idFinancialRoutine = null;
            if ($request->idFinancialRoutine) {
                $idFinancialRoutine = $request->idFinancialRoutine;
                $financialRoutine->where('id', '!=', $idFinancialRoutine);
            }

            $financialRoutine = $financialRoutine->first();
            if (!$financialRoutine) {
                $getTotalSale = self::getTotalSale($request->routine_date, $request->bank, $idFinancialRoutine);
                $lastMonthFinancialRoutine = $getTotalSale['lastMonthFinancialRoutine'];
                $totalSale = $getTotalSale['totalSale'];
                
                return response()->json([
                    "status" => "success",
                    "lastMonthFinancialRoutine" => $lastMonthFinancialRoutine,
                    "totalSale" => $totalSale,
                ], 200);
            } else {
                return response()->json([
                    "same_data" => "The Financial Routine data already exists."
                ], 200); 
            }
        }

        return response()->json([
            "status" => "error",
            "data" => "Error Request Parameter",
        ], 200); 
    }

    public static function getTotalSale($routine_date, $bank, $idFinancialRoutine)
    {
        $startDate = date('Y-m-01', strtotime($routine_date));
        $endDate = date('Y-m-d', strtotime($routine_date));
        $lastMonthStartDate = date('Y-m-d', strtotime($routine_date . " first day of last month"));
        $lastMonthEndDate = date('Y-m-d', strtotime($routine_date . " last day of last month"));
        $lastMonthFinancialRoutine = FinancialRoutine::where('active', true)
            ->whereBetween('routine_date', [$lastMonthStartDate, $lastMonthEndDate])
            ->where('bank_account_id', $bank);
        if ($idFinancialRoutine != null) $lastMonthFinancialRoutine->where('id', '!=', $idFinancialRoutine);
        $lastMonthFinancialRoutine = $lastMonthFinancialRoutine ->first();
        if ($lastMonthFinancialRoutine) {
            $totalSale = Order::from('orders as o')
                ->selectRaw("(
                    IFNULL(SUM(ts.bank_in), 0) + 
                    IFNULL(SUM(ts.debit), 0) + 
                    IFNULL(SUM(ts.card), 0)
                    ) as total_sale")
                ->join('order_payments as op', 'o.id', 'op.order_id')
                ->join('total_sales as ts', 'ts.order_payment_id', 'op.id')
                ->whereBetween('op.payment_date', [$startDate, $endDate])
                ->where('op.bank_account_id', $bank)
                ->where('op.status', '!=', 'rejected')
                ->where('o.active', true)->first();
            return [
                'lastMonthFinancialRoutine' => $lastMonthFinancialRoutine, 
                'totalSale' => $totalSale
            ];
        } else {
            throw new \Exception('No Data Found for Last Month Financial Routine');
        }
    }
}
