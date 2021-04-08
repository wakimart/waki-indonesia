<?php

namespace App\Http\Controllers;

use App\HistoryUpdate;
use App\ProductService;
use App\Upgrade;
use App\Stock;
use App\HistoryStock;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UpgradeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexNew(Request $request)
    {
        $url = $request->all();
        $upgrades = Upgrade::where([['active', true], ['status', 'new']])->paginate(10);
        return view('admin.list_upgrade_new', compact('upgrades', 'url'));
    }

    public function list(Request $request)
    {
        $url = $request->all();
        $upgrades = Upgrade::where([['active', true], ['status', '!=', 'new']])->paginate(10);
        return view('admin.list_upgrade', compact('upgrades', 'url'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $upgrade = Upgrade::find($id);
        return view('admin.add_upgrade', compact('upgrade'));
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
            $data = $request->all();
            $upgrade = Upgrade::find($data['upgrade_id']);
            $upgrade['task'] = $data['task'];
            $upgrade['due_date'] = $data['due_date'];
            $upgrade['status'] = "process";
            $tempHistoryStatus = [];
            if ($upgrade['history_status'] != null) {
                $tempHistoryStatus = $upgrade['history_status'];
            }
            array_push($tempHistoryStatus, ['user_id' => Auth::user()['id'], 'status' => $upgrade['status'], 'updated_at' => date("Y-m-d H:i:s")]);
            $upgrade['history_status'] = $tempHistoryStatus;
            $upgrade->save();

            $productService = new ProductService();
            $productService->upgrade_id = $upgrade->id;
            $productService->save();


            //Pengecekan dan pembuatan stok
            $getProductId = null;
            $getOtherProduct = null;
            if($upgrade->acceptance['oldproduct_id'] != null){
                $getProductId = $upgrade->acceptance['oldproduct_id'];
            }else{
                $getOtherProduct = strtolower($upgrade->acceptance['other_product']);
            }
            
            $stocks = Stock::where('active', true)->select('stocks.product_id', 'stocks.other_product')->get();

            //pembuatan array isinya all product_id dan other_product yg ada
            $arr_stockId = [];
            $arr_stockOther = [];
            foreach ($stocks as $key => $item) {
                if($item->product_id != null){
                    if($arr_stockId == null){
                        array_push($arr_stockId, $item->product_id);
                    }else{
                        if(!in_array($item->product_id, $arr_stockId)){
                            array_push($arr_stockId, $item->product_id);
                        }            
                    }    
                }else if($item->other_product != null){
                    if($arr_stockOther == null){
                        array_push($arr_stockOther, strtolower($item->other_product));
                    }else{
                        if(!in_array(strtolower($item->other_product), $arr_stockOther)){
                            array_push($arr_stockOther, strtolower($item->other_product));  
                        }
                    }
                }
            }

            //cek di array
            $temp_arrId = [];
            if($getProductId != null){
                if(!in_array($getProductId, $arr_stockId)){
                    for ($i=0; $i < 2; $i++) { 
                        $data['product_id'] = $getProductId;
                        $data['quantity'] = 1;

                        $data['type_warehouse'] = "Display";
                        if($i > 0){
                            $data['type_warehouse'] = "Ready";
                        }
                        
                        $data['other_product'] = null;
                        $stock = Stock::create($data);

                        array_push($temp_arrId, $stock->id);
                    }

                    $history_stock['upgrade_id'] = $upgrade->id;
                    $history_stock['type_warehouse'] = "Display";
                    $history_stock['quantity'] = 1;
                    $history_stock['stock_id'] = $temp_arrId[0];
                    HistoryStock::create($history_stock);
                }else{
                    $stock = Stock::where([
                        ['active', true],
                        ['product_id', $getProductId],
                        ['type_warehouse', "Display"]
                    ])->get();

                    $stock['quantity'] = $stock['quantity'] + 1;
                    $stock->save();

                    $history_stock['upgrade_id'] = $upgrade->id;
                    $history_stock['type_warehouse'] = "Display";
                    $history_stock['quantity'] = 1;
                    $history_stock['stock_id'] = $stock['id'];
                    HistoryStock::create($history_stock);
                }
            }elseif ($getOtherProduct != null) {
                if(!in_array($getOtherProduct, $arr_stockOther)){
                    for ($i=0; $i < 2; $i++) { 
                        $data['product_id'] = null;
                        $data['quantity'] = 1;
                        
                        $data['type_warehouse'] = "Display";
                        if($i > 0){
                            $data['type_warehouse'] = "Ready";
                        }

                        $data['other_product'] = $getOtherProduct;
                        $stock = Stock::create($data);

                        array_push($temp_arrId, $stock->id);
                    }

                    $history_stock['upgrade_id'] = $upgrade->id;
                    $history_stock['type_warehouse'] = "Display";
                    $history_stock['stock_id'] = $temp_arrId[0];
                    HistoryStock::create($history_stock);
                }else{
                    $stock = Stock::where([
                        ['active', true],
                        ['other_product', 'like' , "%{$getOtherProduct}%"],
                        ['type_warehouse', "Display"]
                    ])->get();
                    $stock['quantity'] = $stock['quantity'] + 1;
                    $stock->save();

                    $history_stock['upgrade_id'] = $upgrade->id;
                    $history_stock['type_warehouse'] = "Display";
                    $history_stock['quantity'] = 1;
                    $history_stock['stock_id'] = $stock['id'];
                    HistoryStock::create($history_stock);
                }
            }

            DB::commit();
            return redirect()->route("detail_upgrade_form", ["id" => $upgrade->id]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                "error" => $e
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Upgrade  $upgrade
     * @return \Illuminate\Http\Response
     */
    public function detail($id)
    {
        $upgrade = Upgrade::find($id);
        return view('admin.detail_upgrade', compact('upgrade'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function edit(Request $request)
    {
        if (!empty($request->id)) {
            $upgrade = Upgrade::find($request->id);

            return view('admin.update_upgrade', compact('upgrade'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function update(Request $request)
    {
        if (!empty($request->id)) {
            DB::beginTransaction();

            try {
                $upgrade = Upgrade::find($request->id);
                $upgrade = $upgrade->fill($request->only("due_date", "task"));
                $upgrade->save();


                return response()->json(["test" => "masuk"]);

                $user = Auth::user();
                $historyUpdateUpgrade["type_menu"] = "Upgrade";
                $historyUpdateUpgrade["method"] = "Update";
                $historyUpdateUpgrade["meta"] = json_encode(
                    [
                        "user" => $user["id"],
                        "createdAt" => date("Y-m-d H:i:s"),
                        "dataChange" => $upgrade->getChanges(),
                    ]
                );
                $historyUpdateUpgrade["user_id"] = $user["id"];
                $historyUpdateUpgrade["menu_id"] = $request->id;
                HistoryUpdate::create($historyUpdateUpgrade);

                DB::commit();
                return redirect()->route("detail_upgrade_form", ["id" => $upgrade->id]);
            } catch (\Exception $e) {
                DB::rollback();

                return response()->json([
                    "error" => $e,
                ]);
            }
        }
    }

    public function updateStatus(Request $request)
    {
        if (!empty($request->id)) {
            DB::beginTransaction();

            try {
                $upgrade = Upgrade::find($request->id);

                $getProductId = null;
                $getOtherProduct = null;
                if($upgrade->acceptance['oldproduct_id'] != null){
                    $getProductId = $upgrade->acceptance['oldproduct_id'];
                }else{
                    $getOtherProduct = strtolower($upgrade->acceptance['other_product']);
                }

                //pembuatan history stock
                if($request->status == "Ready" && $getProductId != null){
                    $stocks = Stock::where('product_id', $getProductId)->get(); 

                    foreach ($stocks as $key => $stock) {
                        if($stock['type_warehouse'] == "Display"){
                            $stock['quantity'] = $stock['quantity'] - 1;
                            $stock->save();

                            $history_stock['upgrade_id'] = $upgrade->id;
                            $history_stock['type_warehouse'] = "Display";
                            $history_stock['quantity'] = -1;
                            $history_stock['stock_id'] = $stock['id'];
                            HistoryStock::create($history_stock);
                        }else if($stock['type_warehouse'] == "Ready") {
                            $stock['quantity'] = $stock['quantity'] + 1;
                            $stock->save();

                            $history_stock['upgrade_id'] = $upgrade->id;
                            $history_stock['type_warehouse'] = "Ready";
                            $history_stock['quantity'] = 1;
                            $history_stock['stock_id'] = $stock['id'];
                            HistoryStock::create($history_stock);
                        }
                    }
                }else if($request->status == "Ready" && $getOtherProduct != null){
                    $stocks = Stock::where('other_product', 'like', '%$getProductId%')->get();

                    foreach ($stocks as $key => $stock) {
                        if($stock['type_warehouse'] == "Display"){
                            $stock['quantity'] = $stock['quantity'] - 1;
                            $stock->save();

                            $history_stock['upgrade_id'] = $upgrade->id;
                            $history_stock['type_warehouse'] = "Display";
                            $history_stock['quantity'] = -1;
                            $history_stock['stock_id'] = $stock['id'];
                            HistoryStock::create($history_stock);
                        }else if($stock['type_warehouse'] == "Ready") {
                            $stock['quantity'] = $stock['quantity'] + 1;
                            $stock->save();

                            $history_stock['upgrade_id'] = $upgrade->id;
                            $history_stock['type_warehouse'] = "Ready";
                            $history_stock['quantity'] = 1;
                            $history_stock['stock_id'] = $stock['id'];
                            HistoryStock::create($history_stock);
                        }
                    }
                }
                //end pembentukan history stock                

                // TODO: Ganti "IF" bagian ini jika bagian lain yang dibutuhkan sudah selesai
                if (
                    $request->status === "Display"
                    || $request->status === "Ready"
                ) {
                    $request->status = "Completed";
                }

                // Memperbarui status
                $upgrade->status = $request->status;

                $user = Auth::user();
                // Memperbarui history_status
                $historyStatus = $upgrade->history_status;
                $historyStatus[] = [
                    "user_id" => $user["id"],
                    "status" => strtolower($request->status),
                    "updated_at" => date("Y-m-d H:i:s"),
                ];
                $upgrade->history_status = $historyStatus;
                $upgrade->save();

                // Menambahkan riwayat pembaruan ke tabel history_updates
                $historyUpdateUpgrade["type_menu"] = "Upgrade";
                $historyUpdateUpgrade["method"] = "Update";
                $historyUpdateUpgrade["meta"] = json_encode(
                    [
                        "user" => $user["id"],
                        "createdAt" => date("Y-m-d H:i:s"),
                        "dataChange" => $upgrade->getChanges(),
                    ]
                );
                $historyUpdateUpgrade["user_id"] = $user["id"];
                $historyUpdateUpgrade["menu_id"] = $request->id;
                HistoryUpdate::create($historyUpdateUpgrade);

                DB::commit();

                return redirect()->route("detail_upgrade_form", ["id" => $upgrade->id]);
            } catch (\Exception $e) {
                DB::rollback();

                return response()->json([
                    "error" => $e,
                ]);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $upgrade = Upgrade::find($id);
            $upgrade["active"] = false;
            $upgrade->save();

            $user = Auth::user();
            $historyDeleteUpgrade["type_menu"] = "Upgrade";
            $historyDeleteUpgrade["method"] = "delete";
            $historyDeleteUpgrade["meta"] = json_encode(
                [
                    "user" => $user["id"],
                    "createdAt" => date("Y-m-d H:i:s"),
                    "dataChange" => $upgrade->getChanges(),
                ]
            );
            $historyDeleteUpgrade["user_id"] = $user['id'];
            $historyDeleteUpgrade["menu_id"] = $id;

            HistoryUpdate::create($historyDeleteUpgrade);

            DB::commit();

            return redirect()->route("list_new_upgrade_form")->with(
                "success",
                "Data berhasil dihapus!"
            );
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                "error" => $e,
            ]);
        }
    }
}
