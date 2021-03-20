<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use DB;
use App\ProductService;
use App\Sparepart;
use App\Service;
use App\Product;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $url = $request->all();
        $services = Service::where('active', true)->get();
        $countServices = $services->count();
        $services = $services->paginate(10);
        return view('admin.list_service', compact('services', 'countServices', 'url'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $products = Product::all();
        $spareparts = Sparepart::where('active', true)->get();
        return view('admin.add_service', compact('products', 'spareparts'));
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
            $data = $request->only('no_mpc', 'name', 'address', 'phone', 'service_date');
            $data['status'] = 'New';
            $service = Service::create($data);

            $get_allProductService = json_decode($request->productservices);

            foreach ($get_allProductService as $key => $value) {
                $data['service_id'] = $service->id;
                $data['product_id'] = $value[0];
                
                // $index = 0;
                // $data['arr_sparepart'] = [];
                // foreach ($value[1] as $item_sparepart) {
                //     $data['arr_sparepart'][$index] = [];
                //     $data['arr_sparepart'][$index]['id'] = $item_sparepart[0];
                //     $data['arr_sparepart'][$index]['qty'] = $item_sparepart[1];
                //     $index++;
                // }

                // $data['sparepart'] = json_encode($data['arr_sparepart']);

                $data['arr_issues'] = [];
                $data['arr_issues'][0]['issues'] = $value[1];
                $data['arr_issues'][1]['desc'] = $value[2];

                $data['issues'] = json_encode($data['arr_issues']);
                $data['due_date'] = $value[3];

                $product_services = ProductService::create($data);
            }
            DB::commit();
            return response()->json(['success' => 'Berhasil!!!']);

        } catch (\Exception $ex) {
            DB::rollback();
            return response()->json(['errors' => $ex]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function show(Service $service)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function edit(Service $service)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Service $service)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function destroy(Service $service)
    {
        //
    }
}
