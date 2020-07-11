<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\HomeService;
use App\Branch;
use App\Cso;
use App\User;
use App\Utils;
use Carbon\Carbon;
use App\Exports\HomeServicesExport;
use Maatwebsite\Excel\Facades\Excel;

class HomeServiceController extends Controller
{
    public function index()
    {
    	$branches = Branch::all();
        return view('homeservice', compact('branches'));
    }

    public function store(Request $request){
        $data = $request->all(); dd($data);
        $data['code'] = "HS/".strtotime(date("Y-m-d H:i:s"))."/".substr($data['phone'], -4);
        $data['cso_id'] = Cso::where('code', $data['cso_id'])->first()['id'];
        if($request->has('cso2_id')){
            $data['cso2_id'] = Cso::where('code', $data['cso2_id'])->first()['id'];            
        }
        $data['appointment'] = $data['date']." ".$data['time'];
        $order = HomeService::create($data);

        return redirect()->route('homeServices_success', ['code'=>$order['code']]);
    }

    public function successRegister(Request $request){
        $homeService = HomeService::where('code', $request['code'])->first();
        return view('homeservicesuccess', compact('homeService'));
    }

    public function admin_ListHomeService(Request $request){
        $branches = Branch::Where('active', true)->get();
        $awalBulan = Carbon::now()->startOfMonth()->subMonth(4);
        $akhirBulan = Carbon::now()->startOfMonth()->addMonth(5);//5
        $arrbranches = [];

        //khususu head-manager, head-admin, admin
        $homeServices = HomeService::whereBetween('appointment', array($awalBulan, $akhirBulan))
                     ->where('active', true);

        //khusus akun CSO
        if(Auth::user()->roles[0]['slug'] == 'cso'){
            $homeServices = HomeService::whereBetween('appointment', array($awalBulan, $akhirBulan))->where('cso_id', Auth::user()->cso['id'])->where('active', true);
        }

        //khusus akun branch dan area-manager
        if(Auth::user()->roles[0]['slug'] == 'branch' || Auth::user()->roles[0]['slug'] == 'area-manager'){
            foreach (Auth::user()->listBranches() as $value) {
                array_push($arrbranches, $value['id']);
            }
            $homeServices = HomeService::whereBetween('appointment', array($awalBulan, $akhirBulan))
                     ->whereIn('branch_id', $arrbranches)->where('active', true);
        }

        //kalau ada filter
        if($request->has('filter_city')){
            $homeServices = $homeServices->where('city', 'like', '%'.$request->filter_city.'%');
        }
        if($request->has('filter_branch') && Auth::user()->roles[0]['slug'] != 'branch'){
            $homeServices = $homeServices->where('branch_id', $request->filter_branch);
        }
        if($request->has('filter_cso') && Auth::user()->roles[0]['slug'] != 'cso'){
            $homeServices = $homeServices->where('cso_id', $request->filter_cso);
        }

        $homeServices = $homeServices->get();

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
            if($request->has('cso2_id')){
                $data['cso2_id'] = Cso::where('code', $data['cso2_id'])->first()['id'];            
            }
            $data['appointment'] = $data['date']." ".$data['time'];
            $homeService->fill($data)->save();
        }
        
        $req = new Request();
        return $this->admin_ListHomeService($req);
    }

    public function export_to_xls(Request $request)
    {
        $date = null;
        $city = null;
        $branch = null;
        $cso = null;
        if($request->has('date')){
            $date = $request->date;
        }
        if($request->has('filter_city')){
            $city = $request->filter_city;
        }
        if($request->has('filter_branch')){
            $branch = $request->filter_branch;
        }
        if($request->has('filter_cso')){
            $cso = $request->filter_cso;
        }
        return Excel::download(new HomeServicesExport($date, $city, $branch, $cso), 'Home Service.xlsx');
    }

    //KHUSUS API APPS
    public function addApi(Request $request)
    {
        $messages = array(
                'cso_id.required' => 'The CSO Code field is required.',
                'cso_id.exists' => 'Wrong CSO Code.',
                'branch_id.required' => 'The Branch must be selected.',
                'branch_id.exists' => 'Please choose the branch.',
                'cso2_id.required' => 'The CSO Code field is required.',
                'cso2_id.exists' => 'Wrong CSO Code.'
            );

        $validator = \Validator::make($request->all(), [
            'name' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'city' => 'required',
            'cso_id' => ['required', 'exists:csos,code'],
            'branch_id' => ['required', 'exists:branches,id'],
            'cso_phone' => 'required',
            'date' => 'required',
            'time' => 'required',
            'cso2_id' => ['exists:csos,code']
        ], $messages);

        if ($validator->fails()){
            $data = ['result' => 0,
                     'data' => $validator->errors()
                    ];
            return response()->json($data, 401);
        }
        else{
            $data = $request->all();
            $data['code'] = "HS/".strtotime(date("Y-m-d H:i:s"))."/".substr($data['phone'], -4);
            $data['cso_id'] = Cso::where('code', $data['cso_id'])->first()['id'];
            if($request->has('cso2_id')){
                $data['cso2_id'] = Cso::where('code', $data['cso2_id'])->first()['id'];            
            }
            $data['appointment'] = $data['date']." ".$data['time'];
            $homeservice = HomeService::create($data);

            $data = ['result' => 1,
                     'data' => $homeservice
                    ];
            return response()->json($data, 200);
        }
    }

    public function updateApi(Request $request)
    {
        $messages = array(
                'id.required' => 'There\'s an error with the data.',
                'id.exists' => 'There\'s an error with the data.',
                'cso_id.required' => 'The CSO Code field is required.',
                'cso_id.exists' => 'Wrong CSO Code.',
                'branch_id.required' => 'The Branch must be selected.',
                'cso2_id.required' => 'The CSO Code field is required.',
                'cso2_id.exists' => 'Wrong CSO Code.'
            );

        $validator = \Validator::make($request->all(), [
            'id' => ['required', 'exists:home_services,id,active,1'],
            'name' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'city' => 'required',
            'cso_id' => ['required', 'exists:csos,code'],
            'branch_id' => 'required',
            'cso_phone' => 'required',
            'date' => 'required',
            'time' => 'required',
            'cso2_id' => ['exists:csos,code']
        ], $messages);

        if ($validator->fails()){
            $data = ['result' => 0,
                     'data' => $validator->errors()
                    ];
            return response()->json($data, 401);
        }
        else{
            $data = $request->all();
            $data['cso_id'] = Cso::where('code', $data['cso_id'])->first()['id'];
            if($request->has('cso2_id')){
                $data['cso2_id'] = Cso::where('code', $data['cso2_id'])->first()['id'];            
            }
            $data['appointment'] = $data['date']." ".$data['time'];
            $homeservice = HomeService::find($data['id']);
            $homeservice->fill($data)->save();

            $data = ['result' => 1,
                     'data' => $homeservice
                    ];
            return response()->json($data, 200);
        }
    }

    public function deleteApi(Request $request)
    {
        $messages = array(
                'id.required' => 'There\'s an error with the data.',
                'id.exists' => 'There\'s an error with the data.'
            );

        $validator = \Validator::make($request->all(), [
            'id' => ['required', 'exists:home_services,id,active,1']
        ], $messages);

        if ($validator->fails()){
            $data = ['result' => 0,
                     'data' => $validator->errors()
                    ];
            return response()->json($data, 401);
        }
        else{
            $homeservice = HomeService::find($request->id);
            $homeservice->active = false;
            $homeservice->save();

            $data = ['result' => 1,
                     'data' => $homeservice
                    ];
            return response()->json($data, 200);
        }
    }

    public function listApi(Request $request)
    {
        $messages = array(
                'date.required' => 'There\'s an error with the data.',
                'user_id.required' => 'There\'s an error with the data.'
            );

        $validator = \Validator::make($request->all(), [
            'date' => 'required',
            'user_id' => 'required',
        ], $messages);

        if ($validator->fails()){
            $data = ['result' => 0,
                     'data' => $validator->errors()
                    ];
            return response()->json($data, 401);
        }
        else{
            $data = $request->all();
            $tgl=date_create($data['date']);
            $userSlug = User::find($data['user_id'])->roles[0];

            //khususu head-manager, head-admin, admin
            $homeServices = HomeService::WhereMonth('home_services.appointment', '=', date_format($tgl, "n"))
                            ->where('home_services.active', true);

            //khusus akun CSO
            if($userSlug == 'cso'){
                $homeServices = HomeService::whereBetween('appointment', array($awalBulan, $akhirBulan))->where('cso_id', Auth::user()->cso['id'])->where('active', true);
            }
            $homeServices = $homeServices->where('home_services.branch_id', 1);

            //LAST Strutured Eloquent for Homeservices
            $homeServices = $homeServices->leftjoin('branches', 'home_services.branch_id', '=', 'branches.id')
                            ->leftjoin('csos', 'home_services.cso_id', '=', 'csos.id')
                            ->select('home_services.id', 'home_services.appointment', 'home_services.name as custommer_name', 'home_services.phone as custommer_phone', 'branches.code as branch_code', 'csos.code as cso_code', 'csos.name as cso_name')
                            ->orderBy('home_services.appointment', 'ASC')->get();

            $data = ['result' => count($homeServices),
                     'data' => $homeServices
                    ];
            return response()->json($data, 200);
        }        
    }
    

    public function viewApi(Request $request, $id)
    {
        //khususu head-manager, head-admin, admin
        $homeServices = HomeService::where('home_services.active', true);

        
        $homeServices = $homeServices->where('home_services.branch_id', 1);

        //LAST Strutured Eloquent for Homeservices
        $homeServices = $homeServices->leftjoin('branches', 'home_services.branch_id', '=', 'branches.id')
                        ->leftjoin('csos', 'home_services.cso_id', '=', 'csos.id')
                        ->select('home_services.id', 'home_services.appointment', 'home_services.name as custommer_name', 'home_services.phone as custommer_phone', 'branches.code as branch_code', 'csos.code as cso_code', 'csos.name as cso_name')
                        ->orderBy('home_services.appointment', 'ASC')->get();
        $homeServices = $homeServices->find($id);
        return response()->json($homeServices, 200);
           
    }
}
