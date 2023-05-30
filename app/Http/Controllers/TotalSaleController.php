<?php

namespace App\Http\Controllers;

use App\BankAccount;
use App\Branch;
use App\Cso;
use App\Exports\TotalSaleByBankExport;
use App\Exports\TotalSaleByBranchExport;
use App\Exports\TotalSaleReportBranchExport;
use App\Exports\TotalSaleReportCsoExport;
use App\Order;
use App\OrderPayment;
use App\TotalSale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class TotalSaleController extends Controller
{
    public static function insertTotalSale($order_payment_id)
    {
        $orderPayment = OrderPayment::find($order_payment_id);
        $totalSale = new TotalSale();
        $totalSale->order_payment_id = $order_payment_id;

        if ($orderPayment->type_payment == "cash") {
            $totalSale->bank_in = $orderPayment->total_payment;
        } else if ($orderPayment->type_payment == "debit") {
            $totalSale->debit = $orderPayment->total_payment;
            $totalSale->netto_debit = $orderPayment->total_payment - ($orderPayment->total_payment * ($orderPayment->charge_percentage_company + $orderPayment->charge_percentage_bank) / 100);
        } else if ($orderPayment->type_payment == "card" || $orderPayment->type_payment == "card installment") {
            $totalSale->card = $orderPayment->total_payment;
            $totalSale->netto_card = $orderPayment->total_payment - ($orderPayment->total_payment * ($orderPayment->charge_percentage_company + $orderPayment->charge_percentage_bank) / 100);
        }

        $totalSale->created_at = $orderPayment->created_at;
        $totalSale->updated_at = $orderPayment->created_at;
        $totalSale->save();
    }

    public static function updateTotalSale($order_payment_id)
    {
        $orderPayment = OrderPayment::find($order_payment_id);
        $totalSale = TotalSale::where('order_payment_id', $order_payment_id)->first() ?? new TotalSale();
        $totalSale->order_payment_id = $order_payment_id;

        $totalSale->bank_in = 0;
        $totalSale->debit = 0;
        $totalSale->netto_debit = 0;
        $totalSale->card = 0;
        $totalSale->netto_card = 0;
        if ($orderPayment->type_payment == "cash") {
            $totalSale->bank_in = $orderPayment->total_payment;
        } else if ($orderPayment->type_payment == "debit") {
            $totalSale->debit = $orderPayment->total_payment;
            $totalSale->netto_debit = $orderPayment->total_payment - ($orderPayment->total_payment * ($orderPayment->charge_percentage_company + $orderPayment->charge_percentage_bank) / 100);
        } else if ($orderPayment->type_payment == "card" || $orderPayment->type_payment == "card installment") {
            $totalSale->card = $orderPayment->total_payment;
            $totalSale->netto_card = $orderPayment->total_payment - ($orderPayment->total_payment * ($orderPayment->charge_percentage_company + $orderPayment->charge_percentage_bank) / 100);
        }

        $totalSale->created_at = $orderPayment->created_at;
        $totalSale->updated_at = $orderPayment->created_at;
        $totalSale->save();
    }

    public static function deleteTotalSale($order_payment_id)
    {
        DB::beginTransaction();
        try {
            $orderPayment = OrderPayment::find($order_payment_id);
            $totalSale = TotalSale::where('order_payment_id', $order_payment_id)->first();
            if ($totalSale) {
                $totalSale->delete();
            }
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            throw new \ErrorException($ex->getMessage());
        }
    }

    public function listTotalSale(Request $request)
    {
        $startDate = date('Y-m-01');
        if ($request->has('filter_start_date')) {
            $startDate = date('Y-m-d', strtotime($request->filter_start_date));
        }
        $endDate = date('Y-m-d');
        if ($request->has('filter_end_date')) {
            $endDate = date('Y-m-d', strtotime($request->filter_end_date));
        }
        
        $total_sales = Branch::from('branches as b')
            ->select('b.*')
            ->selectRaw("SUM(ts.bank_in) as sum_ts_bank_in")
            ->selectRaw("SUM(ts.debit) as sum_ts_debit")
            ->selectRaw("SUM(ts.netto_debit) as sum_ts_netto_debit")
            ->selectRaw("SUM(ts.card) as sum_ts_card")
            ->selectRaw("SUM(ts.netto_card) as sum_ts_netto_card")
            ->leftJoin('orders as o', 'o.branch_id', 'b.id')
            ->leftJoin('order_payments as op', 'op.order_id', 'o.id')
            ->leftJoin('total_sales as ts', 'ts.order_payment_id', 'op.id')
            ->whereBetween('op.payment_date', [$startDate, $endDate])
            ->where('b.active', true)
            ->where('o.active', true)
            ->orderBy('b.code')
            ->groupBy('b.id')->get();
        $countTotalSales = $total_sales->count();

        // if ($request->has('export_type')) {
            // if ($request->export_type == "print") {
            //     return view('admin.list_totalsale_Pdf', compact('startDate', 'endDate', 'total_sales', 'countTotalSales'));
            // } else if ($request->export_type == "xls") {
            //     return Excel::download(new TotalSaleReportExport($startDate, $endDate, $total_sales, $countTotalSales), 'Sales Report.xlsx');
            // }    
        // } else {
            return view('admin.list_totalsale', compact('startDate', 'endDate', 'total_sales', 'countTotalSales'));
        // }
    }

    public function listTotalSaleBranch(Request $request)
    {
        $branches = Branch::where('active', true)->orderBy('code', 'asc')->get();

        $startDate = date('Y-m-01');
        if ($request->has('filter_start_date')) {
            $startDate = date('Y-m-d', strtotime($request->filter_start_date));
        }
        $endDate = date('Y-m-d');
        if ($request->has('filter_end_date')) {
            $endDate = date('Y-m-d', strtotime($request->filter_end_date));
        }

        $total_sales = Cso::from('csos as c')
            ->select('c.*')
            ->selectRaw("SUM(ts.bank_in) as sum_ts_bank_in")
            ->selectRaw("SUM(ts.debit) as sum_ts_debit")
            ->selectRaw("SUM(ts.netto_debit) as sum_ts_netto_debit")
            ->selectRaw("SUM(ts.card) as sum_ts_card")
            ->selectRaw("SUM(ts.netto_card) as sum_ts_netto_card")
            ->join('orders as o', 'o.cso_id', 'c.id')
            ->join('order_payments as op', 'op.order_id', 'o.id')
            ->join('total_sales as ts', 'ts.order_payment_id', 'op.id')
            ->whereBetween('op.payment_date', [$startDate, $endDate])
            ->where('c.active', true)
            ->where('o.active', true);

        $currentBranch = null;
        if ($request->has('filter_branch')) {
            $currentBranch = Branch::find($request->filter_branch);
            $total_sales->where('o.branch_id', $currentBranch['id']);
        } 
        
        $total_sales = $total_sales->orderBy('c.code')
            ->groupBy('c.id')->get();
        $countTotalSales = $total_sales->count();

        if ($request->has('export_type')) {
            if ($request->export_type == "print") {
                return view('admin.list_totalsale_branchPdf', compact('startDate', 'endDate', 'branches', 'currentBranch', 'total_sales', 'countTotalSales', 'currentBranch'));
            } else if ($request->export_type == "xls") {
                return Excel::download(new TotalSaleReportBranchExport($startDate, $endDate, $total_sales, $countTotalSales, $currentBranch), 'Order Report Branch.xlsx');
            }    
        } else {
            return view('admin.list_totalsale_branch', compact('startDate', 'endDate', 'branches', 'currentBranch', 'total_sales', 'countTotalSales'));
        }
    }

    public function listTotalSaleCso(Request $request)
    {
        $branches = Branch::where('active', true)->orderBy('code', 'asc')->get();
        $csos = Cso::where("active", true)->orderBy("code", 'asc')->get();

        $startDate = date('Y-m-01');
        if ($request->has('filter_start_date')) {
            $startDate = date('Y-m-d', strtotime($request->filter_start_date));
        }
        $endDate = date('Y-m-d');
        if ($request->has('filter_end_date')) {
            $endDate = date('Y-m-d', strtotime($request->filter_end_date));
        }
        
        $total_sales = Order::from('orders as o')
            ->select('o.*', 'op.payment_date as op_payment_date')
            ->selectRaw("SUM(ts.bank_in) as sum_ts_bank_in")
            ->selectRaw("SUM(ts.debit) as sum_ts_debit")
            ->selectRaw("SUM(ts.netto_debit) as sum_ts_netto_debit")
            ->selectRaw("SUM(ts.card) as sum_ts_card")
            ->selectRaw("SUM(ts.netto_card) as sum_ts_netto_card")
            ->join('order_payments as op', 'op.order_id', 'o.id')
            ->join('total_sales as ts', 'ts.order_payment_id', 'op.id')
            ->whereBetween('op.payment_date', [$startDate, $endDate])
            ->where('o.active', true);

        $currentBranch = null;
        if ($request->has('filter_branch')) {
            $currentBranch = Branch::find($request->filter_branch);
            $total_sales->where('o.branch_id', $currentBranch['id']);
        }
        $currentCso = null;
        if ($request->has('filter_cso')) {
            $currentCso = Cso::where("code", $request->filter_cso)->first();
            $total_sales->where('o.cso_id', $currentCso['id']);
        }

        $total_sales = $total_sales->orderBy('op.payment_date', 'desc')
            ->groupBy('o.id')->get();
        $countTotalSales = $total_sales->count();

        if ($request->has('export_type')) {
            if ($request->export_type == "print") {
                return view('admin.list_totalsale_CsoPdf', compact('startDate', 'endDate', 'branches', 'csos', 'currentBranch', 'currentCso', 'total_sales', 'countTotalSales'));
            } else if ($request->export_type == "xls") {
                return Excel::download(new TotalSaleReportCsoExport($startDate, $endDate, $total_sales, $countTotalSales, $currentBranch, $currentCso), 'Order Report Cso.xlsx');
            }
        } else {
            return view('admin.list_totalsale_cso', compact('startDate', 'endDate', 'branches', 'csos', 'currentBranch', 'currentCso', 'total_sales', 'countTotalSales'));
        }
    }

    public function exportTotalSaleByBank(Request $request)
    {
        $startDate = date('Y-m-01');
        if ($request->has('filter_start_date')) {
            $startDate = date('Y-m-d', strtotime($request->filter_start_date));
        }
        $endDate = date('Y-m-d');
        if ($request->has('filter_end_date')) {
            $endDate = date('Y-m-d', strtotime($request->filter_end_date));
        }

        $total_sales_temp = BankAccount::from('bank_accounts as b')
            ->select('b.*'
                , 'br.id as br_id'
                , 'br.code as br_code'
                , 'br.name as br_name'
                , 'o.id as o_id'
                , 'o.code as o_code'
                , 'o.created_at as o_created_at'
                , 'op.payment_date as op_payment_date'
                , 'op.estimate_transfer_date as op_estimate_transfer_date'
                , 'ts.bank_in as ts_bank_in'
                , 'ts.debit as ts_debit'
                , 'ts.netto_debit as ts_netto_debit'
                , 'ts.card as ts_card'
                , 'ts.netto_card as ts_netto_card')
            ->join('order_payments as op', 'op.bank_account_id', 'b.id')
            ->join('orders as o', 'o.id', 'op.order_id')
            ->join('branches as br', 'br.id', 'o.branch_id')
            ->join('total_sales as ts', 'ts.order_payment_id', 'op.id')
            ->whereBetween('op.payment_date', [$startDate, $endDate])
            ->where('o.active', true)
            ->where('o.status', '!=', 'reject')
            ->groupBy('b.id', 'br.id', 'op.id')
            ->orderBy('b.code', 'asc')
            ->orderBy('br.code', 'asc')
            ->orderBy('op.payment_date', 'asc')->get();
        
        $total_sales = [];

        foreach ($total_sales_temp as $ts_temp) {
            if (!isset($total_sales[$ts_temp['id']])) 
                $total_sales[$ts_temp['id']] = $ts_temp->toArray();
            if (!isset($total_sales[$ts_temp['id']]['branches'][$ts_temp['br_id']])) 
                $total_sales[$ts_temp['id']]['branches'][$ts_temp['br_id']] = $ts_temp->toArray();
            $total_sales[$ts_temp['id']]['branches'][$ts_temp['br_id']]['orders'][] = $ts_temp;
        }

        if($request->has("isPrint")){
            return view('admin.list_totalsale_print', compact('startDate', 'endDate', 'total_sales'));
        }

        return Excel::download(new TotalSaleByBankExport($startDate, $endDate, $total_sales), 'Report Bank In Sales (By Bank).xlsx');
    }

    public function exportTotalSaleByBranch(Request $request)
    {
        $startDate = date('Y-m-01');
        if ($request->has('filter_start_date')) {
            $startDate = date('Y-m-d', strtotime($request->filter_start_date));
        }
        $endDate = date('Y-m-d');
        if ($request->has('filter_end_date')) {
            $endDate = date('Y-m-d', strtotime($request->filter_end_date));
        }

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
                , 'ts.bank_in as ts_bank_in'
                , 'ts.debit as ts_debit'
                , 'ts.netto_debit as ts_netto_debit'
                , 'ts.card as ts_card'
                , 'ts.netto_card as ts_netto_card')
            ->join('orders as o', 'o.branch_id', 'br.id')
            ->join('order_payments as op', 'op.order_id', 'o.id')
            ->join('bank_accounts as b', 'b.id', 'op.bank_account_id')
            ->join('total_sales as ts', 'ts.order_payment_id', 'op.id')
            ->whereBetween('op.payment_date', [$startDate, $endDate])
            ->where('o.active', true)
            ->groupBy('br.id', 'b.id', 'op.id')
            ->orderBy('br.code', 'asc')
            ->orderBy('b.code', 'asc')
            ->orderBy('op.payment_date', 'asc')->get();
        
        $total_sales = [];

        foreach ($total_sales_temp as $ts_temp) {
            if (!isset($total_sales[$ts_temp['id']])) 
                $total_sales[$ts_temp['id']] = $ts_temp->toArray();
            if (!isset($total_sales[$ts_temp['id']]['banks'][$ts_temp['b_id']])) 
                $total_sales[$ts_temp['id']]['banks'][$ts_temp['b_id']] = $ts_temp->toArray();
            $total_sales[$ts_temp['id']]['banks'][$ts_temp['b_id']]['orders'][] = $ts_temp;
        }

        if($request->has("isPrint")){
            return view('admin.list_totalsale_print', compact('startDate', 'endDate', 'total_sales'));
        }

        return Excel::download(new TotalSaleByBranchExport($startDate, $endDate, $total_sales), 'Report Bank In Sales (By Branch).xlsx');
    }
}
