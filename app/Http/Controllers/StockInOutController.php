<?php

namespace App\Http\Controllers;

use App\HistoryUpdate;
use App\Http\Controllers\Api\OfflineSideController;
use App\Order;
use App\OrderDetail;
use App\Product;
use App\Stock;
use App\StockInOut;
use App\StockInOutProduct;
use App\StockOrderRequest;
use App\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use PDF;

class StockInOutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $warehouses = Warehouse::select("id", "code", "name")
            ->where("active", true)
            ->orderBy("code")
            ->get();

        $stocks = Product::select("id", "code", "name")
            ->where("active", true)
            ->orderBy("code")
            ->get();

        $types = ["in", "out"];
        $stockTypes = [];
        foreach ($types as $type) {
            $stockTypes[$type] = StockInOut::orderBy("created_at", "desc")->orderBy('id', 'asc')
                ->where('active', true)
                ->where('type', $type);

            if ($request->has("filter_code")) {
                $filterCode = $request->filter_code;
                $stockTypes[$type]->where(function ($q) use ($filterCode) {
                    $q->where('code', "like", "%" . $filterCode . "%");
                });
            }

            if ($request->has("filter_product_code")) {
                $filterProduct = Product::where('code', $request->filter_product_code)->first();
                $stockTypes[$type]->whereHas('stockInOutProduct', function ($q) use ($filterProduct) {
                    $q->where('product_id', $filterProduct->id ?? '');
                });
            }

            if ($request->has("filter_date")) {
                $stockTypes[$type]->where('date', $request->filter_date);
            }

            $stockTypes[$type] = $stockTypes[$type]->paginate(10, ['*'], $type);
        }
        return view("admin.list_stock_new", compact("stockTypes", "warehouses", "stocks"));
    }

    public function indexInOut(Request $request)
    {
        $selectedDate = date('Y-m-d');
        if ($request->has('filter_date') 
            && $request->input('filter_date')
            && (date('Y-m-d', strtotime($request->input('filter_date'))) >= date('Y-m-d', strtotime('-2 months')))) {
            $selectedDate = date('Y-m-d', strtotime($request->input('filter_date')));
        }

        $listDates = [
            "todayDate" => date('Y-m-d'),
            "selectedDate" => $selectedDate,
        ];
        $typeInOuts = ["from" => "out", "to" => "in"]; 
        $subQuery = [];
        foreach ($listDates as $keyListDate => $listDate) {
            foreach ($typeInOuts as $keyTypeInOut => $typeInOut) {
                $subQueryString = "SELECT IFNULL(SUM(siop.quantity), 0)
                    FROM stock_in_outs as sio
                    JOIN stock_in_out_products as siop ON sio.id = siop.stock_in_out_id
                    WHERE siop.product_id = p.id
                    AND sio.active = 1
                    AND siop.active = 1
                    AND sio.warehouse_".$keyTypeInOut."_id = s.warehouse_id
                    AND sio.type = '".$typeInOut."'";
                if ($keyListDate == "todayDate") {
                    $subQueryString .= 
                        " AND sio.date > '".$listDates['selectedDate']."'
                        AND sio.date <= '".$listDate."'";
                } else {
                    $subQueryString .= 
                        " AND sio.date >= '".$listDate."' 
                        AND sio.date <= '".$listDate."'";
                }
                $subQuery[$keyListDate][$typeInOut] = $subQueryString;
            }
        };
        
        $stockWarehouses = [];
        $selectedWarehouse = null;
        $warehouses = [];
        if ($request->filter_parent_warehouse) {
            $selectedWarehouse = Warehouse::find($request->filter_parent_warehouse);
            $warehouses = Warehouse::where('parent_warehouse_id', $request->filter_parent_warehouse)->get()->keyBy('code');

            foreach ($warehouses as $warehouse) {
                $stockWarehouses[$warehouse->code] = Product::select('p.*')
                    ->selectRaw("SUM(s.quantity) as sum_current_quantity")
                    ->selectRaw("SUM((".$subQuery['todayDate']['in'].")) as today_in")
                    ->selectRaw("SUM((".$subQuery['todayDate']['out'].")) as today_out")
                    ->selectRaw("SUM((".$subQuery['selectedDate']['in'].")) as selectedDate_in")
                    ->selectRaw("SUM((".$subQuery['selectedDate']['out'].")) as selectedDate_out")
                    ->from('products as p')
                    // ->leftJoin('stocks as s', 'p.id', 's.product_id')
                    ->leftJoin('stocks as s', function($join) use ($warehouse){
                        $join->on('s.product_id', 'p.id');
                        $join->where('s.warehouse_id', $warehouse->id);
                    })
                    ->where('p.active', true)
                    // ->whereIn('s.warehouse_id', $warehouse->pluck('id')->toArray())
                    ->orderBy('p.code')
                    ->groupBy('p.id')
                    ->get();
            }
        }

        $parent_warehouses = Warehouse::select("id", "code", "name")
            ->where("active", true)
            ->where('parent_warehouse_id', null)
            ->orderBy("code")
            ->get();

        if ($request->print == true) {
            return view("admin.print_stockinout", compact("stockWarehouses", "warehouses", "selectedDate", "selectedWarehouse", "parent_warehouses"))
                ->with('i', (request()->input('page', 1) - 1) * 10);
        } else {
            return view("admin.list_stockinout", compact("stockWarehouses", "warehouses", "selectedDate", "selectedWarehouse", "parent_warehouses"))
                ->with('i', (request()->input('page', 1) - 1) * 10);
        }
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

        return view("admin.add_stock_in", compact(
            "products",
        ));
    }

    public function createOut(Request $request)
    {
        $products = Product::select("id", "code", "name")
            ->where("active", true)
            ->orderBy("code")
            ->get();
        $stockOrderRequest = null;
        if ($request->sor) {
            $stockOrderRequest = StockOrderRequest::where('id', $request->sor)
                ->where('status', StockOrderRequest::$status['1'])->first();
        }

        return view("admin.add_stock_out", compact(
            "products",
            "stockOrderRequest",
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

    public function generateCode(Request $request)
    {
        if ($request->type && $request->date && $request->warehouse_type) {
            $date_d = date('d', strtotime($request->date));
            $date_m = date('m', strtotime($request->date));
            $countStockInOut = StockInOut::whereMonth('date', $date_m)
                ->whereDay('date', $date_d)
                ->where('type', $request->type)->count() + 1;
            $code = "D".ucfirst(substr($request->type, 0, 1));
            $code .= ($request->warehouse_type != "warehouse") ? ucfirst(substr($request->warehouse_type, 0, 1)) : "U";
            $code .= '-'.$date_d.$date_m;
            $code .= '-'.str_pad($countStockInOut, "6", "0", STR_PAD_LEFT);
            return response()->json(["data" => $code], 200);            
        }
        return response()->json(["error" => 'Error Parameter'], 500);
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
            'date' => 'required',
            'from_warehouse_id' => 'required|exists:warehouses,id',
            'to_warehouse_id' => 'required|different:from_warehouse_id|exists:warehouses,id',
            'code' => [
                'required',
                Rule::unique('stock_in_outs')
                    ->where('code', $request->code)
                    ->where('active', true)
            ],
        ], ['code.unique' => 'The Code already used Please Generate Other Code.']);
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
            $stockInOut = new StockInOut();
            $stockInOut->warehouse_from_id = $request->from_warehouse_id;
            $stockInOut->warehouse_to_id = $request->to_warehouse_id;
            $stockInOut->code = $request->code;
            $stockInOut->temp_no = $request->temp_no;
            $stockInOut->date = $request->date;
            $stockInOut->type = $request->type;
            $stockInOut->description = $request->description;
            $stockInOut->user_id = Auth::user()->id;
            $stockInOut->stock_order_request_id = $request->stock_order_request_id;
            $stockInOut->save();
            
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

                $stockInOutProduct = new StockInOutProduct();
                $stockInOutProduct->stock_in_out_id = $stockInOut->id;
                $stockInOutProduct->stock_from_id = $stock_from->id;
                $stockInOutProduct->stock_to_id = $stock_to->id;
                $stockInOutProduct->product_id = $products[$i];
                $stockInOutProduct->quantity = $request->quantity[$i];
                $stockInOutProduct->koli = $request->koli[$i] ?? 0;
                $stockInOutProduct->save();
            }

            if ($stockInOut->stock_order_request_id) {
                $stockOrderRequest = $stockInOut->stockOrderRequest;
                $stockOrderRequest->status = StockOrderRequest::$status['2']; //Approved
                $stockOrderRequest->save();

                // Update Order Status
                $order = $stockOrderRequest->order;
                $order->status = 'stock_request_success';
                $order->save();

                // (new OfflineSideController)->sendUpdateOrderStatus($order->code, 'stock_request_success', Auth::user()->code);
            }

            DB::commit();

            return response()->json(['success' => "Stocck ".ucwords($request->type)." successfully added."]);
        } catch (\Exception $th) {
            DB::rollBack();

            return response()->json(["errors" => $th->getMessage()], 500);
        }
    }

    public function storeOutFromOrder($request, $order)
    {
        if ($request->to_warehou_type == null && $request->to_warehouse_id == null) {
            throw new \Exception('To Warehouse can\'t be empty.');
        }
        
        // Generate Code
        $getGenerateCode = $this->generateCode(new Request([
            'type' => $request->type,
            'date' => $request->date,
            'warehouse_type' => $request->to_warehouse_type,
        ]));
        $code = json_decode($getGenerateCode->getContent(), true)['data'];

        $stockInOut = new StockInOut();
        $stockInOut->warehouse_from_id = $request->from_warehouse_id;
        $stockInOut->warehouse_to_id = $request->to_warehouse_id;
        $stockInOut->code = $code;
        $stockInOut->temp_no = $request->temp_no;
        $stockInOut->date = $request->date;
        $stockInOut->type = $request->type;
        $stockInOut->description = $request->description;
        $stockInOut->user_id = Auth::user()->id;
        $stockInOut->order_id = $order->id;
        $stockInOut->save();

        $products = [];
        $qtys = [];
        $orderDetails = [];
        foreach ($request->orderDetail_product as $odProduct) {
            $orderDetail = OrderDetail::find($odProduct);
            if ($orderDetail->product_id != null) {
                array_push($products, $orderDetail->product_id);
                array_push($qtys, $orderDetail->qty);
                array_push($orderDetails, $orderDetail->id);
            } else if ($orderDetail->promo_id != null) {
                foreach ($orderDetail->promo->product_list() as $promoProdList) {
                    array_push($products, $promoProdList['id']);
                    array_push($qtys, $orderDetail->qty);
                    array_push($orderDetails, $orderDetail->id);
                }
            }
        }
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

            if ($request->type === "in") {
                $stock_to->quantity += $qtys[$i];
                $stock_to->save();
            } elseif ($request->type === "out") {
                if (($stock_from->quantity - $qtys[$i]) >= 0) {
                    $stock_from->quantity -= $qtys[$i];
                    $stock_from->save();
                } else {
                    // Validation Out Of Stock
                    throw new \Exception('Stock ' . ($i + 1) . ' is not enough');
                }
            }

            $stockInOutProduct = new StockInOutProduct();
            $stockInOutProduct->stock_in_out_id = $stockInOut->id;
            $stockInOutProduct->stock_from_id = $stock_from->id;
            $stockInOutProduct->stock_to_id = $stock_to->id;
            $stockInOutProduct->product_id = $products[$i];
            $stockInOutProduct->quantity = $qtys[$i];
            $stockInOutProduct->order_detail_id = $orderDetails[$i];
            $stockInOutProduct->save();
        }

        return $stockInOut;
    }

    public function pdfOutFromOrder(Request $request)
    {
        $order = Order::where('code', $request->code)->first();
        $order['district'] = $order->getDistrict();
        $stockInOut = StockInOut::find($request->stock_in_out);

        $products = [];
        foreach ($order->orderDetail as $orderDetail) {
            $status = "OUT";
            $quantity = $orderDetail->stock_id == $stockInOut->id ? $orderDetail->qty : 0;
            if ($orderDetail->type == "upgrade") {
                $status = "IN-UPGRADE**";
                if ($request->upgrade == 1) {
                    $quantity = $orderDetail->qty;
                }
            }
            if ($orderDetail->product_id != null) {
                $products[] = [
                    'code' => $orderDetail->product['code'],
                    'name' => $orderDetail->product['name'],
                    'status' => $status,
                    'quantity' => $quantity,
                ];
            } elseif ($orderDetail->promo_id != null) {
                foreach ($orderDetail->promo->product_list() as $promoProdList) {
                    $products[] = [
                        'code' => $promoProdList['code'],
                        'name' => $promoProdList['name'],
                        'status' => $status,
                        'quantity' => $quantity,
                    ];
                }
            }
        }

        $data = [
            'order' => $order,
            'stockInOut' => $stockInOut,
            'products' => $products,
        ];

        $pdf = PDF::loadView('admin.pdf_stock_out_order', $data);
        return $pdf->download('pdf_surat_jalan_'.$stockInOut->code.'.pdf');
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
        $stockInOut = StockInOut::where("code", $request->code)
            ->where('active', true)->first();

        $historyUpdate = HistoryUpdate::leftjoin('users','users.id', '=','history_updates.user_id' )
            ->select('history_updates.method', 'history_updates.created_at','history_updates.meta as meta' ,'users.name as name')
            ->where('type_menu', 'Stock In Out')
            ->where('menu_id', $stockInOut->id)->get();

        return view('admin.detail_stockio', compact(
            'stockInOut',
            'historyUpdate',
        ));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function editIn(Request $request)
    {
        $stockInOut = StockInOut::where("code", $request->code)
            ->where('type', 'in')
            ->where('active', true)->first();

        $products = Product::select("id", "code", "name")
            ->where("active", true)
            ->orderBy("code")
            ->get();

        return view("admin.update_stock_in", compact(
            "stockInOut",
            "products",
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
        $stockInOut = StockInOut::where("code", $request->code)
            ->where('type', 'out')
            ->where('active', true)->first();

        $products = Product::select("id", "code", "name")
            ->where("active", true)
            ->orderBy("code")
            ->get();

        return view("admin.update_stock_out", compact(
            "stockInOut",
            "products",
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
            'stock_in_out_id' => 'required|exists:stock_in_outs,id',
            'type' => 'required',
            'date' => 'required',
            'from_warehouse_id' => 'required|exists:warehouses,id',
            'to_warehouse_id' => 'required|different:from_warehouse_id|exists:warehouses,id',
            'code' => [
                'required',
                Rule::unique('stock_in_outs')
                    ->whereNot('id', $request->stock_in_out_id)
                    ->where('code', $request->code)
                    ->where('active', true)
            ],
        ], ['code.unique' => 'The Code already used Please Generate Other Code.']);
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
            $userId = Auth::user()["id"];
            $dataChanges = [];

            $stockInOut = StockInOut::find($request->stock_in_out_id);

            $old_warehouse_from_id = $stockInOut->warehouse_from_id;
            $old_warehouse_to_id = $stockInOut->warehouse_to_id;

            $stockInOut->warehouse_from_id = $request->from_warehouse_id;
            $stockInOut->warehouse_to_id = $request->to_warehouse_id;
            $stockInOut->code = $request->code;
            $stockInOut->temp_no = $request->temp_no;
            $stockInOut->date = $request->date;
            $stockInOut->type = $request->type;
            $stockInOut->description = $request->description;
            $stockInOut->save();

            $dataChanges = $stockInOut->getChanges();

            $products = $request->product;
            $countProduct = count($products);
            $old_stock_in_out_products_id = $request->old_stock_in_out_products_id;
            $stock_in_out_product_id = $request->stock_in_out_product_id;

            // Delete Stock In Out Product
            $diff_stock_in_out_id_arr = array_diff($old_stock_in_out_products_id, $stock_in_out_product_id);
            if ($diff_stock_in_out_id_arr) {
                $diff_stock_in_out_id = StockInOutProduct::whereIn("id", $diff_stock_in_out_id_arr)->get();
                foreach ($diff_stock_in_out_id as $diff_soi_id) {
                    $diff_soi_id->active = false;
                    $diff_soi_id->save();
                    if ($stockInOut->type == "in") {
                        $stock = Stock::where('id', $diff_soi_id->stock_to_id)->first();
                        $stock->quantity -= $diff_soi_id->quantity;
                    } else if ($stockInOut->type == "out") {
                        $stock = Stock::where('id', $diff_soi_id->stock_from_id)->first();
                        $stock->quantity += $diff_soi_id->quantity;
                    }
                    $stock->save();
                }

                // History Update
                $dataChanges["Product Deleted"] = $diff_stock_in_out_id_arr;
            }

            // Update Stock In Out Product
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

                if (isset($stock_in_out_product_id[$i])) {
                    $stockInOutProduct = StockInOutProduct::where('id', $stock_in_out_product_id[$i])->first();
                } else {
                    $stockInOutProduct = new StockInOutProduct();
                    $stockInOutProduct->quantity = 0;
                }

                if ($request->type === "in") {
                    if ($stockInOut->warehouse_to_id != $old_warehouse_to_id) {
                        $old_stock_to = Stock::where("warehouse_id", $old_warehouse_to_id)
                            ->where("product_id", $products[$i])
                            ->where("type_warehouse", null)
                            ->first();
                        $old_stock_to->quantity -= $stockInOutProduct->quantity;
                        $old_stock_to->save();
                        
                        $stock_to->quantity += $request->quantity[$i];
                        $stock_to->save();
                    } else {
                        $stock_to->quantity += ($request->quantity[$i] - $stockInOutProduct->quantity);
                        $stock_to->save();
                    }
                } elseif ($request->type === "out") {
                    $check_available_stock = $stockInOut->warehouse_from_id != $old_warehouse_from_id
                        ? $stock_from->quantity - $request->quantity[$i] >= 0
                        : ($stock_from->quantity - ($request->quantity[$i] - $stockInOutProduct->quantity)) >= 0;

                    if ($check_available_stock == true) {
                        if ($stockInOut->warehouse_from_id != $old_warehouse_from_id) {
                            $old_stock_from = Stock::where("warehouse_id", $old_warehouse_from_id)
                                ->where("product_id", $products[$i])
                                ->where("type_warehouse", null)
                                ->first();
                            $old_stock_from->quantity += $stockInOutProduct->quantity;
                            $old_stock_from->save();

                            $stock_from->quantity -= $request->quantity[$i];
                            $stock_from->save();
                        } else {
                            $stock_from->quantity -= ($request->quantity[$i] - $stockInOutProduct->quantity);
                            $stock_from->save();
                        }
                    } else {
                        // Validation Out Of Stock
                        $arr_Hasil = [];
                        $arr_Hasil['quantity[]'] = 'Stock ' . ($i + 1) . ' is not enough';
                        return response()->json(['errors' => $arr_Hasil]);
                    }
                }

                $stockInOutProduct->stock_in_out_id = $stockInOut->id;
                $stockInOutProduct->stock_from_id = $stock_from->id;
                $stockInOutProduct->stock_to_id = $stock_to->id;
                $stockInOutProduct->product_id = $products[$i];
                $stockInOutProduct->quantity = $request->quantity[$i];
                $stockInOutProduct->koli = $request->koli[$i] ?? 0;
                $stockInOutProduct->save();

                // History Update
                if ((isset($stock_in_out_product_id[$i]) && $stockInOutProduct->getChanges()) || !isset($stock_in_out_product_id[$i])) {
                    if (!isset($stock_in_out_product_id[$i])) {
                        $dataChanges[$stockInOutProduct->id] = "Product Add";
                    } else {
                        $dataChanges[$stockInOutProduct->id."_Product Update"] = $stockInOutProduct->getChanges();
                    }
                }
            }

            $history["type_menu"] = "Stock In Out";
            $history["method"] = "Update";
            $history["meta"] = json_encode(
                [
                    "user" => $userId,
                    "createdAt" => date("Y-m-d H:i:s"),
                    "dataChange" => $dataChanges
                ],
                JSON_THROW_ON_ERROR
            );
            $history["user_id"] = $userId;
            $history["menu_id"] = $stockInOut->id;
            HistoryUpdate::create($history);

            DB::commit();

            return response()->json(['success' => "Stock ".ucwords($request->type)." successfully updated."]);
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
            $stockInOut = StockInOut::where("code", $request->old_code)
                ->where("active", true)
                ->first();
            $stockInOut->active = false;
            $stockInOut->save();
            $dataChanges = $stockInOut->getChanges();

            $userId = Auth::user()["id"];

            // Deactivate History Stock based on code
            foreach ($stockInOut->stockInOutProduct as $stockInOutProduct) {
                // Change quantity in stock
                if ($stockInOut->type === "in") {
                    $stock = Stock::where("id", $stockInOutProduct->stock_to_id)->first();
                    $stock->quantity -= $stockInOutProduct->quantity;
                } elseif ($stockInOut->type === "out") {
                    $stock = Stock::where("id", $stockInOutProduct->stock_from_id)->first();
                    $stock->quantity += $stockInOutProduct->quantity;
                }
                $stock->save();

                // History (Stock)
                $history["type_menu"] = "Stock (Stock In Out)";
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
                $stockInOutProduct->active = false;
                $stockInOutProduct->save();
                
                $dataChanges["Product Deleted"][] = $stockInOutProduct->id;
            }

            // History (Stock In Out)
            $history["type_menu"] = "Stock In Out";
            $history["method"] = "Delete";
            $history["meta"] = json_encode(
                [
                    "user" => $userId,
                    "createdAt" => date("Y-m-d H:i:s"),
                    "dataChange" => $dataChanges,
                ],
                JSON_THROW_ON_ERROR
            );
            $history["user_id"] = $userId;
            $history["menu_id"] = $stockInOut->id;
            HistoryUpdate::create($history);

            DB::commit();

            return redirect()
                ->route("list_stock")
                ->with("success", "Stock ".ucwords($stockInOut->type)." successfully deleted.");
        } catch (Throwable $th) {
            DB::rollBack();

            return response()->json(["error" => $th->getMessage()], 500);
        }
    }
}
