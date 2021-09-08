<?php

namespace App\Http\Controllers;

use App\HistoryStock;
use App\HistoryUpdate;
use App\Product;
use App\Stock;
use App\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class HistoryStockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $historystocks = HistoryStock::groupBy('code');

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

        $historystocks = $historystocks->paginate(10);

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
        $products = Product::select("id", "code", "name")
            ->where("active", true)
            ->orderBy("code")
            ->get();

        $warehouses = Warehouse::select("id", "code", "name")
            ->where("active", true)
            ->orderBy("code")
            ->get();

        return view("admin.add_history_stock", compact(
            "products",
            "warehouses",
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
        DB::beginTransaction();

        try {
            HistoryStock::create($request->only(
                "stock_id",
                "code",
                "date",
                "type",
                "quantity",
                "description",
            ));

            DB::commit();

            return redirect()
                ->route("add_history_stock")
                ->with("success", "History Stock successfully added.");
        } catch (Throwable $th) {
            DB::rollBack();

            return response()->json(["error" => $th->getMessage()], 500);
        }
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
     * @param  \Illuminate\Http\Request  $request
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
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        DB::beginTransaction();

        try {
            $historyStock = HistoryStock::where($request->id)->first();
            $historyStock->fill($request->only(
                "stock_id",
                "code",
                "date",
                "type",
                "quantity",
                "description",
            ));
            $historyStock->save();

            $userId = Auth::user()["id"];
            $history["type_menu"] = "History Stock";
            $history["method"] = "Update";
            $history["meta"] = json_encode(
                [
                    "user" => $userId,
                    "createdAt" => date("Y-m-d H:i:s"),
                    "dataChange" => $historyStock->getChanges(),
                ],
                JSON_THROW_ON_ERROR
            );
            $history["user_id"] = $userId;
            $history["menu_id"] = $request->id;
            HistoryUpdate::create($history);

            DB::commit();

            return redirect()
                ->route("list_history_stock")
                ->with("success", "History Stock successfully updated.");
        } catch (Throwable $th) {
            DB::rollBack();

            return response()->json(["error" => $th->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        DB::beginTransaction();

        try {
            $historyStock = HistoryStock::where($request->id)->first();
            $historyStock->active = false;
            $historyStock->save();

            $userId = Auth::user()["id"];
            $history["type_menu"] = "History Stock";
            $history["method"] = "Delete";
            $history["meta"] = json_encode(
                [
                    "user" => $userId,
                    "createdAt" => date("Y-m-d H:i:s"),
                    "dataChange" => $historyStock->getChanges(),
                ],
                JSON_THROW_ON_ERROR
            );
            $history["user_id"] = $userId;
            $history["menu_id"] = $request->id;
            HistoryUpdate::create($history);

            DB::commit();

            return redirect()
                ->route("list_history_stock")
                ->with("success", "History Stock successfully deleted.");
        } catch (Throwable $th) {
            DB::rollBack();

            return response()->json(["error" => $th->getMessage()], 500);
        }
    }
}
