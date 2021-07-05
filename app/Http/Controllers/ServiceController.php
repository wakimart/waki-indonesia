<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use DB;
use App\HomeService;
use App\ProductService;
use App\Sparepart;
use App\Service;
use App\Product;
use App\Branch;
use App\Cso;

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

    public function indexUser(Request $request)
    {
        $url = $request->all();
        $services = Service::where('active', true)->get();
        $countServices = $services->count();
        $services = $services->paginate(10);
        return view('service', compact('services', 'countServices', 'url'));
    }

    public function trackService($id){
        $services = Service::find($id);
        $branches = Branch::where('active', true)->get();
        return view('track_service', compact('services', 'branches'));
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
            $temp_id = DB::select("SHOW TABLE STATUS LIKE 'services'");
            $data['code'] = "SERVICE/".$temp_id[0]->Auto_increment."/".date("Ymd");
            $service = Service::create($data);

            $get_allProductService = json_decode($request->productservices);

            foreach ($get_allProductService as $key => $value) {
                $data['service_id'] = $service->id;

                $data['product_id'] = null;
                $data['other_product'] = null;
                if($value[0] != "other"){
                    $data['product_id'] = $value[0];
                }else{
                    $data['other_product'] = $value[4];
                }
                
                
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
    public function show($id, Request $request)
    {
        $services = Service::find($id);
        $branches = Branch::where('active', true)->get();
        return view('admin.detail_service', compact('services', 'branches', 'request'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        if($request->has('id')){
            $services = Service::find($request->get('id'));
            $products = Product::all();
            $spareparts = Sparepart::where('active', true)->get();
            $product_services = ProductService::where([
                    ['active', '=', 1],
                    ['service_id', '=', $request->get('id')]
                ])->get();
            return view('admin.update_service', compact('services','products', 'spareparts', 'product_services'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        DB::beginTransaction();
        try {
            $get_allProductService = json_decode($request->productservices);
            $get_oldProductService = ProductService::where('service_id', $get_allProductService[0][0])->get();

            $data = $request->only('no_mpc', 'name', 'address', 'phone', 'service_date');
            $service = Service::find($get_allProductService[0][0]);
            $service->no_mpc = $data['no_mpc'];
            $service->name = $data['name'];
            $service->address = $data['address'];
            $service->phone = $data['phone'];
            $service->service_date = $data['service_date'];
            $service->save();

            foreach ($get_allProductService as $key => $value) {
                if($value[0] != null ){
                    $product_services = ProductService::find($value[1]);

                    if($value[2] != 'other'){
                        $product_services->product_id = $value[2];
                    }else{
                        $product_services->other_product = $value[6];
                    }

                    $data['arr_issues'] = [];
                    $data['arr_issues'][0]['issues'] = $value[3];
                    $data['arr_issues'][1]['desc'] = $value[4];
                    $product_services->issues = json_encode($data['arr_issues']);

                    $product_services->due_date = $value[5];

                    if($value[7] == "0"){
                        $product_services->active = false;
                    }
                    $product_services->save();    
                }else{
                    //ada produk service baru
                    $product_services = new ProductService();
                    $product_services->service_id = $get_allProductService[0][0];

                    if($value[2] != 'other'){
                        $product_services->product_id = $value[2];
                    }else{
                        $product_services->other_product = $value[6];
                    }

                    $data['arr_issues'] = [];
                    $data['arr_issues'][0]['issues'] = $value[3];
                    $data['arr_issues'][1]['desc'] = $value[4];
                    $product_services->issues = json_encode($data['arr_issues']);

                    $product_services->due_date = $value[5];

                    if($value[7] == "0"){
                        $product_services->active = false;
                    }
                    $product_services->save();    

                }
                
            }
            DB::commit();
            return response()->json(['success' => 'Berhasil!!!']);
        } catch (\Exception $ex) {
            DB::rollback();
            return response()->json(['errors' => $ex]);
        }
    }


    public function updateStatus(Request $request){
        if(!empty($request->id)){
            DB::beginTransaction();
            try {
                $service = Service::find($request->id);

                if($request->status == "Quality_Control" || $request->status == "Completed"){
                    $service->status = str_replace('_', ' ', $request->status);

                    $user = Auth::user();
                    $arr_old_history = json_decode($service->history_status);

                    array_push($arr_old_history, ['user_id' => Auth::user()['id'], 'status' => strtolower($request->status), 'updated_at' => date("Y-m-d H:i:s")]);
                    $service->history_status = json_encode($arr_old_history);
                    $service->save();
                }else if($request->status == "Delivery" || $request->status == "Pickup"){
                    $arr_serviceoption = [];
                    $cso = Cso::where('code', $request->input('cso_id'))->first();

                    if($request->status == "Pickup"){
                        array_push($arr_serviceoption, 
                            [
                                'recipient_name' => $request->input('name'),
                                'address' => $request->input('address'),
                                'recipient_phone' => $request->input('phone'),
                                'branch_id' => $request->input('branch_id'),
                                'cso_id' => $cso['id']
                            ]
                        );
                    }
                    else if($request->status == "Delivery"){
                        $tglJamNya = $request->date." ".$request->time;
                        $checkHS = HomeService::where([['cso_id', $cso['id']], ['appointment', $tglJamNya], ['active', true]])->get();
                        if(sizeof($checkHS) > 0){
                            $reqError = new Request;
                            $reqError->replace(['input' => $request->all(), 'errMessage' => "Jadwal Home Lapangan Tidak Valid !"]);
                            return $this->show($request->id, $reqError);
                        }
                        else{
                            $data = $request->all();
                            $data['no_member'] = $service->no_mpc;
                            $data['code'] = "HS/".strtotime(date("Y-m-d H:i:s"))."/".substr($data['phone'], -4);
                            $data['province'] = $data['province_id'];
                            $data['distric'] = $data['subDistrict'];
                            $data['type_customer'] = "WAKi Customer (Type B)";
                            $data['type_homeservices'] = "Home Delivery";
                            $data['appointment'] = $data['date']." ".$data['time'];
                            $data['cso_id'] = Cso::where('code', $data['cso_id'])->first()['id'];
                            $data['cso_phone'] = Cso::where('code', $data['cso_id'])->first()['phone'];
                            $data['cso2_id'] = (Cso::where('code', 'SERVICE')->first() != null ? Cso::where('code', 'SERVICE')->first()['id'] : null );

                            $homeService = HomeService::create($data);
                            array_push($arr_serviceoption, 
                                [
                                    'homeService' => $homeService['id']
                                ]
                            );
                        }
                    }

                    
                    $service->service_option = json_encode($arr_serviceoption);

                    $service->status = str_replace('_', ' ', $request->status);
                    $user = Auth::user();
                    $arr_old_history = json_decode($service->history_status);
                    array_push($arr_old_history, ['user_id' => Auth::user()['id'], 'status' => strtolower($request->status), 'updated_at' => date("Y-m-d H:i:s")]);
                    $service->history_status = json_encode($arr_old_history);
                    $service->save();
                }
                
                DB::commit();
                return redirect()->route("detail_service", ["id" => $service->id]);
            } catch (\Exception $ex) {
                DB::rollback();
                return response()->json(['errors' => $ex]);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if (!empty($request->id)) {
            DB::beginTransaction();

            try {
                $services = Service::find($request->id);
                $services->active = false;
                $services->save();

                DB::commit();

                return redirect()
                    ->route("list_service")
                    ->with("success", "Data service berhasil dihapus.");
            } catch (Exception $e) {
                DB::rollback();

                return response()->json([
                    "error" => $e,
                ]);
            }
        }

        return response()->json(["error" => "Data tidak ditemukan."]);
    }
}
