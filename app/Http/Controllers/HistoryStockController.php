<?php

namespace App\Http\Controllers;

use App\HistoryStock;
use App\HistoryUpdate;
use App\Product;
use App\Stock;
use App\Warehouse;
use App\Exports\HistoryStockExportByWarehouse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
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
        $historystocks = HistoryStock::orderBy("date", "desc")->whereNotNull('code');

        if ($request->has("filter_code")) {
            $filterCode = $request->filter_code;
            $historystocks = $historystocks->where(function ($q) use ($filterCode) {
                $q->where('code', "like", "%" . $filterCode . "%");
            });
        }

        if ($request->has("filter_type")) {
            $historystocks = $historystocks->where('type', $request->filter_type);
        }

        if ($request->has("filter_date")) {
            $historystocks = $historystocks->where('date', $request->filter_date);
        }

        $historystocks = $historystocks->paginate(10);

        $warehouses = Warehouse::select("id", "code", "name")
            ->where("active", true)
            ->orderBy("code")
            ->get();

        return view("admin.list_history_stock", compact("historystocks", "warehouses"))
            ->with('i', (request()->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createIn()
    {
        $products = Product::select("id", "code", "name")
            ->where("active", true)
            ->orderBy("code")
            ->get();

        $warehouses = Warehouse::select("id", "code", "name")
            ->whereNotNull('parent_warehouse_id')
            ->where("active", true)
            ->orderBy("code")
            ->get();

        return view("admin.add_history_in", compact(
            "products",
            "warehouses",
        ));
    }

    public function createOut()
    {
        $products = Product::select("id", "code", "name")
            ->where("active", true)
            ->orderBy("code")
            ->get();

        $warehouses = Warehouse::select("id", "code", "name")
            ->whereNotNull('parent_warehouse_id')
            ->where("active", true)
            ->orderBy("code")
            ->get();

        return view("admin.add_history_out", compact(
            "products",
            "warehouses",
        ));
    }

    public function getStock(Request $request)
    {
        try {
            $stock = Stock::select(
                    "id",
                    "product_id",
                    "warehouse_id",
                    "quantity",
                )
                ->where("product_id", $request->product_id)
                ->where("warehouse_id", $request->warehouse_id)
                ->first();

            return response()->json($stock);
        } catch (Throwable $th) {
            return response()->json(["error" => $th->getMessage()], 500);
        }
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
            $products = $request->product;
            $countProduct = count($products);

            for ($i = 0; $i < $countProduct; $i++) {
                $stock = Stock::where("warehouse_id", $request->warehouse_id)
                    ->where("product_id", $products[$i])
                    ->where("type_warehouse", null)
                    ->first();

                if (empty($stock)) {
                    $stock = new Stock();
                    $stock->warehouse_id = $request->warehouse_id;
                    $stock->product_id = $products[$i];
                    $stock->quantity = 0;
                    $stock->save();
                }

                if ($request->type === "in") {
                    $stock->quantity += $request->quantity[$i];
                } elseif ($request->type === "out") {
                    if (($stock->quantity - $request->quantity[$i]) >= 0) {
                        $stock->quantity -= $request->quantity[$i];
                    } else {
                        throw new Exception("Stock is not enough");
                    }
                }
                $stock->save();

                $historyStock = new HistoryStock();
                $historyStock->fill($request->only(
                    "code",
                    "date",
                    "type",
                    "description",
                ));
                $historyStock->stock_id = $stock->id;
                $historyStock->quantity = $request->quantity[$i];
                $historyStock->save();
            }

            DB::commit();

            if ($request->type === "in") {
                return redirect()
                    ->route("add_history_in")
                    ->with("success", "History Stock successfully added.");
            } elseif ($request->type === "out") {
                return redirect()
                    ->route("add_history_out")
                    ->with("success", "History Stock successfully added.");
            }
        } catch (Throwable $th) {
            DB::rollBack();

            return response()->json(["error" => $th->getMessage()], 500);
        }
    }

    public function getProduct()
    {
        try {
            $products = Product::select("id", "code", "name")
                ->where("active", true)
                ->get();

            return response()->json(["data" => $products]);
        } catch (Throwable $th) {
            return response()->json(["error" => $th->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\HistoryStock  $historyStock
     * @return \Illuminate\Http\Response
     */

    public function show(Request $request)
    {
        $historystock = HistoryStock::where("code", $request->code)->get();

        return view('admin.detail_history_stock', compact(
            'historystock',
        ));
    }

    public function export_to_xls_byWarehouse(Request $request)
    {
        $inputWarehouse = null;
        $startDate = null;
        $endDate = null;

        if($request->has('filter_startDate')&&$request->has('filter_endDate')){
            $startDate = $request->filter_startDate;
            $endDate = $request->filter_endDate;
            $endDate = new \DateTime($endDate);
            $endDate = $endDate->modify('+1 day')->format('Y-m-d');
        }

        if($request->has('inputWarehouse')){
            $inputWarehouse = $request->inputWarehouse;
        }

        return Excel::download(
            new HistoryStockExportByWarehouse($inputWarehouse, array($startDate, $endDate)),
            'History Stock By Warehouse.xlsx'
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function editIn(Request $request)
    {
        $historyStocks = HistoryStock::where("code", $request->code)->get();

        $products = Product::select("id", "code", "name")
            ->where("active", true)
            ->orderBy("code")
            ->get();

        $warehouses = Warehouse::select("id", "code", "name")
            ->where("active", true)
            ->orderBy("code")
            ->get();

        return view("admin.update_history_in", compact(
            "historyStocks",
            "products",
            "warehouses",
        ));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function editOut(Request $request)
    {
        $historyStocks = HistoryStock::where("code", $request->code)->get();

        $products = Product::select("id", "code", "name")
            ->where("active", true)
            ->orderBy("code")
            ->get();

        $warehouses = Warehouse::select("id", "code", "name")
            ->where("active", true)
            ->orderBy("code")
            ->get();

        return view("admin.update_history_out", compact(
            "historyStocks",
            "products",
            "warehouses",
        ));
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
            $historyStocks = HistoryStock::where("code", $request->old_code)
                ->where("active", true)
                ->get();

            $userId = Auth::user()["id"];

            // Deactivate History Stock based on code
            foreach ($historyStocks as $historyStock) {
                // Change quantity in stock
                $stock = Stock::where("id", $historyStock->stock_id)->first();
                if ($historyStock->type === "in") {
                    $stock->quantity -= $historyStock->quantity;
                } elseif ($historyStock->type === "out") {
                    $stock->quantity += $historyStock->quantity;
                }
                $stock->save();

                $historyStock->active = false;
                $historyStock->save();

                // History (History Stock)
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
                $history["menu_id"] = $historyStock->id;
                HistoryUpdate::create($history);
            }

            // Re-entry History Stock
            $products = $request->product;
            $countProduct = count($products);

            for ($i = 0; $i < $countProduct; $i++) {
                $stock = Stock::where("warehouse_id", $request->warehouse_id)
                    ->where("product_id", $products[$i])
                    ->where("type_warehouse", null)
                    ->first();

                if (empty($stock)) {
                    $stock = new Stock();
                    $stock->warehouse_id = $request->warehouse_id;
                    $stock->product_id = $products[$i];
                    $stock->quantity = 0;
                    $stock->save();
                }

                if ($request->type === "in") {
                    $stock->quantity += $request->quantity[$i];
                } elseif ($request->type === "out") {
                    $stock->quantity -= $request->quantity[$i];
                }
                $stock->save();

                $historyStock = new HistoryStock();
                $historyStock->fill($request->only(
                    "code",
                    "date",
                    "type",
                    "description",
                ));
                $historyStock->stock_id = $stock->id;
                $historyStock->quantity = $request->quantity[$i];
                $historyStock->save();

                // History (Stock)
                $history["type_menu"] = "Stock";
                $history["method"] = "Update";
                $history["meta"] = json_encode(
                    [
                        "user" => $userId,
                        "createdAt" => date("Y-m-d H:i:s"),
                        "dataChange" => $stock->getChanges(),
                    ],
                    JSON_THROW_ON_ERROR
                );
                $history["user_id"] = $userId;
                $history["menu_id"] = $stock->id;
                HistoryUpdate::create($history);
            }

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
            $historyStocks = HistoryStock::where("code", $request->old_code)
                ->where("active", true)
                ->get();

            $userId = Auth::user()["id"];

            // Deactivate History Stock based on code
            foreach ($historyStocks as $historyStock) {
                // Change quantity in stock
                $stock = Stock::where("id", $historyStock->stock_id)->first();
                if ($historyStock->type === "in") {
                    $stock->quantity -= $historyStock->quantity;
                } elseif ($historyStock->type === "out") {
                    $stock->quantity += $historyStock->quantity;
                }
                $stock->save();

                // History (Stock)
                $history["type_menu"] = "Stock (History Stock)";
                $history["method"] = "Delete";
                $history["meta"] = json_encode(
                    [
                        "user" => $userId,
                        "createdAt" => date("Y-m-d H:i:s"),
                        "dataChange" => $stock->getChanges(),
                    ],
                    JSON_THROW_ON_ERROR
                );
                $history["user_id"] = $userId;
                $history["menu_id"] = $stock->id;
                HistoryUpdate::create($history);

                // Save History Stock
                $historyStock->active = false;
                $historyStock->save();

                // History (History Stock)
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
                $history["menu_id"] = $historyStock->id;
                HistoryUpdate::create($history);
            }

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
