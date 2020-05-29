<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\HomeService;
use App\Branch;
use App\Cso;
use Carbon\Carbon;

class HomeServiceController extends Controller
{
    public function index()
    {
    	$branches = Branch::all();
        return view('homeservice', compact('branches'));
    }

    public function store(Request $request){
        $data = $request->all();
        $data['code'] = "HS/".strtotime(date("Y-m-d H:i:s"))."/".substr($data['phone'], -4);
        $data['cso_id'] = Cso::where('code', $data['cso_id'])->first()['id'];
        $data['cso2_id'] = Cso::where('code', $data['cso2_id'])->first()['id'];
        $data['appointment'] = $data['date']." ".$data['time'];
        $order = HomeService::create($data);

        return redirect()->route('homeServices_success', ['code'=>$order['code']]);
    }

    public function successRegister(Request $request){
        $homeService = HomeService::where('code', $request['code'])->first();
        return view('homeservicesuccess', compact('homeService'));
    }

    public function admin_ListHomeService(){
        $branches = Branch::all();
        $awalBulan = Carbon::now()->startOfMonth()->subMonth(4);
        $akhirBulan = Carbon::now()->startOfMonth()->addMonth(5);//5

        //khususu head-manager, head-admin, admin
        $homeServices = HomeService::whereBetween('appointment', array($awalBulan, $akhirBulan))
                     ->orderBy('appointment', 'asc')->where('active', true)->get();

        //khusus akun CSO
        if(Auth::user()->roles[0]['slug'] == 'cso'){
            $homeServices = Auth::user()->cso->home_service->where('active', true);
        }

        //khusus akun branch dan area-manager
        if(Auth::user()->roles[0]['slug'] == 'branch' || Auth::user()->roles[0]['slug'] == 'area-manager'){
            $arrbranches = [];
            foreach (Auth::user()->listBranches() as $value) {
                array_push($arrbranches, $value['id']);
            }
            $homeServices = HomeService::whereBetween('appointment', array($awalBulan, $akhirBulan))
                     ->whereIn('id', $arrbranches)->orderBy('appointment', 'asc')->where('active', true)->get();
        }

        return view('admin.list_homeservice', compact('homeServices', 'awalBulan', 'akhirBulan', 'branches'));
    }

    public function admin_fetchHomeService(Request $request){
        //$data['year'], $data['month']
        $data = $request->all();
        $homeServices = HomeService::whereYear('appointment', $data['year'])->whereMonth('appointment', $data['month'])->orderBy('appointment', 'asc')->get();
        return response()->json($homeServices);
    }

    public function edit(Request $request)
    {
        if($request->has('id')){
            $data = HomeService::find($request->id);
            return response()->json(['result' => $data]);
        }else{
            return response()->json(['result' => 'Gagal!!']);
        }
    }

    public function update(Request $request){
        $homeService = HomeService::find($request->id);
        if($request->has('cancel')){
            $homeService->active = false;
            $homeService->save();
        }
        else if($request->has('cash')){
            if($request->cash == 0){
                $homeService->cash = false;
            }
            else{
                $homeService->cash = true;
            }
            $homeService->cash_description = $request['cash_description'];
            $homeService->save();
        }
        else{
            $data = $request->all();
            $data['code'] = "HS/".strtotime(date("Y-m-d H:i:s"))."/".substr($data['phone'], -4);
            $data['cso_id'] = Cso::where('code', $data['cso_id'])->first()['id'];
            $data['cso2_id'] = Cso::where('code', $data['cso2_id'])->first()['id'];
            $data['appointment'] = $data['date']." ".$data['time'];
            $homeService->fill($data)->save();
        }
        

        return $this->admin_ListHomeService();
    }
}
