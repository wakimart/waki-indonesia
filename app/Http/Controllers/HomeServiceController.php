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
use App\DeliveryOrder;
use App\Order;
use Carbon\Carbon;
use App\Exports\HomeServicesExport;
use App\Exports\HomeServicesExportByDate;
use Maatwebsite\Excel\Facades\Excel;
use Google_Service_Calendar_Event;
use DB;
use Validator;
use Google_Client;
use Google_Service_Calendar;
use App\Http\Controllers\gCalendarController;
use DateTime;
use RajaOngkir;

class HomeServiceController extends Controller
{
    public function index()
    {
        $branches = Branch::where('active', true)->get();
        $categoryProducts = CategoryProduct::all();
        return view('homeservice', compact('branches', 'categoryProducts'));
    }

    public function indexAdmin()
    {
        $branches = Branch::where('active', true)->get();
        return view('admin.add_home_service', compact('branches'));
    }
    
    public function store(Request $request){
        $validator = \Validator::make($request->all(), [
            'phone' => 'required|string|min:7',
        ]);
        if($validator->fails()){
            $arr_Errors = $validator->errors()->all();
            $arr_Keys = $validator->errors()->keys();
            $arr_Hasil = [];
            for ($i = 0; $i < count($arr_Keys); $i++) {
                $arr_Hasil[$arr_Keys[$i]] = $arr_Errors[$i];
            }
            return response()->json(['errors' => $arr_Hasil]);
        }else {
            $inputAppointment = $request->date." ".$request->time;
            if($request->type_homeservices == "Home Eksklusif Therapy" || $request->type_homeservices == "Home Family Therapy"){
                $homeserviceInactive = HomeService::where([['phone', $request->phone],['active', true]])->where('type_homeservices', 'Home Eksklusif Therapy')->orWhere('type_homeservices', 'Home Family Therapy')->get();
                for ($i = 0; $i< count($homeserviceInactive); $i++){
                    if(($homeserviceInactive[$i]->type_homeservices == "Home Eksklusif Therapy" && date("Y-m-d h:i:s",strtotime('+14 days', strtotime($homeserviceInactive[$i]->appointment))) < date("Y-m-d h:i:s"))||($homeserviceInactive[$i]->type_homeservices == "Home Family Therapy" && date("Y-m-d h:i:s",strtotime('+14 days', strtotime($homeserviceInactive[$i]->appointment))) < date("Y-m-d h:i:s"))) {
                        $homeService = HomeService::find($homeserviceInactive[$i]->id);
                        $homeService->active = false;
                        $homeService->save();
                    }
                }
            }
            $counterExecutive= 0;
            $counterFamily=0;
            $homeServiceRawData = HomeService::where([['phone', $request->phone],['active', true]])->get();
            if ($homeServiceRawData != null && count($homeServiceRawData) > 0){
                for ($i=0; $i<count($homeServiceRawData);$i++){
                    if ($homeServiceRawData[$i]->type_homeservices == "Upgrade Member" && $request->type_homeservices == "Upgrade Member"){
                        return redirect()->back()->with("errors","Upgrade Member Hanya Sekali Saja Untuk Nomer Yang Sama");    
                    }
                    if ($homeServiceRawData[$i]->appointment > $inputAppointment){
                        $dateTime = explode(' ', $homeServiceRawData[$i]->appointment); 
                        if($dateTime[0] == $request->date){
                            if($dateTime[1] == $request->time){
                                return redirect()->back()->with("errors","Appointment dengan nomer ini sudah ada!!");                        }
                        }        
                    }
                    if($homeServiceRawData[$i]->type_homeservices == "Home Eksklusif Therapy"){
                        $counterExecutive ++;
                    }
                    if($homeServiceRawData[$i]->type_homeservices == "Home Family Therapy"){
                        $counterFamily ++;
                    }
                }    
            }

            $homeServiceDataTiga = HomeService::where([['phone', $request->phone],['active', true],['type_homeservices', 'Home service']])->orderBy('appointment', 'desc')->first();
            
            if($homeServiceDataTiga != null && $request->type_homeservices == "Home service"){
                if($homeServiceDataTiga->appointment > date($inputAppointment,strtotime('last week'))){ //date("Y-m-d h:i:s",strtotime('last week'));
                    return redirect()->back()->with("errors","Nomer Telpon Tersebut Telah Di Gunakan Dalam Home Service Dengan Type Home service ");
                }
            }
            if($counterExecutive >=3 || $counterFamily >=3 ){
                return redirect()->back()->with("errors","Home Service dengan Tipe 'Home Family Therapy' atau 'Home Executive Therapy' Hanya Bisa Masing-Masing di Gunakan 3 Kali Dalam 2 Minggu Dengan Nomor Inputan yang Sama");
            }

            $data = $request->all();
            $data['code'] = "HS/".strtotime(date("Y-m-d H:i:s"))."/".substr($data['phone'], -4);

            $getAppointment = $request->get('date')." ".$request->get('time');
            $getIdCso = Cso::where('code', $data['cso_id'])->first()['id'];
            $getHomeServices = HomeService::where([
                ['cso_id', '=', $getIdCso],
                ['appointment', '=', $getAppointment]
            ])->get();
            
            if (count($getHomeServices) > 0) {
                //return response()->json(['errors' => "An appointment has been already scheduled."]);
                return redirect()->back()->with("errors","An appointment has been already scheduled.");
            }

            $data['cso_id'] = Cso::where('code', $data['cso_id'])->first()['id'];
            if($request->has('cso2_id')){
                $data['cso2_id'] = Cso::where('code', $data['cso2_id'])->first()['id'];            
            }
            $data['appointment'] = $inputAppointment;
            $order = HomeService::create($data);
            $dt = new DateTime($data['appointment']);

            $phone = preg_replace('/[^A-Za-z0-9\-]/', '', $order['phone']);
            if($phone[0]==0 || $phone[0]=="0"){
            $phone =  substr($phone, 1);
            }
            $phone = "62".$phone;
            Utils::sendSms($phone, "Terima kasih telah mendaftar layanan 'Home Service' kami. Tim kami akan datang pada tanggal ".$dt->format('j/m/Y')." pukul ".$dt->format('H:i')." WIB. Info lebih lanjut, hubungi ".$data['cso_phone']); 
            
            return redirect()->route('homeServices_success', ['code'=>$order['code']]);
        }
        
    }

    public function successRegister(Request $request){
        $homeService = HomeService::where('code', $request['code'])->first();
        $categoryProducts = CategoryProduct::all();
        return view('homeservicesuccess', compact('homeService', $categoryProducts));
    }

    public function admin_ListHomeService(Request $request){
        $branches = Branch::Where('active', true)->get();
        $csos = Cso::where('active', true)->get();
        $awalBulan = Carbon::now()->startOfMonth()->subMonth(4);
        $akhirBulan = Carbon::now()->startOfMonth()->addMonth(5);//5
        $arrbranches = [];

        //khususu head-manager, head-admin, admin
        $homeServices = HomeService::whereBetween('appointment', array($awalBulan, $akhirBulan))
                     ->where('active', true);

        //khusus akun CSO
        if(Auth::user()->roles[0]['slug'] == 'cso'){
            // $homeServices = HomeService::whereBetween('appointment', array($awalBulan, $akhirBulan))->where('cso_id', Auth::user()->cso['id'])->where('active', true);
            $homeServices = $homeServices->where('home_services.cso_id', Auth::user()->cso['id']);
        }

        //khusus akun branch dan area-manager
        if(Auth::user()->roles[0]['slug'] == 'branch' || Auth::user()->roles[0]['slug'] == 'area-manager'){
            foreach (Auth::user()->listBranches() as $value) {
                array_push($arrbranches, $value['id']);
            }
            // $homeServices = HomeService::whereBetween('appointment', array($awalBulan, $akhirBulan))
            //          ->whereIn('branch_id', $arrbranches)->where('active', true);
            $homeServices = $homeServices->whereIn('home_services.branch_id', $arrbranches);
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
            $cso_id = Cso::where('code', $request->filter_cso)->get();
            $homeServices = $homeServices->where('home_services.cso_id', $cso_id[0]['id']);
        }
        
        
        $homeServices = $homeServices->get();
        
        return view('admin.list_homeservice', compact('homeServices', 'awalBulan', 'akhirBulan', 'branches', 'csos'));
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
        $validator = \Validator::make($request->all(), [
            'phone' => 'required|string|min:7',
        ]);
        if($validator->fails()){
            $arr_Errors = $validator->errors()->all();
            $arr_Keys = $validator->errors()->keys();
            $arr_Hasil = [];
            for ($i = 0; $i < count($arr_Keys); $i++) {
                $arr_Hasil[$arr_Keys[$i]] = $arr_Errors[$i];
            }
            return response()->json(['errors' => $arr_Hasil]);
        }else {
            $inputAppointment = $request->date." ".$request->time;
            if($request->type_homeservices == "Home Eksklusif Therapy" || $request->type_homeservices == "Home Family Therapy"){
                $homeserviceInactive = HomeService::where([['phone', $request->phone],['active', true],['type_homeservices', 'Home Eksklusif Therapy']])->orWhere([['phone', $request->phone],['active', true],['type_homeservices', 'Home Family Therapy']])->get();
                for ($i = 0; $i< count($homeserviceInactive); $i++){
                    if(date("Y-m-d",strtotime('+14 days', strtotime($homeserviceInactive[$i]->appointment))) < date("Y-m-d")) {
                        $homeService = HomeService::find($homeserviceInactive[$i]->id);
                        $homeService->active = false;
                        $homeService->save();
                    }
                }
            }

            $counterExecutive= 0;
            $counterFamily=0;
            $homeServiceRawData = HomeService::where([['phone', $request->phone],['active', true]])->get();
            if ($homeServiceRawData != null && count($homeServiceRawData) > 0){
                for ($i=0; $i<count($homeServiceRawData);$i++){
                    if ($homeServiceRawData[$i]->type_homeservices == "Upgrade Member" && $request->type_homeservices == "Upgrade Member"){
                        return response()->json(['errors' => "Upgrade Member Hanya Sekali Saja Untuk Nomer Yang Sama"]);    
                    }

                    if (date("Y-m-d", strtotime($homeServiceRawData[$i]->appointment)) == date("Y-m-d", strtotime($inputAppointment))){
                        return response()->json(['errors' => ['type_homeservices' => "Appointment dengan nomer ini sudah ada!!"]]);     
                    }

                    if($homeServiceRawData[$i]->type_homeservices == "Home Eksklusif Therapy"){
                        $counterExecutive ++;
                    }
                    if($homeServiceRawData[$i]->type_homeservices == "Home Family Therapy"){
                        $counterFamily ++;
                    }
                }
            }

            $homeServiceDataTiga = HomeService::where([['phone', $request->phone],['active', true],['type_homeservices', 'Home service']])->orderBy('appointment', 'desc')->first();
            if($homeServiceDataTiga != null && $request->type_homeservices == "Home service"){
                if(date("Y-m-d", strtotime($homeServiceDataTiga->appointment)) >= date("Y-m-d", strtotime($inputAppointment.' -7 days'))){
                    return response()->json(['errors' => ['type_homeservices' => "Nomer Telpon Tersebut Telah Di Gunakan Dalam Home Service Dengan Type Home service "]]);
                }
            }

            if($counterExecutive >=3 && $request->type_homeservices == "Home Eksklusif Therapy"){
                return response()->json(['errors' =>['type_homeservices' =>  "Home Service dengan Tipe 'Home Family Therapy' atau 'Home Executive Therapy' Hanya Bisa Masing-Masing di Gunakan 3 Kali Dalam 2 Minggu Dengan Nomor Inputan yang Sama"]]);
            }
            else if($counterFamily >=3 && $request->type_homeservices == "Home Family Therapy"){
                return response()->json(['errors' =>['type_homeservices' =>  "Home Service dengan Tipe 'Home Family Therapy' atau 'Home Executive Therapy' Hanya Bisa Masing-Masing di Gunakan 3 Kali Dalam 2 Minggu Dengan Nomor Inputan yang Sama"]]);
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
                return response()->json(['errors' => ['type_homeservices' => "An appointment has been already scheduled."]]);
            }

            $cso = Cso::where('code', $data['cso_id']);
            $cso2 = Cso::where('code', $data['cso2_id']);
            $data['cso_id'] = $cso->first()['id'];
            $data['cso2_id'] = $cso2->first()['id'];
            $data['appointment'] = $inputAppointment;

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

                //dd($historyUpdate);
                $createData = HistoryUpdate::create($historyUpdate);
                DB::commit();
            } catch (\Exception $ex) {
                DB::rollback();
            }
            
        }
        
        $req = new Request();
        return $this->admin_ListHomeService($req);
        
        // $homeService = HomeService::find($request->id);
        // if($request->has('cancel')){
        //     $homeService->active = false;
        //     $homeService->save();
        // }
        // else if($request->has('cash')){
        //     if($request->cash == 0){
        //         $homeService->cash = false;
        //     }
        //     else{
        //         $homeService->cash = true;
        //     }
        //     $homeService->cash_description = $request['cash_description'];
        //     $homeService->save();
        // }
        // else{
        //     DB::beginTransaction();
        //     try{
        //         $data = $request->all();
        //         $data['code'] = "HS/".strtotime(date("Y-m-d H:i:s"))."/".substr($data['phone'], -4);
        //         $data['cso_id'] = Cso::where('code', $data['cso_id'])->first()['id'];
        //         $data['cso2_id'] = Cso::where('code', $data['cso2_id'])->first()['id'];
        //         $data['appointment'] = $data['date']." ".$data['time'];
        //         $homeService->fill($data)->save();

        //         $user = Auth::user();
        //         $historyUpdate= [];
        //         $historyUpdate['type_menu'] = "Home Service";
        //         $historyUpdate['method'] = "Update";
        //         $historyUpdate['meta'] = ['user'=>$user['id'],'createdAt' => date("Y-m-d h:i:s"), 'dateChange'=> $data];
        //         $historyUpdate['user_id'] = $user['id'];

        //         //dd($historyUpdate);
        //         $createData = HistoryUpdate::create($historyUpdate);
        //         DB::commit();
        //     }catch (\Exception $ex) {
        //         DB::rollback();
        //     }
        // }
        
        // $req = new Request();
        // return $this->admin_ListHomeService($req);
    }

    public function export_to_xls(Request $request)
    {
        $city = null;
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
            $csos = Cso::where('code', $request->filter_cso)->get();
            $cso = $csos[0]['id'];
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
        $inputDate = null; 

        if($request->has('filter_city')  && $request->filter_city != "undefined"){
            $city = $request->filter_city;
        }
        if($request->has('filter_branch')  && $request->filter_branch != "undefined"){
            $branch = $request->filter_branch;
        }
        if($request->has('filter_cso')  && $request->filter_cso != "undefined"){
            $csos = Cso::where('code', $request->filter_cso)->get();
            $cso = $csos[0]['id'];
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
        if($request->has('inputDate')){
            $inputDate = $request->inputDate; 
        }
        return Excel::download(new HomeServicesExportByDate($city, $branch, $cso, $search, array($startDate, $endDate), $inputDate), 'Home Service By Date.xlsx');
    }
    public function delete(Request $request) {
        $homeService = HomeService::find($request->id);
        $homeService->active = false;
        $homeService->save();
        return view('admin.list_order');
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
            'type_customer' => 'required',
            'type_homeservices' => 'required',
            'name' => 'required',
            'address' => 'required',
            'phone' => 'required|min:7',
            'city' => 'required',
            'cso_id' => ['required', 'exists:csos,code'],
            'branch_id' => ['required', 'exists:branches,id'],
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
            //Validation 2
            $inputAppointment = $request->date." ".$request->time;
            if($request->type_homeservices == "Home Eksklusif Therapy" || $request->type_homeservices == "Home Family Therapy"){
                $homeserviceInactive = HomeService::where([['phone', $request->phone],['active', true],['type_homeservices', 'Home Eksklusif Therapy']])->orWhere([['phone', $request->phone],['active', true],['type_homeservices', 'Home Family Therapy']])->get();
                for ($i = 0; $i< count($homeserviceInactive); $i++){
                    if(date("Y-m-d",strtotime('+14 days', strtotime($homeserviceInactive[$i]->appointment))) < date("Y-m-d")) {
                        $homeService = HomeService::find($homeserviceInactive[$i]->id);
                        $homeService->active = false;
                        $homeService->save();
                    }
                }
            }

            $counterExecutive= 0;
            $counterFamily=0;
            $homeServiceRawData = HomeService::where([['phone', $request->phone],['active', true]])->get();
            if ($homeServiceRawData != null && count($homeServiceRawData) > 0){
                for ($i=0; $i<count($homeServiceRawData);$i++){
                    if ($homeServiceRawData[$i]->type_homeservices == "Upgrade Member" && $request->type_homeservices == "Upgrade Member"){
                        $data = ['result' => 0,
                                 'data' => ['type_homeservices' => ["Upgrade Member Hanya Sekali Saja Untuk Nomer Yang Sama"]]
                                ];
                        return response()->json($data, 401);
                    }

                    if (date("Y-m-d", strtotime($homeServiceRawData[$i]->appointment)) == date("Y-m-d", strtotime($inputAppointment))){
                        $data = ['result' => 0,
                                 'data' => ['type_homeservices' => ["Appointment dengan nomer ini sudah ada!!"]]
                                ];
                        return response()->json($data, 401);
                    }

                    if($homeServiceRawData[$i]->type_homeservices == "Home Eksklusif Therapy"){
                        $counterExecutive ++;
                    }
                    if($homeServiceRawData[$i]->type_homeservices == "Home Family Therapy"){
                        $counterFamily ++;
                    }
                }
            }

            $homeServiceDataTiga = HomeService::where([['phone', $request->phone],['active', true],['type_homeservices', 'Home service']])->orderBy('appointment', 'desc')->first();
            if($homeServiceDataTiga != null && $request->type_homeservices == "Home service"){
                if(date("Y-m-d", strtotime($homeServiceDataTiga->appointment)) >= date("Y-m-d", strtotime($inputAppointment.' -7 days'))){
                    $data = ['result' => 0,
                             'data' => ['type_homeservices' => ["HomeService sudah dilakukan dalam jarak 1 minggu"]]
                            ];
                    return response()->json($data, 401);
                }
            }

            if($counterExecutive >=3 && $request->type_homeservices == "Home Eksklusif Therapy"){
                $data = ['result' => 0,
                         'data' => ['type_homeservices' => ["Hanya Bisa 3 Kali Dalam 2 Minggu Dengan Nomor Sama !"]]
                        ];
                return response()->json($data, 401);
            }
            else if($counterFamily >=3 && $request->type_homeservices == "Home Family Therapy"){
                $data = ['result' => 0,
                         'data' => ['type_homeservices' => ["Hanya Bisa 3 Kali Dalam 2 Minggu Dengan Nomor Sama !"]]
                        ];
                return response()->json($data, 401);
            }

            $data = $request->all();
            $data['code'] = "HS/".strtotime(date("Y-m-d H:i:s"))."/".substr($data['phone'], -4);
            $data['cso_id'] = Cso::where('code', $data['cso_id'])->first()['id'];
            $data['cso_phone'] = Cso::where('id', $data['cso_id'])->first()['phone'];
            if($request->has('cso2_id')){
                $data['cso2_id'] = Cso::where('code', $data['cso2_id'])->first()['id'];            
            }
            $data['appointment'] = $data['date']." ".$data['time'];
            $homeservice = HomeService::create($data);

            try{
                $dt = new DateTime($data['appointment']);
                $phone = preg_replace('/[^A-Za-z0-9\-]/', '', $data['phone']);
                if($phone[0]==0 || $phone[0]=="0"){
                $phone =  substr($phone, 1);
                }
                $phone = "62".$phone;
                Utils::sendSms($phone, "Terima kasih telah mendaftar layanan 'Home Service' kami. Tim kami akan datang pada tanggal ".$dt->format('j/m/Y')." pukul ".$dt->format('H:i')." WIB. Info lebih lanjut, hubungi ".$data['cso_phone']);
            }
            catch(\Exception $ex){

            }

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
            'phone' => 'required|min:7',
            'city' => 'required',
            'cso_id' => ['required', 'exists:csos,code'],
            'branch_id' => 'required',
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
            $data['cso_phone'] = Cso::where('id', $data['cso_id'])->first()['phone'];
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

    public function reportHomeService(Request $request)
    {
        $messages = array(
            'id.required'=> 'The ID field is required.',
            'cash.required' => 'The Cash field is required.',
            'cash_description.required' => 'The Cash Description field is required.'
        );
        $validator = \Validator::make($request->all(), [
            'id' => 'required',
            'cash' => 'required',
            'cash_description' => 'required',
        ], $messages);

        if($validator->fails()){
            $data = ['result' => 0,
                     'data' => $validator->errors()
                    ];
            return response()->json($data, 401);
        }else {
            $data = $request->all();
            $homeservice = Homeservice::find($data['id']);
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
                $homeServices = HomeService::whereDate('home_services.appointment', '=', $tgl)->where('home_services.cso_id', $userSlug->cso['id'])->where('home_services.active', true);
            }
            
            //khusus akun Branch
            else if($userSlug->roles[0]['slug'] == 'branch' || $userSlug->roles[0]['slug'] == 'area-manager'){
                $homeServices = HomeService::whereDate('home_services.appointment', '=', $tgl)->whereIn('home_services.branch_id', json_decode($userSlug['branches_id']))->where('home_services.active', true);
            }
            
            //khusus filter
            if(isset($data['filter']['filter_city'])){
                $homeServices = $homeServices->Where('home_services.city', $data['filter']['filter_city']);
            }
            if(isset($data['filter']['filter_branch'])){
                $homeServices = $homeServices->Where('home_services.branch_id', $data['filter']['filter_branch']);
            }
            if(isset($data['filter']['filter_cso'])){
                $homeServices = $homeServices->Where('home_services.cso_id', $data['filter']['filter_cso']);
            }

            //LAST Strutured Eloquent for Homeservices
            $homeServices = $homeServices->leftjoin('branches', 'home_services.branch_id', '=', 'branches.id')
                            ->leftjoin('csos', 'home_services.cso_id', '=', 'csos.id')
                            ->select('home_services.id', 'home_services.appointment', 'home_services.name as custommer_name', 'home_services.phone as custommer_phone', 'branches.code as branch_code', 'csos.code as cso_code', 'csos.name as cso_name', 'branches.color as branch_color', 'home_services.created_at', 'home_services.updated_at')->get();
                            // ->orderBy('home_services.appointment', 'ASC')
                            
            foreach($homeServices as $HS){
                if($HS->historyUpdate() != null){
                    $HS['appointmentBefore'] = $HS->historyUpdate()['meta']['appointmentBefore'];
                }
            }

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
                        ->select('home_services.id', 'home_services.code as code', 'home_services.appointment', 'home_services.no_member as no_member', 'home_services.name as custommer_name', 'home_services.city as custommer_city', 'home_services.address as custommer_address','home_services.phone as custommer_phone', 'home_services.type_customer as type_customer', 'home_services.type_homeservices as type_homeservices', 'branches.id as branch_id', 'branches.code as branch_code', 'branches.name as branch_name', 'csos.code as cso_code', 'csos.name as cso_name', 'csos.phone as cso_phone')->first();

        $homeServices['province_id'] = RajaOngkir::FetchProvinceByCity($homeServices['custommer_city'])['province_id'];
        $homeServices['URL'] = route('homeServices_success')."?code=".$homeServices['code'];
        $data = ['result' => 1,
                 'data' => $homeServices
                ];
        return response()->json($data, 200);
    }

    public function listAllTypeHS(){
        $type_homeservices = ["Home Service", "Home Tele Voucher", "Home Eksklusif Therapy", "Home Free Family Therapy", "Home Demo Health & Safety with WAKi", "Home Voucher", "Home Tele Free Gift", "Home Refrensi Product", "Home Delivery", "Home Free Refrensi Therapy VIP"];
        $type_customers = ["VVIP (Type A)", "WAKi Customer (Type B)", "New Customer (Type C)"];

        $data = ['result' => 1,
                 'type_homeservices' => $type_homeservices,
                 'type_customers' => $type_customers
                ];
        return response()->json($data, 200);
    }


    public function fetchCSOFIlter(Request $request){
        $csosId = [];
        switch(true){
            case($request->menu == "home service"):
                $Homeservices = HomeService::Where([['branch_id', $request->branch_id], ['active', true]])->get();
                foreach( $Homeservices as $Homeservice){
                    array_push($csosId, $Homeservice->cso_id);
                }
                $data = Cso::where('code','like',  '%'.$request->text.'%')->whereIn('id', array_unique($csosId))->get();
                $result = "true";
                if(sizeof($data) < 0){
                    $result = "false";
                }
                
                return response()->json([
                    'result' =>$result,
                    'data' => $data
                ], 200);
            case($request->menu == "registration"):
                $registrations = DeliveryOrder::Where([['branch_id', $request->branch_id], ['active', true]])->get();
                foreach( $registrations as $registration){
                    array_push($csosId, $registration->cso_id);
                }
                $data = Cso::where('code','like',  '%'.$request->text.'%')->whereIn('id', array_unique($csosId))->get();
                $result = "false";
                if(sizeof($data) > 0){
                    $result = "true";
                }
                
                return response()->json([
                    'result' =>$result,
                    'data' => $data
                ], 200);
            case($request->menu == "order"):
                $Orders = Order::where([['branch_id', $request->branch_id], ['active', true]])->get();
                foreach( $Orders as $Order){
                    array_push($csosId, $Order->cso_id);
                }
                
                $data = Cso::where('code','like',  '%'.$request->text.'%')->whereIn('id', array_unique($csosId))->get();
                $result = "false";
                if(sizeof($data) > 0){
                    $result = "true";
                }
                
                return response()->json([
                    'result' =>$result,
                    'data' => $data
                ], 200);
        }
    }
}
