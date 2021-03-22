<?php

namespace App\Http\Controllers;

use App\ProductService;
use App\Product;
use App\Sparepart;
use App\Service;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $services = Service::where('active', true)->get();
        $countServices = $services->count();
        return view('admin.list_taskservice', compact('countServices', 'services'));
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
                    $data['arr_sparepart'][$index] = [];
                    $data['arr_sparepart'][$index]['id'] = $item_sparepart[0];
                    $data['arr_sparepart'][$index]['qty'] = $item_sparepart[1];
                    $index++;
                }

                $product_services['sparepart'] = json_encode($data['arr_sparepart']);
                $product_services->save();
            }


            
            $services = Service::find($get_allProductService[0][0]);
            $arr_history = [];
            array_push($arr_history, ['user_id' => Auth::user()['id'], 'status' => "Process", 'updated_at' => date("Y-m-d h:i:s")]);
            $services['history_status'] = json_encode($arr_history);
            $services['status'] = "Process";
            $services->save();

            DB::commit();
            return response()->json(['success' => 'Berhasil!!!']);
        } catch (\Exception $ex) {
            DB::rollback();
            return response()->json(['errors' => $ex]);
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
}
