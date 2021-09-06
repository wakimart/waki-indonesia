<?php

namespace App\Http\Controllers;

use App\HistoryStock;
use App\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class HistoryStockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $historystocks = HistoryStock::orderBy('date')->paginate(10);

        if ($request->has("filter_code")) {
            $filterCode = $request->filter_code;
            $historystocks = $historystocks->where(function ($q) use ($filterCode) {
                $q->where('code', "like", "%" . $filterCode . "%");
            });
        }
        if ($request->has("filter_type")) {
            $historystocks = $historystocks->where('type', $request->historystocks);
        }
        if ($request->has("filter_date")) {
            $historystocks = $historystocks->where('date', $request->historystocks);
        }

        return view("admin.list_history_stock", compact("historystocks"))
            ->with('i', (request()->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $stocks = Stock::with('product')->where('active', true)->get();

        return view("admin.add_history_stock", compact(
            "stocks",
        ));
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
     * @param  \App\HistoryStock  $historyStock
     * @return \Illuminate\Http\Response
     */
    public function show(HistoryStock $historyStock)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\HistoryStock  $historyStock
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $historystock = HistoryStock::where($request->id)->first();

        $stocks = Stock::with('product')->where('active', true)->get();

        return view("admin.update_history_stock", compact("historystock", "stocks"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\HistoryStock  $historyStock
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, HistoryStock $historyStock)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\HistoryStock  $historyStock
     * @return \Illuminate\Http\Response
     */
    public function destroy(HistoryStock $historyStock)
    {
        //
    }
}
