<?php

namespace App\Http\Controllers;

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
        $data['appointment'] = $data['date']." ".$data['time'];
        $order = HomeService::create($data);

        return redirect()->route('homeServices_success', ['code'=>$order['code']]);
    }

    public function successRegister(Request $request){
        $homeService = HomeService::where('code', $request['code'])->first();
        return view('homeservicesuccess', compact('homeService'));
    }

    public function admin_ListHomeService(){
        $awalBulan = Carbon::now()->startOfMonth()->subMonth(4);
        $akhirBulan = Carbon::now()->startOfMonth()->addMonth(12);//5
        $homeServices = HomeService::whereBetween('appointment', array($awalBulan, $akhirBulan))
                     ->orderBy('appointment', 'asc')->get();
        return view('admin.list_homeservice', compact('homeServices', 'awalBulan', 'akhirBulan'));
    }

    public function admin_fetchHomeService(Request $request){
        //$data['year'], $data['month']
        $data = $request->all();
        $homeServices = HomeService::whereYear('appointment', $data['year'])->whereMonth('appointment', $data['month'])->orderBy('appointment', 'asc')->get();
        return response()->json($homeServices);
    }
}
