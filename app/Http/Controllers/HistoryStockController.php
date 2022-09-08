<?php

namespace App\Http\Controllers;

use App\HistoryStock;
use App\HistoryUpdate;
use App\Product;
use App\Stock;
use App\Warehouse;
use App\Exports\HistoryStockExport;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
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
        $historystocks = HistoryStock::orderBy("id", "desc")->whereNotNull('code')
            ->where('active', true);

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

        $stocks = Product::select("id", "code", "name")
            ->where("active", true)
            ->orderBy("code")
            ->get();

        return view("admin.list_history_stock", compact("historystocks", "warehouses", "stocks"))
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
        $validator = \Validator::make($request->all(), [
            'type' => 'required',
            'code' => 'required',
            'date' => 'required',
            'from_warehouse_id' => 'required|exists:warehouses,id',
            'to_warehouse_id' => 'required|different:from_warehouse_id|exists:warehouses,id',
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
            $products = $request->product;
            $countProduct = count($products);

            for ($i = 0; $i < $countProduct; $i++) {
                $stock_from = Stock::where("warehouse_id", $request->from_warehouse_id)
                    ->where("product_id", $products[$i])
                    ->where("type_warehouse", null)
                    ->first();
                $stock_to = Stock::where("warehouse_id", $request->to_warehouse_id)
                    ->where("product_id", $products[$i])
                    ->where("type_warehouse", null)
                    ->first();

                if (empty($stock_from)) {
                    $stock_from = new Stock();
                    $stock_from->warehouse_id = $request->from_warehouse_id;
                    $stock_from->product_id = $products[$i];
                    $stock_from->quantity = 0;
                    $stock_from->save();
                }
                if (empty($stock_to)) {
                    $stock_to = new Stock();
                    $stock_to->warehouse_id = $request->to_warehouse_id;
                    $stock_to->product_id = $products[$i];
                    $stock_to->quantity = 0;
                    $stock_to->save();
                }

                // Validation Same Stock
                $checkUniqueCodeType = HistoryStock::where('type', $request->type)
                    ->where('code', $request->code)
                    ->where('date', $request->date)
                    ->where('stock_from_id', $stock_from->id)
                    ->where('stock_to_id', $stock_to->id)
                    ->where('active', true)->first();
                if ($checkUniqueCodeType) {
                    $arr_Hasil = [];
                    $arr_Hasil['code'] = 'Stock ' . $request->type . ' code with product ' . ($i + 1) . ' is already exists';
                    return response()->json(['errors' => $arr_Hasil]);
                }

                if ($request->type === "in") {
                    $stock_to->quantity += $request->quantity[$i];
                    $stock_to->save();
                } elseif ($request->type === "out") {
                    if (($stock_from->quantity - $request->quantity[$i]) >= 0) {
                        $stock_from->quantity -= $request->quantity[$i];
                        $stock_from->save();
                    } else {
                        // Validation Out Of Stock
                        $arr_Hasil = [];
                        $arr_Hasil['quantity[]'] = 'Stock ' . ($i + 1) . ' is not enough';
                        return response()->json(['errors' => $arr_Hasil]);
                    }
                }

                $historyStock = new HistoryStock();
                $historyStock->fill($request->only(
                    "code",
                    "date",
                    "type",
                    "description",
                ));
                $historyStock->stock_from_id = $stock_from->id;
                $historyStock->stock_to_id = $stock_to->id;
                $historyStock->quantity = $request->quantity[$i];
                $historyStock->koli = $request->koli[$i];
                $historyStock->user_id = Auth::user()->id;
                $historyStock->save();
            }

            DB::commit();

            // if ($request->type === "in") {
                // return redirect()
                //     ->route("add_history_in")
                //     ->with("success", "History Stock successfully added.");
            // } elseif ($request->type === "out") {
                // return redirect()
                //     ->route("add_history_out")
                //     ->with("success", "History Stock successfully added.");
            // }
            return response()->json(['success' => "History Stock successfully added."]);
        } catch (Throwable $th) {
            DB::rollBack();

            return response()->json(["errors" => $th->getMessage()], 500);
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

    public function fetchHistoryStockByCode(Request $request)
    {
        if ($request->code && $request->type) {
            $type = $request->type == 'in' ? 'out' : 'in';
            $historyStock = HistoryStock::where('code', $request->code)
                ->where('type', $type)
                ->where('active', true)
                ->first();
            if ($historyStock) {
                $historyStock->from_warehouse_id = $historyStock->stockFrom->warehouse_id;
                $historyStock->to_warehouse_id = $historyStock->stockTo->warehouse_id ?? '';
                return response()->json(["data" => $historyStock]);
            }
            return response()->json(["success" => 'No Data Found']);
        }
        return response()->json(["error" => 'Error Parameter'], 500);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\HistoryStock  $historyStock
     * @return \Illuminate\Http\Response
     */

    public function show(Request $request)
    {
        $historystock = HistoryStock::where("code", $request->code)
            ->where('active', true)->get();

        return view('admin.detail_history_stock', compact(
            'historystock',
        ));
    }

    public function export_to_xls(Request $request)
    {
        $inputWarehouse = null;
        $inputStock = null;
        $startDate = null;
        $endDate = null;

        if ($request->has('filter_startDate') || $request->has('filter_endDate')) {
            $startDate = isset($request->filter_startDate) ? date($request->filter_startDate) : '';
            $endDate = isset($request->filter_endDate) ? date($request->filter_endDate) : date('Y-m-d');
        }

        if ($request->has('filter_inputByWarehouse') && is_numeric($request->filter_inputByWarehouse)) {
            $inputWarehouse = $request->filter_inputByWarehouse;
        }

        if ($request->has('filter_inputByStock') && is_numeric($request->filter_inputByStock)) {
            $inputStock = $request->filter_inputByStock;
        }

        return Excel::download(
            new HistoryStockExport($inputWarehouse, $inputStock, array($startDate, $endDate)),
            'History Stock.xlsx'
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
        $historyStocks = HistoryStock::where("code", $request->code)
            ->where('active', true)->get();

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
        $historyStocks = HistoryStock::where("code", $request->code)
            ->where('active', true)->get();

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
        $validator = \Validator::make($request->all(), [
            'type' => 'required',
            'code' => 'required',
            'date' => 'required',
            'from_warehouse_id' => 'required|exists:warehouses,id',
            'to_warehouse_id' => 'required|different:from_warehouse_id|exists:warehouses,id',
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
            $historyStocks = HistoryStock::where("code", $request->old_code)
                ->where("active", true)
                ->get();

            $userId = Auth::user()["id"];

            // Deactivate History Stock based on code
            foreach ($historyStocks as $historyStock) {
                // Change quantity in stock
                if ($historyStock->type === "in") {
                    $stock = Stock::where("id", $historyStock->stock_to_id)->first();
                    $stock->quantity -= $historyStock->quantity;
                } elseif ($historyStock->type === "out") {
                    $stock = Stock::where("id", $historyStock->stock_from_id)->first();
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
                $stock_from = Stock::where("warehouse_id", $request->from_warehouse_id)
                    ->where("product_id", $products[$i])
                    ->where("type_warehouse", null)
                    ->first();
                $stock_to = Stock::where("warehouse_id", $request->to_warehouse_id)
                    ->where("product_id", $products[$i])
                    ->where("type_warehouse", null)
                    ->first();

                if (empty($stock_from)) {
                    $stock_from = new Stock();
                    $stock_from->warehouse_id = $request->from_warehouse_id;
                    $stock_from->product_id = $products[$i];
                    $stock_from->quantity = 0;
                    $stock_from->save();
                }
                if (empty($stock_to)) {
                    $stock_to = new Stock();
                    $stock_to->warehouse_id = $request->to_warehouse_id;
                    $stock_to->product_id = $products[$i];
                    $stock_to->quantity = 0;
                    $stock_to->save();
                }

                // Validation Same Stock
                $checkUniqueCodeType = HistoryStock::where('type', $request->type)
                    ->where('code', $request->code)
                    ->where('code', '!=', $request->old_code)
                    ->where('date', $request->date)
                    ->where('stock_from_id', $stock_from->id)
                    ->where('stock_to_id', $stock_to->id)
                    ->where('active', true)->first();
                if ($checkUniqueCodeType) {
                    $arr_Hasil = [];
                    $arr_Hasil['code'] = 'Stock ' . $request->type . ' code with product ' . ($i + 1) . ' is already exists';
                    return response()->json(['errors' => $arr_Hasil]);
                }

                if ($request->type === "in") {
                    $stock_to->quantity += $request->quantity[$i];
                    $stock_to->save();
                } elseif ($request->type === "out") {
                    if (($stock_from->quantity - $request->quantity[$i]) >= 0) {
                        $stock_from->quantity -= $request->quantity[$i];
                        $stock_from->save();
                    } else {
                        // Validation Out Of Stock
                        $arr_Hasil = [];
                        $arr_Hasil['quantity[]'] = 'Stock ' . ($i + 1) . ' is not enough';
                        return response()->json(['errors' => $arr_Hasil]);
                    }
                }

                $historyStock = new HistoryStock();
                $historyStock->fill($request->only(
                    "code",
                    "date",
                    "type",
                    "description",
                ));
                $historyStock->stock_from_id = $stock_from->id;
                $historyStock->stock_to_id = $stock_to->id;
                $historyStock->quantity = $request->quantity[$i];
                $historyStock->koli = $request->koli[$i];
                $historyStock->user_id = Auth::user()->id;
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

            // return redirect()
            //     ->route("list_history_stock")
            //     ->with("success", "History Stock successfully updated.");
            return response()->json(['success' => "History Stock successfully added."]);
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
                if ($historyStock->type === "in") {
                    $stock = Stock::where("id", $historyStock->stock_to_id)->first();
                    $stock->quantity -= $historyStock->quantity;
                } elseif ($historyStock->type === "out") {
                    $stock = Stock::where("id", $historyStock->stock_from_id)->first();
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
