<?php

namespace App\Http\Controllers;

use App\Product;
use App\ProductService;
use App\Service;
use App\Sparepart;
use App\Upgrade;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $services = Service::where('active', true)
            ->orderBy("service_date", "desc")
            ->get();
        $upgrades = Upgrade::where('active', true)->get();
        $countServices = $services->count();
        $countUpgrades = $upgrades->count();
        return view('admin.list_taskservice', compact('countServices', 'services', 'upgrades', 'countUpgrades'));
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
     * @param  \App\ProductService  $productService
     * @return \Illuminate\Http\Response
     */
    public function show(ProductService $productService)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ProductService  $productService
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        if($request->has('id')){
            $product_services = ProductService::where('service_id',$request->get('id'))->get();
            $products = Product::all();
            $spareparts = Sparepart::where('active', true)->get();
            return view('admin.update_productservice', compact('product_services','products', 'spareparts'));
        }
    }

    public function editUpgrade(Request $request)
    {
        if($request->has('id')){
            $upgrades = Upgrade::where('id', $request->get('id'))->get();
            $product_services = ProductService::where('upgrade_id',$request->get('id'))->get();
            $products = Product::all();
            $spareparts = Sparepart::where('active', true)->get();
            return view('admin.update_productupgrade', compact('upgrades', 'product_services','products', 'spareparts'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ProductService  $productService
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //UPDATE KHUSUS SERVICE
        DB::beginTransaction();
        try {
            $get_allProductService = json_decode($request->productservices);

            foreach ($get_allProductService as $key => $item_productservice) {
                $data = $request->all();
                $product_services = ProductService::find($item_productservice[0]);

                $index = 0;
                $data['arr_sparepart'] = [];
                foreach ($item_productservice[1] as $item_sparepart) {
                    if($item_sparepart[0] == 'other'){
                        //cek sparepart ada/tidak
                        $spareparts = Sparepart::where([
                            ['active', true],
                            ['name', 'like', '%{$item_sparepart[2]}%']
                        ])->get();

                        if(count($spareparts) == 0){
                            $sparepart['name'] = $item_sparepart[2];
                            $sparepart['price'] = $item_sparepart[3];
                            $new_sparepart = Sparepart::create($sparepart);

                            $data['arr_sparepart'][$index] = [];
                            $data['arr_sparepart'][$index]['id'] = $new_sparepart->id;
                            $data['arr_sparepart'][$index]['qty'] = $item_sparepart[1];
                            $index++;
                        }
                    }else{
                        $data['arr_sparepart'][$index] = [];
                        $data['arr_sparepart'][$index]['id'] = $item_sparepart[0];
                        $data['arr_sparepart'][$index]['qty'] = $item_sparepart[1];
                        $index++;
                    }
                }

                $product_services->sparepart = json_encode($data['arr_sparepart']);
                $product_services->save();
            }

            $services = Service::find($get_allProductService[0][2]);

            if($request->repairedservices == "true"){

                $arr_history = json_decode($services['history_status']);
                array_push($arr_history, ['user_id' => Auth::user()['id'], 'status' => "repaired", 'updated_at' => date("Y-m-d H:i:s")]);
                $services->history_status = json_encode($arr_history);
                $services->status = "Repaired";
                $services->save();
            }else if($request->repairedservices == "false"){
                $arr_history = array();
                array_push($arr_history, ['user_id' => Auth::user()['id'], 'status' => "process", 'updated_at' => date("Y-m-d H:i:s")]);
                $services->history_status = json_encode($arr_history);
                $services->status = "Process";
                $services->save();
            }

            DB::commit();
            return response()->json(['success' => 'Berhasil!!!']);
        } catch (\Exception $ex) {
            DB::rollback();
            return response()->json(['errors' => $ex]);
        }
    }

    public function updateUpgrade(Request $request)
    {
        //UPDATE KHUSUS SERVICE
        DB::beginTransaction();
        try {
            $get_allProductService = json_decode($request->productservices);

            foreach ($get_allProductService as $key => $item_productservice) {
                $data = $request->all();
                $product_services = ProductService::find($item_productservice[0]);

                if(count($item_productservice[1]) > 0){
                    $index = 0;
                    $data['arr_sparepart'] = [];
                    foreach ($item_productservice[1] as $item_sparepart) {
                        if($item_sparepart[0] == 'other'){
                            //cek sparepart ada/tidak
                            $spareparts = Sparepart::where([
                                ['active', true],
                                ['name', 'like', '%{$item_sparepart[2]}%']
                            ])->get();

                            if(count($spareparts) == 0){
                                $sparepart['name'] = $item_sparepart[2];
                                $sparepart['price'] = $item_sparepart[3];
                                $new_sparepart = Sparepart::create($sparepart);

                                $data['arr_sparepart'][$index] = [];
                                $data['arr_sparepart'][$index]['id'] = $new_sparepart->id;
                                $data['arr_sparepart'][$index]['qty'] = $item_sparepart[1];
                                $index++;
                            }
                        }else{
                            $data['arr_sparepart'][$index] = [];
                            $data['arr_sparepart'][$index]['id'] = $item_sparepart[0];
                            $data['arr_sparepart'][$index]['qty'] = $item_sparepart[1];
                            $index++;
                        }
                    }
                    $product_services->sparepart = json_encode($data['arr_sparepart']);
                }
                $product_services->save();
            }

            if($request->repairedservices == "true"){
                $upgrades = Upgrade::find($get_allProductService[0][2]);
                $arr_old_history = $upgrades['history_status'];
                array_push($arr_old_history, ['user_id' => Auth::user()['id'], 'status' => "repaired", 'updated_at' => date("Y-m-d h:i:s")]);

                $upgrades->history_status = $arr_old_history;
                $upgrades->status = "Repaired";
                $upgrades->save();
            }

            DB::commit();
            return response()->json(['success' => 'Berhasil!!!']);
        } catch (\Exception $ex) {
            DB::rollback();
            return response()->json(['errors' => $ex]);
        }
    }

    public function updateFailRepair(Request $request)
    {
        DB::beginTransaction();

        try {
            $service = Service::find($request->id);
            $service->fill($request->only(
                "status",
                "fail_repair_description",
            ));
            $service->history_status = json_encode(
                [
                    "user_id" => Auth::user()["id"],
                    "status" => "repaired",
                    "updated_at" => date("Y-m-d H:i:s"),
                ],
                JSON_THROW_ON_ERROR
            );
            $service->save();

            DB::commit();

            return redirect()->route("edit_taskservice", ["id" => $request->id]);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                "result" => 0,
                "error" => $e,
                "error message" => $e->getMessage(),
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ProductService  $productService
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductService $productService)
    {
        //
    }

    public function fetchProductService(Request $request)
    {
        $productServices = ProductService::where('service_id', $request->service_id)
            ->where('active', 1)->get();
        if(count($productServices) > 0) {
            return [
                'result' =>'true',
                'data' => $productServices
            ];
        }

        return [
            'result' =>'false',
            'data' => $productServices
        ];
    }
}
