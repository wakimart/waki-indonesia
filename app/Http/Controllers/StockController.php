<?php

namespace App\Http\Controllers;

use App\Product;
use App\Stock;
use App\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stocks = Stock::with('product')
            ->where('active', true)
            ->whereNotNull('type_warehouse')
            ->get();
        $stocks = $stocks->groupBy('product_id');

        return view('admin.list_stock', compact('stocks'));
    }

    public function stock(Request $request)
    {
        $products = Product::select("id", "code", "name")
            ->where("active", true)
            ->orderBy("code")
            ->get();

        $stocks = Stock::select(
                "stocks.id AS id",
                DB::raw("SUM(stocks.quantity) AS quantity"),
                "products.code AS product_code",
                "products.name AS product_name",
            )
            ->leftJoin('products', 'products.id', '=', 'stocks.product_id')
            ->whereNull('stocks.type_warehouse')
            ->where('stocks.active', true);

        $warehouses = Warehouse::where("active", true)
            ->orderBy("code")
            ->get();

        $parentWarehouses = Warehouse::select("id", "code", "name", "city_id")
            ->whereNull('parent_warehouse_id')
            ->where("active", true)
            ->orderBy("code")
            ->get();

        if (
            !empty($request->get("filter_product"))
            && empty($request->get("filter_warehouse"))
            && empty($request->get("filter_city"))
        ) {
            $stocks = $stocks->addSelect(
                    "warehouses.code AS warehouse_code",
                    "warehouses.name AS warehouse_name",
                )
                ->leftJoin(
                    'warehouses',
                    'warehouses.id',
                    '=',
                    'stocks.warehouse_id'
                )
                ->where("stocks.product_id", $request->get("filter_product"));
        } elseif (
            empty($request->get("filter_product"))
            && !empty($request->get("filter_warehouse"))
            && !empty($request->get("filter_city"))
        ) {
            $warehouses = $warehouses->where(
                    "parent_warehouse_id",
                    $request->get("filter_warehouse")
                );

            $stocks = $stocks->where(function ($query) use ($request) {
                $query->where(
                        "warehouses.parent_warehouse_id",
                        $request->get("filter_warehouse")
                    );
            })
            ->leftJoin(
                'warehouses',
                'warehouses.id',
                '=',
                'stocks.warehouse_id'
            );
        } elseif (
            !empty($request->get("filter_product"))
            && !empty($request->get("filter_warehouse"))
            && !empty($request->get("filter_city"))
        ) {
            $warehouses = $warehouses->where(
                    "parent_warehouse_id",
                    $request->get("filter_warehouse")
                );

            $stocks = $stocks->where(function ($query) use ($request) {
                $query->where(
                        [["stocks.product_id", $request->get("filter_product")],
                        ["warehouses.parent_warehouse_id", $request->get("filter_warehouse")]]
                    );
            })
            ->leftJoin(
                'warehouses',
                'warehouses.id',
                '=',
                'stocks.warehouse_id'
            );
        }

        if (!empty($request->get("filter_month"))) {
            $stocks = $stocks->addSelect(
                DB::raw(
                    "IFNULL(stocks.quantity "
                    . "- (SELECT IFNULL(SUM(hs.quantity), 0) FROM history_stocks AS hs WHERE hs.stock_id = stocks.id AND hs.type = 'in' AND hs.date > '" . $request->get("filter_month") . "') "
                    . "+ (SELECT IFNULL(SUM(hs2.quantity), 0) FROM history_stocks AS hs2 WHERE hs2.stock_id = stocks.id AND hs2.type = 'out' AND hs2.date > '" . $request->get("filter_month") . "')"
                    . ", 0) AS month_quantity"
                ),
            );
        }

        $stocks = $stocks->paginate(10);

        return view('admin.list_stock_warehouse', compact(
                'stocks',
                "products",
                "warehouses",
                "parentWarehouses",
            ))
            ->with('i', (request()->input('page', 1) - 1) * 10);
    }

    public function fetchParentByCity($city){
        $parentWarehouse = Warehouse::whereNull('parent_warehouse_id')->where([['city_id', $city], ['active', true]])->get();
        return response()->json($parentWarehouse);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function show(Stock $stock)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function edit(Stock $stock)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Stock $stock)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function destroy(Stock $stock)
    {
        //
    }
}
