<?php

namespace App\Http\Controllers;

use App\Branch;
use App\Warehouse;
use App\Order;
use App\OrderDetail;
use App\StockInOutProduct;
use App\TotalSale;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function ReportProvitAndLoss_index(Request $request)
    {
    	$warehouses = Warehouse::select("id", "code", "name")
		            ->whereIn("id", [29,4])
		            ->get();

    	$stockOutOrder = OrderDetail::select('p.id as product_id', 'p.code as code', 'p.name as name', 'p.in_price')
    					->selectRAW('SUM(od.qty) as quantity')
    					->from('order_details as od')
    					->leftJoin('products as p', 'p.id', 'od.product_id')
    					->leftJoin('stock_in_outs as s', 's.id', 'od.stock_id')
    					->where('od.stock_id', '!=', null)
    					->groupBy('od.product_id')
    					->orderBy('p.code');

    	$yearChoice = $request->has("filter_year") ? $request->filter_year : date('Y');
    	$stockOutOrder->whereYear('s.date', $yearChoice);

        if ($request->has("filter_warehouse")){
            $stockOutOrder->where('s.warehouse_from_id', $request->filter_warehouse);
        }
        $stockOutOrder = $stockOutOrder->get();
        
        $total_sales = Branch::from('branches as b')
            ->select('b.*')
            ->selectRaw("SUM(ts.bank_in) as sum_ts_bank_in")
            ->selectRaw("SUM(ts.netto_debit) as sum_ts_netto_debit")
            ->selectRaw("SUM(ts.netto_card) as sum_ts_netto_card")
            ->leftJoin('orders as o', 'o.branch_id', 'b.id')
            ->leftJoin('order_payments as op', 'op.order_id', 'o.id')
            ->leftJoin('total_sales as ts', 'ts.order_payment_id', 'op.id')
            ->whereYear('op.payment_date', $yearChoice)
            ->where('b.active', true)
            ->where('o.active', true)
            ->orderBy('b.code')
            ->groupBy('b.id')->get();
        $total_saleNya = $total_sales->sum('sum_ts_bank_in') + $total_sales->sum('sum_ts_netto_debit') + $total_sales->sum('sum_ts_netto_card');

        return view("admin.report_provit_and_loss", compact("stockOutOrder", "warehouses", "total_saleNya"));
    }

    public function ReportProvitAndLoss_detail($product_id, Request $request)
    {
        $warehouses = Warehouse::select("id", "code", "name")
                    ->whereIn("id", [29,4])
                    ->get();

        $stockOutOrder = OrderDetail::select('od.*')
                        ->from('order_details as od')
                        ->leftJoin('stock_in_outs as s', 's.id', 'od.stock_id')
                        ->where('od.stock_id', '!=', null)
                        ->where('od.product_id', $product_id);

        $yearChoice = $request->has("filter_year") ? $request->filter_year : date('Y');
        $stockOutOrder->whereYear('s.date', $yearChoice);

        if ($request->has("filter_warehouse")){
            $stockOutOrder->where('s.warehouse_from_id', $request->filter_warehouse);
        }
        $stockOutOrder = $stockOutOrder
                        ->orderBy('s.date')
                        ->paginate(10);

        return view("admin.report_out_provit_and_loss_detail", compact("stockOutOrder", "warehouses", "product_id"));
    }
}
