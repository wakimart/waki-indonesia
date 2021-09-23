<?php

namespace App\Http\Controllers;

use App\Product;
use App\Stock;
use App\Warehouse;
use Illuminate\Http\Request;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stocks = Stock::with('product')->where('active', true)->whereNotNull('type_warehouse')->get();
        $stocks = $stocks->groupBy('product_id');
        // dd($stocks->toArray());
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
                "stocks.quantity AS quantity",
                "products.code AS product_code",
                "products.name AS product_name",
            )
            ->leftJoin('products', 'products.id', '=', 'stocks.product_id')
            ->whereNull('stocks.type_warehouse')
            ->where('stocks.active', true);

        $warehouses = Warehouse::select("id", "code", "name")
            ->where("active", true)
            ->orderBy("code")
            ->get();

        if (!empty($request->get("filter_name"))) {
            $stocks = $stocks->where(
                "products.name",
                "like",
                "%" . $request->get("filter_name") . "%"
            );
        }

        if (!empty($request->get("filter_code"))) {
            $stocks = $stocks->where(
                "products.code",
                "like",
                "%" . $request->get("filter_code") . "%"
            );
        }

        if (
            !empty($request->get("filter_product"))
            && empty($request->get("filter_warehouse"))
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
        ) {
            $stocks = $stocks->where(function ($query) use ($request) {
                $query->where(
                        "stocks.warehouse_id",
                        $request->get("filter_warehouse")
                    )
                    ->orWhere(
                        "warehouses.parent_warehouse_id",
                        $request->get("filter_warehouse")
                    );
            });
        } elseif (
            !empty($request->get("filter_product"))
            && !empty($request->get("filter_warehouse"))
        ) {
            $stocks = $stocks->where(
                    "stocks.product_id",
                    $request->get("filter_product")
                )
                ->where(
                    "stocks.warehouse_id",
                    $request->get("filter_warehouse")
                );
        }

        $stocks = $stocks->groupBy("product_id")->paginate(10);

        return view('admin.list_stock_warehouse', compact(
                'stocks',
                "products",
                "warehouses",
            ))
            ->with('i', (request()->input('page', 1) - 1) * 10);
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
