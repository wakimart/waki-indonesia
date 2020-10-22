<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\HomeService;
use App\Branch;
use App\Cso;
use App\User;
use App\CategoryProduct;
use App\Utils;
use App\HistoryUpdate;
use Carbon\Carbon;
use App\Exports\HomeServicesExport;
use App\Exports\HomeServicesExportByDate;
use Maatwebsite\Excel\Facades\Excel;
use Google_Service_Calendar_Event;
use DB;
use Google_Client;
use Google_Service_Calendar;
use App\Http\Controllers\gCalendarController;

class HomeServiceController extends Controller
{
    public function index()
    {
        $branches = Branch::all();
        $categoryProducts = CategoryProduct::all();
        return view('homeservice', compact('branches', 'categoryProducts'));
    }

    public function indexAdmin()
    {
    	$branches = Branch::all();
        return view('admin.add_home_service', compact('branches'));
    }
    
    public function store(Request $request){
        $homeServiceRawData = HomeService::where([['phone', $request->phone],['active', true]]);
        $homeServiceDataEmpat = $homeServiceRawData->where('type_homeservices', 'Upgrade Member')->get();
        if($homeServiceDataEmpat != null ){
            if(count($homeServiceDataEmpat)>0){
                return redirect()->back()->with("errors","Upgrade Member Hanya Sekali Saja Untuk Nomer Yang Sama");
            }
        }
        
        $homeServiceDataSatu = $homeServiceRawData->where('appointment', '>', DB::raw('NOW()'))->get();
        
        for($i = 0; $i<count($homeServiceDataSatu); $i++){
            $dateTime = explode(' ', $homeServiceDataSatu[$i]->appointment); 
            if($dateTime[0] == $request->date){
                if($dateTime[1] == $request->time){
                    return redirect()->back()->with("errors","Appointment dengan nomer ini sudah ada!!");        
                }
            }
        }

        $homeServiceDataTiga = $homeServiceRawData->where('type_homeservices', 'Home service')->orderBy('created_at', 'desc')->first();
        
        if($homeServiceDataTiga != null){
            if($homeServiceDataTiga->appointment < $request->get('date')." ".$request->get('time')){ //date("Y-m-d h:i:s",strtotime('last week'));
                return redirect()->back()->with("errors","Nomer Telpon Tersebut Telah Di Gunakan Dalam Home Service Dengan Type Home service ");
            }
        }

        $homeServiceDataDua = $homeServiceRawData->orWhere([['type_homeservices', 'Home Eksklusif Therapy'], ['type_homeservices', 'Home Family Family']])->orWhere('type_homeservices', 'Home Family Therapy')->get();
        $counterExecutive= 0;
        $counterFamily=0;
        for($i = 0; $i<count($homeServiceDataDua); $i++){
            if($homeServiceDataDua->type_homeservices == "Home Eksklusif Therapy"){
                $counterExecutive ++;
            }
            if($homeServiceDataDua->type_homeservices == "Home Family Therapy"){
                $counterFamily ++;
            }
        }

        if($counterExecutive> 3 || $counterFamily>3 ){
            return redirect()->back()->with("errors","Home Service dengan Tipe 'Home Family Therapy' atau 'Home Executive Therapy' Hanya Bisa Di Gunakan 3 Kali Dalam 2 Minggu Dengan Nomor Inputan yang Sama");
        }

        // if(count($homeServiceData) > 0 ){
        //     return redirect()->back()->with("errors","Appointment dengan nomer ini sudah ada!!");
        // }
        // if(count(HomeService::where([['phone', $request->phone],['active', false]])->get()) > 0){
        //     return redirect()->back()->with("errors","Apakah Appointment ini reschadule? Jika iya lakukan edit pada menu edit."); 
        // }
        $data = $request->all();
        $data['code'] = "HS/".strtotime(date("Y-m-d H:i:s"))."/".substr($data['phone'], -4);

        $getAppointment = $request->get('date')." ".$request->get('time');
        $getIdCso = Cso::where('code', $data['cso_id'])->first()['id'];
        $getHomeServices = HomeService::where([
            ['cso_id', '=', $getIdCso],
            ['appointment', '=', $getAppointment]
        ])->get();
        //dd(count($getHomeServices));
        
        if (count($getHomeServices) > 0) {
            //return response()->json(['errors' => "An appointment has been already scheduled."]);
            return redirect()->back()->with("errors","An appointment has been already scheduled.");
        }

        $data['cso_id'] = Cso::where('code', $data['cso_id'])->first()['id'];
        if($request->has('cso2_id')){
            $data['cso2_id'] = Cso::where('code', $data['cso2_id'])->first()['id'];            
        }
        $data['appointment'] = $data['date']." ".$data['time'];
        $order = HomeService::create($data);
        //return response()->json(['berhasil' => $data]);
        return redirect()->route('homeServices_success', ['code'=>$order['code']]);
    }

    public function successRegister(Request $request){
        $homeService = HomeService::where('code', $request['code'])->first();
        $categoryProducts = CategoryProduct::all();
        return view('homeservicesuccess', compact('homeService', $categoryProducts));
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
            // $homeServices = HomeService::whereBetween('appointment', array($awalBulan, $akhirBulan))->where('cso_id', Auth::user()->cso['id'])->where('active', true);
            $homeService = $homeService->where('home_services.cso_id', Auth::user()->cso['id']);
        }

        //khusus akun branch dan area-manager
        if(Auth::user()->roles[0]['slug'] == 'branch' || Auth::user()->roles[0]['slug'] == 'area-manager'){
            foreach (Auth::user()->listBranches() as $value) {
                array_push($arrbranches, $value['id']);
            }
            // $homeServices = HomeService::whereBetween('appointment', array($awalBulan, $akhirBulan))
            //          ->whereIn('branch_id', $arrbranches)->where('active', true);
            $homeService = $homeService->whereIn('home_services.branch_id', $arrbranches);
        }

        //kalau ada filter
        if($request->has('filter_city')){
            $homeServices = $homeServices->where('home_services.city', 'like', '%'.$request->filter_city.'%');
        }
        if($request->has('filter_search')){
            $homeServices = $homeServices->where('home_services.name', 'like', '%'.$request->filter_search.'%')
            ->orWhere('home_services.phone', 'like', '%'.$request->filter_search.'%')
            ->orWhere('home_services.code', 'like', '%'.$request->filter_search.'%');
        }
        if($request->has('filter_branch') && Auth::user()->roles[0]['slug'] != 'branch'){
            $homeServices = $homeServices->where('home_services.branch_id', $request->filter_branch);
        }
        if($request->has('filter_cso') && Auth::user()->roles[0]['slug'] != 'cso'){
            $homeServices = $homeServices->where('home_services.cso_id', $request->filter_cso);
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


    protected $gCalendarController;
    public function __construct(gCalendarController $gCalendarController)
    {
        $this->gCalendarController = $gCalendarController;
    }

    public function admin_addHomeService(Request $request){
        if(count(HomeService::where([['phone', $request->phone],['appointment', '>', DB::raw('NOW()')],['active', true]])->get()) != 0){
            return response()->json(['validator' => 'Phone Number Already Exists'], 401); 
        }
        if(count(HomeService::where([['phone', $request->phone],['active', false]])->get()) != 0){
            return response()->json(['active' => 'Wanna Reschedule?'], 401); 
        }
        $data = $request->all();
        $data['code'] = "HS/".strtotime(date("Y-m-d H:i:s"))."/".substr($data['phone'], -4);

        $getAppointment = $request->get('data')." ".$request->get('time');
        $getIdCso = Cso::where('code', $data['cso_id'])->first()['id'];
        $getHomeServices = HomeService::where([
            ['cso_id', '=', $getIdCso],
            ['appointment', '=', $getAppointment]
        ])->get();
        
        
        if (count($getHomeServices) > 0) {
            //return response()->json(['errors' => "An appointment has been already scheduled."]);
            return redirect()->back()->with("errors","An appointment has been already scheduled.");
        }

        $cso = Cso::where('code', $data['cso_id']);
        $cso2 = Cso::where('code', $data['cso2_id']);
        $data['cso_id'] = $cso->first()['id'];
        $data['cso2_id'] = $cso2->first()['id'];
        $data['appointment'] = $data['date']." ".$data['time'];

        $startDateTime = $data['date']."T".$data['time'].":00";
        $time = strtotime($data['time']) + 60*60 * 2;
        $endDateTime = $data['date']."T".date('H:i', $time).":00";
        DB::beginTransaction();
        try{
            $order = HomeService::create($data);
            $event = array(
                'summary' => 'Acara Home Service',
                'location' => $data['address'],
                'description' => 'A chance to hear more about Google\'s developer products.',
                'start' => array(
                    'dateTime' => $startDateTime,
                    'timeZone' => 'Asia/Jakarta',
                ),
                'end' => array(
                    'dateTime' => $endDateTime,
                    'timeZone' => 'America/Los_Angeles',
                ),
                'recurrence' => array(
                    'RRULE:FREQ=DAILY;COUNT=2'
                ),
                'attendees' => array(
                    array('email' => $cso->first()['email']),
                    // array('email' => $cso2->first()['email']),
                ),
                'reminders' => array(
                    'useDefault' => FALSE,
                    'overrides' => array(
                    array('method' => 'email', 'minutes' => 24 * 60),
                    array('method' => 'popup', 'minutes' => 10),
                    ),
                ),
            );

            // $event = $this->gCalendarController->store($event);
            DB::commit();
            return response()->json(['success' => 'Berhasil']);
        } catch (\Exception $ex) {
            DB::rollback();
            return response()->json(['error' => $ex->getMessage()], 500);
        }
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
            DB::beginTransaction();
            try{
                $appointmentBefore = HomeService::find($request->id);
                $data = $request->all();
                $data['code'] = "HS/".strtotime(date("Y-m-d H:i:s"))."/".substr($data['phone'], -4);
                $data['cso_id'] = Cso::where('code', $data['cso_id'])->first()['id'];
                $data['cso2_id'] = Cso::where('code', $data['cso2_id'])->first()['id'];
                $data['appointment'] = $data['date']." ".$data['time'];
                $homeService->fill($data)->save();

                $user = Auth::user();
                $historyUpdate= [];
                $historyUpdate['type_menu'] = "Home Service";
                $historyUpdate['method'] = "Update";
                $historyUpdate['meta'] = ['user'=>$user['id'],'createdAt' => date("Y-m-d h:i:s"), 'dateChange'=> $data, 'appointmentBefore'=>$appointmentBefore->appointment];
                $historyUpdate['user_id'] = $user['id'];
                $historyUpdate['menu_id'] = $homeService->id;

                $createData = HistoryUpdate::create($historyUpdate);
                DB::commit();
            }catch (\Exception $ex) {
                DB::rollback();
            }
        }
        
        $req = new Request();
        return $this->admin_ListHomeService($req);
    }

    public function export_to_xls(Request $request)
    {
        $date = null;
        $branch = null;
        $cso = null;
        $search = null;
        if($request->has('date') && $request->date != "undefined"){
            $date = $request->date;
        }
        if($request->has('filter_city') && $request->filter_city != "undefined"){
            $city = $request->filter_city;
        }
        if($request->has('filter_search') && $request->filter_search != "undefined"){
            $search = $request->filter_search;
        }
        if($request->has('filter_branch') && $request->filter_branch != "undefined"){
            $branch = $request->filter_branch;
        }
        if($request->has('filter_cso') && $request->filter_cso != "undefined"){
            $cso = $request->filter_cso;
        }
        // dd(new HomeServicesExportByDate($date, $city, $branch, $cso, null));
        return Excel::download(new HomeServicesExport($city, $branch, $cso, $search, null), 'Home Service.xlsx');
    }

    public function export_to_xls_byDate(Request $request)
    {
        $city = null;
        $branch = null;
        $cso = null;
        $search = null;
        $startDate = null;
        $endDate = null;

        if($request->has('filter_city')  && $request->filter_city != "undefined"){
            $city = $request->filter_city;
        }
        if($request->has('filter_branch')  && $request->filter_branch != "undefined"){
            $branch = $request->filter_branch;
        }
        if($request->has('filter_cso')  && $request->filter_cso != "undefined"){
            $cso = $request->filter_cso;
        }
        if($request->has('filter_search') && $request->filter_search != "undefined"){
            $search = $request->filter_search;
        }
        if($request->has('filter_startDate')&&$request->has('filter_endDate')){
            $startDate = $request->filter_startDate;
            $endDate = $request->filter_endDate;
            $endDate = new \DateTime($endDate);
            $endDate = $endDate->modify('+1 day')->format('Y-m-d'); 
        }
        return Excel::download(new HomeServicesExportByDate($city, $branch, $cso, $search, array($startDate, $endDate)), 'Home Service By Date.xlsx');
    }
    public function delete(Request $request) {
        $homeService = HomeService::find($request->id);
        $homeService->active = false;
        $order->save();
        return view('admin.list_order');
    }

    //KHUSUS API APPS
    public function addApi(Request $request)
    {
        if(count(HomeService::where([['phone', $request->phone],['appointment', '>', DB::raw('NOW()')],['active', true]])->get()) != 0){
            return response()->json(['validator' => 'Appointment Has Been Made by New Phone Number'], 401); 
        }
        if(count(HomeService::where([['phone', $request->phone],['active', false]])->get()) != 0){
            return response()->json(['active' => 'Wanna Reschedule?'], 401); 
        }
        $messages = array(
                'cso_id.required' => 'The CSO Code field is required.',
                'cso_id.exists' => 'Wrong CSO Code.',
                'branch_id.required' => 'The Branch must be selected.',
                'branch_id.exists' => 'Please choose the branch.',
                'cso2_id.required' => 'The CSO Code field is required.',
                'cso2_id.exists' => 'Wrong CSO Code.'
            );

        $validator = \Validator::make($request->all(), [
            'type_customer' => 'required',
            'type_homeservices' => 'required',
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
            $tgl=date_create($request->date);
            $userSlug = User::find($data['user_id']);

            //khususu head-manager, head-admin, admin
            $homeServices = HomeService::whereDate('home_services.appointment', '=', $tgl)
                            ->where('home_services.active', true);

            //khusus akun CSO
            if($userSlug->roles[0]['slug'] == 'cso'){
                $homeServices = HomeService::whereDate('home_services.appointment', '=', $tgl)->where('cso_id', Auth::user()->cso['id'])->where('active', true);
            }

            //khusus akun branch dan area-manager
            if($userSlug->roles[0]['slug'] == 'branch' || $userSlug->roles[0]['slug'] == 'area-manager'){
                $arrbranches = [];
                foreach ($userNya->listBranches() as $value) {
                    array_push($arrbranches, $value['id']);
                }
                $homeServices = HomeService::WhereIn('home_services.branch_id', $arrbranches)->where('home_services.active', true);
            }

            //LAST Strutured Eloquent for Homeservices
            $homeServices = $homeServices->leftjoin('branches', 'home_services.branch_id', '=', 'branches.id')
                            ->leftjoin('csos', 'home_services.cso_id', '=', 'csos.id')
                            ->select('home_services.id', 'home_services.appointment', 'home_services.name as custommer_name', 'home_services.phone as custommer_phone', 'branches.code as branch_code', 'csos.code as cso_code', 'csos.name as cso_name', 'branches.color as branch_color');
                            // ->orderBy('home_services.appointment', 'ASC')
            if($request->has('filter_branch')){
                $homeServices = $homeServices->where('home_services.branch_id', $request->filter_branch);
            }
            if($request->has('filter_cso')){
                $homeServices = $homeServices->where('home_services.cso_id', $request->filter_cso);
            }
            if($request->has('filter_city')){
                $homeServices = $homeServices->where('home_services.city', 'like', '%'.$request->filter_city.'%');
            }
            if($request->has('filter_search')){
                $homeServices = $homeServices->where('home_services.name', 'like', '%'.$request->filter_search.'%')
                ->orWhere('home_services.phone', 'like', '%'.$request->filter_search.'%')
                ->orWhere('home_services.code', 'like', '%'.$request->filter_search.'%');
            }
            $homeServices = $homeServices->get();          
            $totalPerDay = [];

            for ($i=1; $i <= 31; $i++) { 
                $tempHari = ($i < 9 ? "0".$i : $i);
                $tempTgl = date_create(date_format($tgl, "Y")."-".date_format($tgl, "m")."-".$tempHari);
                $temp = HomeService::whereDate('appointment', "=" , $tempTgl)
                            ->where('home_services.active', true)
                            ->count();
                array_push($totalPerDay, $temp);
            }

            $data = ['result' => count($homeServices),
                     'data' => $homeServices,
                     'totalPerDay' => $totalPerDay
                    ];
            return response()->json($data, 200);
        }        
    }

    public function listPerTotalDay(){
        $totalPerDay = [];

        for ($i=1; $i <= 31; $i++) { 
            $tempHari = ($i < 9 ? "0".$i : $i);
            $tempTgl = date_create(date_format($tgl, "Y")."-".date_format($tgl, "m")."-".$tempHari);
            $temp = HomeService::whereDate('appointment', "=" , $tempTgl)
                        ->where('home_services.active', true)
                        ->count();
            array_push($totalPerDay, $temp);
        }

        $data = ['result' => 1,
                 'totalPerDay' => $totalPerDay
                ];
            return response()->json($data, 200);
    }

    public function viewApi(Request $request, $id)
    {
        $homeServices = HomeService::where([['home_services.active', true], ['home_services.id', $id]]);

        $homeServices = $homeServices->leftjoin('branches', 'home_services.branch_id', '=', 'branches.id')
                        ->leftjoin('csos', 'home_services.cso_id', '=', 'csos.id')
                        ->select('home_services.id', 'home_services.code as code', 'home_services.appointment', 'home_services.no_member as no_member', 'home_services.name as custommer_name', 'home_services.city as custommer_city', 'home_services.address as custommer_address','home_services.phone as custommer_phone', 'home_services.type_customer as type_customer', 'home_services.type_homeservices as type_homeservices','branches.code as branch_code', 'branches.name as branch_name', 'csos.code as cso_code', 'csos.name as cso_name')->first();

        $homeServices['URL'] = route('homeServices_success')."?code=".$homeServices['code'];
        $data = ['result' => 1,
                 'data' => $homeServices
                ];
        return response()->json($data, 200);
    }
}
