<?php

namespace App\Http\Controllers;

use App\BankAccount;
use App\Branch;
use App\FinancialRoutine;
use App\HistoryUpdate;
use App\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FinancialRoutineBranchController extends Controller
{
    public function index(Request $request)
    {
        $banks = BankAccount::leftjoin('branches','branches.bank_account_id', '=','bank_accounts.id' )
                ->where([['bank_accounts.active', true], ['branches.bank_account_id', '!=', null]])
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
                ->where([['bank_accounts.active', true], ['branches.bank_account_id', '!=', null]])
                ->select('bank_accounts.*')->get();
        $banksPettyCash = BankAccount::leftjoin('branches','branches.bank_account_id', '=','bank_accounts.id' )
                ->where([['bank_accounts.active', true], ['branches.bank_account_id', '=', null]])
                ->select('bank_accounts.*')->get();

        return view('admin.add_financialroutine', compact('banks', 'banksPettyCash'));
    }

    public function show(Request $request)
    {
        if (empty($request->id)) {
            return redirect()
                ->route("list_financial_routine_branch")
                ->with("danger", "Data not found.");
        }

        $banks = BankAccount::leftjoin('branches','branches.bank_account_id', '=','bank_accounts.id' )
                ->where([['bank_accounts.active', true], ['branches.bank_account_id', '=', null], ['bank_accounts.id', '!=', 7]])
                ->select('bank_accounts.*')->get();
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
                ->route("list_financial_routine_branch")
                ->with("danger", "Data not found.");
        }

        $banks = BankAccount::leftjoin('branches','branches.bank_account_id', '=','bank_accounts.id' )
                ->where([['bank_accounts.active', true], ['branches.bank_account_id', '!=', null]])
                ->select('bank_accounts.*')->get();
        $banksPettyCash = BankAccount::leftjoin('branches','branches.bank_account_id', '=','bank_accounts.id' )
                ->where([['bank_accounts.active', true], ['branches.bank_account_id', '=', null]])
                ->select('bank_accounts.*')->get();
        $financialRoutine = FinancialRoutine::where('id', $request->id)->first();
        $totalSale = self::getTotalSale($financialRoutine['routine_date'], $financialRoutine['bank_account_id'], $financialRoutine['id'])['totalSale']['total_sale'];

        return view('admin.edit_financialroutine', compact('banks', 'financialRoutine', 'banksPettyCash', 'totalSale'));
    }

    public function destroy(Request $request)
    {
        if (empty($request->id)) {
            return redirect()
                ->route("list_financial_routine_branch")
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
                ->route("list_financial_routine_branch")
                ->with("success", "Financial Routine successfully deleted.");
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->route("list_financial_routine_branch")
                ->with("danger", $e->getMessage());
        }
    }

    public function print(Request $request)
    {
        if (empty($request->id)) {
            return redirect()
                ->route("list_financial_routine_branch")
                ->with("danger", "Data not found.");
        }

        $financialRoutine = FinancialRoutine::find($request->id);

        $startDate = date('Y-m-01', strtotime($financialRoutine->routine_date));
        $endDate = date('Y-m-d', strtotime($financialRoutine->routine_date));
        $total_sales_temp = Branch::from('branches as br')
            ->select('br.*'
                , 'b.id as b_id'
                , 'b.code as b_code'
                , 'b.name as b_name'
                , 'b.estimate_transfer as b_estimate_transfer'
                , 'o.id as o_id'
                , 'o.code as o_code'
                , 'o.created_at as o_created_at'
                , 'op.payment_date as op_payment_date'
                , 'op.estimate_transfer_date as op_estimate_transfer_date'
                , DB::raw('CONCAT(c.code," - ",c.name) as c_name')
                , 'ts.bank_in as ts_bank_in'
                , 'ts.debit as ts_debit'
                , 'ts.netto_debit as ts_netto_debit'
                , 'ts.card as ts_card'
                , 'ts.netto_card as ts_netto_card')
            ->join('orders as o', 'o.branch_id', 'br.id')
            ->join('order_payments as op', 'op.order_id', 'o.id')
            ->join('bank_accounts as b', 'b.id', 'op.bank_account_id')
            ->join('total_sales as ts', 'ts.order_payment_id', 'op.id')
            ->leftjoin('csos as c', 'c.id', 'o.cso_id')
            ->whereBetween('op.payment_date', [$startDate, $endDate])
            ->where('op.status', '!=', 'rejected')
            ->where('o.active', true)
            ->groupBy('br.id', 'b.id', 'op.id')
            ->orderBy('br.code', 'asc')
            ->orderBy('b.code', 'asc')
            ->orderBy('op.payment_date', 'asc')->get();

        $total_sales = [];

        foreach ($total_sales_temp as $ts_temp) {
        	if($ts_temp['id'] != $financialRoutine->bankAccount->branch['id'])
        		continue;
            if (!isset($total_sales[$ts_temp['id']])) 
                $total_sales[$ts_temp['id']] = $ts_temp->toArray();
            if (!isset($total_sales[$ts_temp['id']]['banks'][$ts_temp['b_id']])) 
                $total_sales[$ts_temp['id']]['banks'][$ts_temp['b_id']] = $ts_temp->toArray();
            $total_sales[$ts_temp['id']]['banks'][$ts_temp['b_id']]['orders'][] = $ts_temp;
        }
        return view('admin.print_financialroutine', compact('financialRoutine', 'total_sales'));
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
                ->where('op.status', '!=', 'rejected')
                ->where('o.branch_id', $lastMonthFinancialRoutine->bankAccount->branch['id'])
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
