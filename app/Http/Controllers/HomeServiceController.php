<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\HomeService;
use App\Branch;
use App\Cso;
use App\Utils;
use Carbon\Carbon;
use App\Exports\HomeServicesExport;
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
        return view('homeservice', compact('branches'));
    }

    public function indexAdmin()
    {
    	$branches = Branch::all();
        return view('admin.add_home_service', compact('branches'));
    }
    
    public function store(Request $request){
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
        $data['cso2_id'] = Cso::where('code', $data['cso2_id'])->first()['id'];
        $data['appointment'] = $data['date']." ".$data['time'];
        $order = HomeService::create($data);
        //return response()->json(['berhasil' => $data]);
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

        // $homeServices = collect($homeServices) -> paginate(10);

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
                    array('email' => $cso2->first()['email']),
                ),
                'reminders' => array(
                    'useDefault' => FALSE,
                    'overrides' => array(
                    array('method' => 'email', 'minutes' => 24 * 60),
                    array('method' => 'popup', 'minutes' => 10),
                    ),
                ),
            );

            $event = $this->gCalendarController->store($event);
            DB::commit();
            return response()->json(['success' => 'Berhasil'." ".$cso->first()['email']]);
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
            DB:beginTransaction();
            try{
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
                $historyUpdate['meta'] = ['user'=>$user['id'],'createdAt' => date("Y-m-d h:i:s"), 'dateChange'=> $data];
                $historyUpdate['user_id'] = $user['id'];

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
}
