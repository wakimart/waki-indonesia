<?php

namespace App\Http\Controllers;

use App\Branch;
use App\CategoryProduct;
use App\Cso;
use App\DeliveryOrder;
use App\Exports\HomeServicesExport;
use App\Exports\HomeServicesExportByDate;
use App\Exports\HomeServicesCompareExport;
use App\HistoryUpdate;
use App\HomeService;
use App\Http\Controllers\gCalendarController;
use App\Order;
use App\User;
use App\Utils;
use App\Reference;
use DateTime;
use Validator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManagerStatic as Image;

class HomeServiceController extends Controller
{
    public function index()
    {
        $branches = Branch::where('active', true)->orderBy("code", 'asc')->get();
        $categoryProducts = CategoryProduct::all();
        return view('homeservice', compact('branches', 'categoryProducts'));
    }

    public function indexAdmin(Request $request)
    {
        $branches = Branch::where('active', true)->orderBy("code", 'asc')->get();
        if(isset($request->reference_id)){
            $autofill = Reference::find($request->reference_id);
            return view('admin.add_home_service', compact('branches', 'autofill'));
        }
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
        $homeService['district'] = array($homeService->getDistrict());
        $categoryProducts = CategoryProduct::all();
        return view('homeservicesuccess', compact('homeService', $categoryProducts));
    }

    /* public function admin_ListHomeService(Request $request){
        $branches = Branch::Where('active', true)->get();
        $csos = Cso::where('active', true)->get();
        $awalBulan = Carbon::now()->startOfMonth()->subMonth(1);
        $akhirBulan = Carbon::now()->startOfMonth()->addMonth(2); // 5
        // $awalBulan = "2020-10-01 00:00:00";
        // $akhirBulan = "2021-01-01 00:00:00";
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
        if($request->has('filter_province')){
            $homeServices = $homeServices->where('home_services.province', $request->filter_province);
        }
        if($request->has('filter_city')){
            $homeServices = $homeServices->where('home_services.city', $request->filter_city);
        }
        if($request->has('filter_district')){
            $homeServices = $homeServices->where('home_services.distric', $request->filter_district);
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
    } */

    public function admin_ListHomeService(Request $request)
    {
        // Inisialisasi variabel $branches dan diisi dengan data dari tabel "branches"
        $branches = Branch::select(
            [
                "id",
                "code",
                "name",
            ]
        )
        ->where("active", true)
        ->orderBy("code", 'asc')
        ->get();

        // Inisialisasi variabel $csos dan diisi dengan data dari tabel "csos"
        $csos = Cso::select(
            [
                "code",
                "name",
            ]
        )
        ->where("active", true)
        ->orderBy("code", 'asc')
        ->get();

        // Inisialisasi variabel $arrBranches
        $arrBranches = [];
        // Jika user memiliki peran sebagai "branch" atau "area-manager"
        if (
            Auth::user()->roles[0]['slug'] === 'branch'
            || Auth::user()->roles[0]['slug'] === 'area-manager'
        ) {
            // Mengisi variabel $arrBranches dengan branch_id
            foreach (Auth::user()->listBranches() as $value) {
                $arrBranches[] = $value["id"];
            }
        }

        // Jika filter_branch memiliki value dan user tidak memiliki peran sebagai "branch"
        if (
            $request->filled('filter_branch')
            && Auth::user()->roles[0]['slug'] !== 'branch'
        ) {
            if (!empty($request->filter_branch)) {
                $arrBranches = [];

                // Push value dari filter_branch ke dalam variabel $arrBranches
                $arrBranches[] = $request->filter_branch;
            }
        }

        // Jika user memiliki peran sebagai "cso"
        if (Auth::user()->roles[0]['slug'] === "cso") {
            // Query ke tabel "csos" menggunakan cso_id yang dimiliki user
            $getCSOCode = Cso::where("id", Auth::user()->cso["id"])->first();

            // Re-assign $request->filter_cso dengan kode CSO yang didapat dari query
            $request->filter_cso = $getCSOCode->code;
        }

        // Jika user memiliki peran sebagai "admin-management"
        $isAdminManagement = false;
        if (Auth::user()->roles[0]["slug"] === "admin-management") {
            $request->filter_province = 11;
            $request->filter_city = 444;
            $isAdminManagement = true;
        }

        // Mendapatkan count appointment
        $currentMonthDataCount = $this->getCountAppointment(
            date("Y-m-d"),
            $request->filter_province,
            $request->filter_city,
            $request->filter_district,
            $arrBranches,
            $request->filter_cso,
            $request->filter_search,
            $isAdminManagement
        );

        // Inisialisasi variabel $todayDate dan mengisi dengan tanggal hari ini
        $todayDate = date("Y-m-d");

        $currentDayData = $this->getDayData(
            $todayDate,
            $request->filter_province,
            $request->filter_city,
            $request->filter_district,
            $arrBranches,
            $request->filter_cso,
            $request->filter_search,
            $isAdminManagement
        );

        return view(
            "admin.list_homeservice_new",
            compact(
                "currentMonthDataCount",
                "currentDayData",
                "branches",
                "csos"
            )
        );
    }

    private function getCountAppointment(
        $startdate,
        $filterProvince = null,
        $filterCity = null,
        $filterDistrict = null,
        $filterBranch = null,
        $filterCSO = null,
        $filterSearch = null,
        $isAdminManagement = false
    ) {
        // Inisialisasi tanggal awal bulan dari variabel $startDate
        $start = date("Y-m-01 00:00:00", strtotime($startdate));

        // Inisialisasi tanggal akhir bulan dari $startDate
        $end = date("Y-m-t 23:59:59", strtotime($startdate));

        $currentMonthDataCount = DB::table("home_services")
        ->select(
            [
                DB::raw("DATE(appointment) AS appointment_date"),
                DB::raw("COUNT(id) AS data_count"),
            ]
        )
        ->where("active", 1);

        if (!empty($filterProvince) && !$isAdminManagement) {
            $currentMonthDataCount = $currentMonthDataCount->where(
                "province",
                $filterProvince
            );
        }

        if (!empty($filterCity) && !$isAdminManagement) {
            $currentMonthDataCount = $currentMonthDataCount->where(
                "city",
                $filterCity
            );
        }

        if (!empty($filterDistrict) && !$isAdminManagement) {
            $currentMonthDataCount = $currentMonthDataCount->where(
                "distric",
                $filterDistrict
            );
        }

        if (!empty($filterBranch) && !$isAdminManagement) {
            $currentMonthDataCount = $currentMonthDataCount->whereIn(
                "branch_id",
                $filterBranch
            );
        }

        if (!empty($filterCSO) && !$isAdminManagement) {
            $cso_id = Cso::where('code', $filterCSO)->first();

            $currentMonthDataCount = $currentMonthDataCount->where(
                "cso_id",
                $cso_id->id
            );
        }

        if (!empty($filterSearch) && !$isAdminManagement) {
            $currentMonthDataCount = $currentMonthDataCount->where(
                "name",
                "like",
                "%" . $filterSearch . "%"
            )
            ->orWhere(
                "phone",
                "like",
                "%" . $filterSearch . "%"
            )
            ->orWhere(
                "code",
                "like",
                "%" . $filterSearch . "%"
            );
        }

        if ($isAdminManagement) {
            if (strtotime($startdate) < strtotime("now")) {
                $start = date("Y-m-d");
            }

            $currentMonthDataCount = $currentMonthDataCount->where(
                "province",
                11
            )
            ->where(
                "city",
                444
            );
        }

        $currentMonthDataCount = $currentMonthDataCount->whereBetween("appointment", [$start, $end])
        ->groupBy(DB::raw("DATE(appointment)"))
        ->get();

        return $currentMonthDataCount;
    }

    public function printAppointmentCount(Request $request)
    {
        // Inisialisasi variabel $arrBranches
        $arrBranches = [];
        // Jika user memiliki peran sebagai "branch" atau "area-manager"
        if (
            Auth::user()->roles[0]['slug'] === 'branch'
            || Auth::user()->roles[0]['slug'] === 'area-manager'
        ) {
            // Mengisi variabel $arrBranches dengan branch_id
            foreach (Auth::user()->listBranches() as $value) {
                $arrBranches[] = $value["id"];
            }
        }

        // Jika filter_branch memiliki value dan user tidak memiliki peran sebagai "branch"
        if (
            $request->has('filter_branch')
            && Auth::user()->roles[0]['slug'] !== 'branch'
        ) {
            if (!empty($request->filter_branch)) {
                $arrBranches = [];

                // Push value dari filter_branch ke dalam variabel $arrBranches
                $arrBranches[] = $request->filter_branch;
            }
        }

        $branchCode = "";
        $branchCode = implode(", ", $arrBranches);


        // Jika user memiliki peran sebagai "cso"
        if (Auth::user()->roles[0]['slug'] === "cso") {
            // Query ke tabel "csos" menggunakan cso_id yang dimiliki user
            $getCSOCode = Cso::where("id", Auth::user()->cso["id"])->first();

            // Re-assign $request->filter_cso dengan kode CSO yang didapat dari query
            $request->filter_cso = $getCSOCode->code;
        }

        // Jika user memiliki peran sebagai "admin-management"
        $isAdminManagement = false;
        if (Auth::user()->roles[0]["slug"] === "admin-management") {
            $isAdminManagement = true;
        }

        $currentMonthDataCount = $this->getCountAppointment(
            $request->date,
            $request->filter_province,
            $request->filter_city,
            $request->filter_district,
            $arrBranches,
            $request->filter_cso,
            $request->filter_search,
            $isAdminManagement
        );

        $requestedMonth = strtotime($request->date);

        $startDay = (int) date("w", $requestedMonth);
        $maxDate = (int) date("t", $requestedMonth);

        $month = date("m", $requestedMonth);
        $year = date("Y", $requestedMonth);

        $todayDate = 0;

        $currentDate = date("Y-m");
        $requestedYearAndMonth = $month . "-" . $month;

        if ($requestedYearAndMonth === $currentDate) {
            $todayDate = date("j");
        }

        $dayCounter = 1;

        $echoSwitch = false;

        $rowCount = 0;
        $result = "";

        $iMax = 28;
        $startMonthStartDay = $startDay + $maxDate;
        if ($startMonthStartDay > 28) {
            $iMax = 35;
        }
        if ($startMonthStartDay > 35) {
            $iMax = 42;
        }

        for ($i = 0; $i < $iMax; $i++) {
            if ($rowCount === 0) {
                $result .= '<div class="cjslib-row" data-i-value="' . $i . '">';
            }

            if ($i === $startDay) {
                $echoSwitch = true;
            }

            if (!$echoSwitch) {
                $result .= '<label class="calendarContainer cjslib-day cjslib-day-diluted" data-i-value="' . $i . '">'
                    . '</label>';
            }

            if ($echoSwitch) {
                if ($dayCounter <= $maxDate) {
                    $result .= '<input class="cjslib-day-radios" '
                        . 'type="radio" '
                        . 'name="calendarContainer-day-radios" '
                        . 'id="calendarContainer-day-radio-' . $dayCounter . '"'
                        . 'data-date="' . $dayCounter . '"'
                        . 'data-month="' . $month . '"'
                        . 'data-year="' . $year . '"'
                        . 'data-province="' . $request->filter_province . '" '
                        . 'data-city="' . $request->filter_city . '" '
                        . 'data-district="' . $request->filter_district . '" '
                        . 'data-search="' . $request->filter_search . '" '
                        . 'data-branch="' . $branchCode . '" '
                        . 'data-cso="' . $request->filter_cso . '" '
                        . 'onclick="changeDate(this)"'
                        . '/>';

                    $result .= '<label class="calendarContainer cjslib-day cjslib-day-listed';

                    if ($dayCounter === $todayDate) {
                        $result .= ' cjslib-day-today';
                    }

                    $result .= '" for="calendarContainer-day-radio-' . $dayCounter . '" '
                        . 'id="calendarContainer-day-' . $dayCounter . '">';

                    $result .= '<span class="cjslib-day-num" '
                        . 'id="calendarContainer-day-num-' . $dayCounter . '">'
                        . $dayCounter
                        . '</span>';

                    $dayWithZero = $dayCounter;
                    if ($dayCounter < 10) {
                        $dayWithZero = "0" . $dayCounter;
                    }

                    if (!empty($currentMonthDataCount)) {
                        foreach ($currentMonthDataCount as $key => $value) {
                            if ($value->appointment_date === $year . "-" . $month . "-" . $dayWithZero) {
                                $result .= '<span class="cjslib-day-indicator cjslib-indicator-pos-bottom cjslib-indicator-type-numeric" '
                                    . 'id="calendarContainer-day-indicator-' . $dayCounter . '">';
                                $result .= $value->data_count;
                                $result .= '</span>';

                                unset($currentMonthDataCount[$key]);

                                break 1;
                            }
                        }
                    }

                    $result .= "</label>";

                    $dayCounter++;
                }

                if ($dayCounter > $maxDate) {
                    $echoSwitch = false;
                }
            }

            $rowCount++;

            if ($rowCount === 7) {
                $result .= '</div>';
                $rowCount = 0;
            }
        }

        return response($result, 200)->header("Content-Type", "text/plain");

    }

    private function getDayData(
        $requestedDate,
        $filterProvince = null,
        $filterCity = null,
        $filterDistrict = null,
        $filterBranch = null,
        $filterCSO = null,
        $filterSearch = null,
        $isAdminManagement = false
    ) {
        $todayDate = date("Y-m-d", strtotime($requestedDate));

        $currentDayData = HomeService::select(
            "h.id AS hs_id",
            "h.code AS hs_code",
            "h.name AS customer_name",
            "h.phone AS customer_phone",
            "h.appointment AS appointment",
            "b.code AS branch_code",
            "b.name AS branch_name",
            "c.name AS cso_name",
            "h.created_at AS created_at",
            "h.updated_at AS updated_at"
        )
        ->from("home_services AS h")
        ->leftJoin(
            "branches AS b",
            "b.id",
            "=",
            "h.branch_id"
        )
        ->leftJoin(
            "csos AS c",
            "c.id",
            "=",
            "h.cso_id"
        )
        ->where("h.active", true);

        if ($isAdminManagement) {
            if (strtotime($requestedDate) < strtotime("now")) {
                $todayDate = date("Y-m-d");
            }

            $currentDayData = $currentDayData->where(
                "province",
                11
            )
            ->where(
                "city",
                444
            );
        }

        $beginDay = $todayDate . " 00:00:00";
        $endDay = $todayDate . " 23:59:59";

        if (!empty($filterProvince) && !$isAdminManagement) {
            $currentDayData = $currentDayData->where(
                "h.province",
                $filterProvince
            );
        }

        if (!empty($filterCity) && !$isAdminManagement) {
            $currentDayData = $currentDayData->where(
                "h.city",
                $filterCity
            );
        }

        if (!empty($filterDistrict) && !$isAdminManagement) {
            $currentDayData = $currentDayData->where(
                "h.distric",
                $filterDistrict
            );
        }

        if (!empty($filterBranch) && !$isAdminManagement) {
            $currentDayData = $currentDayData->whereIn(
                "h.branch_id",
                $filterBranch
            );
        }

        if (!empty($filterCSO) && !$isAdminManagement) {
            $cso_id = Cso::where('code', $filterCSO)->first();

            $currentDayData = $currentDayData->where(
                "h.cso_id",
                $cso_id->id
            );
        }

        if (!empty($filterSearch) && !$isAdminManagement) {
            $currentDayData = $currentDayData->where(
                "h.name",
                "like",
                "%" . $filterSearch . "%"
            )
            ->orWhere(
                "h.phone",
                "like",
                "%" . $filterSearch . "%"
            )
            ->orWhere(
                "h.code",
                "like",
                "%" . $filterSearch . "%"
            );
        }

        $currentDayData = $currentDayData->whereBetween(
            "h.appointment",
            [
                $beginDay,
                $endDay,
            ]
        )
        ->orderBy("h.appointment", "ASC")
        ->get();

        return $currentDayData;
    }

    public function printDayData(Request $request)
    {
        // Inisialisasi variabel $arrBranches
        $arrBranches = [];
        // Jika user memiliki peran sebagai "branch" atau "area-manager"
        if (
            Auth::user()->roles[0]['slug'] === 'branch'
            || Auth::user()->roles[0]['slug'] === 'area-manager'
        ) {
            // Mengisi variabel $arrBranches dengan branch_id
            foreach (Auth::user()->listBranches() as $value) {
                $arrBranches[] = $value["id"];
            }
        }

        // Jika filter_branch memiliki value dan user tidak memiliki peran sebagai "branch"
        if (
            $request->has('filter_branch')
            && Auth::user()->roles[0]['slug'] !== 'branch'
        ) {
            if (!empty($request->filter_branch)) {
                $arrBranches = [];

                // Push value dari filter_branch ke dalam variabel $arrBranches
                $arrBranches[] = $request->filter_branch;
            }
        }

        // Jika user memiliki peran sebagai "cso"
        if (Auth::user()->roles[0]['slug'] === "cso") {
            // Query ke tabel "csos" menggunakan cso_id yang dimiliki user
            $getCSOCode = Cso::where("id", Auth::user()->cso["id"])->first();

            // Re-assign $request->filter_cso dengan kode CSO yang didapat dari query
            $request->filter_cso = $getCSOCode->code;
        }

        // Jika user memiliki peran sebagai "admin-management"
        $isAdminManagement = false;
        if (Auth::user()->roles[0]["slug"] === "admin-management") {
            $isAdminManagement = true;
        }

        $currentDayData = $this->getDayData(
            $request->date,
            $request->filter_province,
            $request->filter_city,
            $request->filter_district,
            $arrBranches,
            $request->filter_cso,
            $request->filter_search,
            $isAdminManagement
        );

        $result = "";
        if ($currentDayData->isEmpty()) {
            $result .= '<tr>';
            $result .= '<td colspan="7">'
                . '<div class="cjslib-rows" id="organizerContainer-list-container">'
                . '<ol class="cjslib-list" id="organizerContainer-list">'
                . '<div class="cjslib-list-placeholder">'
                . '<li style="text-align:center; margin-top: 1em;">'
                . 'No appointments on this day.'
                . '</li>'
                . '</div>'
                . '</ol>'
                . '</div>'
                . '</td>';
            $result .= '</tr>';
        } else {
            $i = 1;
            foreach ($currentDayData as $dayData) {
                $result .= '<tr>'
                    . '<td style="text-align: center">'
                    . $i
                    . '</td>'
                    . '<td style="text-align: center">';

                $time = new DateTime($dayData->appointment);
                $result .= $time->format("H:i");

                $result .= '</td>'
                    . '<td>';

                if (!$isAdminManagement) {
                    $result .= '<p class="titleAppoin">'
                    . '<a href="'
                    . route('homeServices_success')
                    . '?code='
                    . $dayData->hs_code
                    . '" target="_blank">'
                    . $dayData->hs_code
                    . '</a>'
                    . '</p>';
                }

                $result .= '<p class="descAppoin">';

                if (!$isAdminManagement) {
                    $result .= $dayData->customer_name . ' - ' . $dayData->customer_phone
                        . '<br>';
                }

                $result .= 'Branch: '
                    . $dayData->branch_code . ' - ' . $dayData->branch_name
                    . '<br>'
                    . 'CSO: ' . $dayData->cso_name
                    . '<br>';

                if (!$isAdminManagement) {
                    $result .= 'Created at: ' . $dayData->created_at
                        . '<br>'
                        . 'Last update: ' . $dayData->updated_at;
                }

                $result .= '</p>'
                    . '</td>';


                if (!$isAdminManagement) {
                    $result .= '<td style="text-align: center">';

                    if(Auth::user()->hasPermission('detail-home_service')){
                        $result .= '<button '
                            . 'class="btnappoint btn-gradient-primary mdi mdi-eye btn-homeservice-view" '
                            . 'type="button" '
                            . 'data-toggle="modal" '
                            . 'data-target="#viewHomeServiceModal" '
                            . 'onclick="clickView(this)" '
                            . 'value="' . $dayData->hs_id . '">'
                            . '</button>'
                            . '</td>'
                            . '<td style="text-align: center">';
                    }
                    else{
                        $result .= '</td>'
                            . '<td style="text-align: center">';
                    }

                    if(Auth::user()->hasPermission('edit-home_service')){
                        $result .= '<button '
                            . 'class="btnappoint btn-gradient-success mdi mdi-cash-multiple btn-homeservice-cash" '
                            . 'type="button" '
                            . 'data-toggle="modal" '
                            . 'data-target="#cashHomeServiceModal" '
                            . 'onclick=clickCash(this) '
                            . 'value="' . $dayData->hs_id . '">'
                            . '</button>'
                            . '</td>'
                            . '<td style="text-align: center">';

                        $result .= '<button '
                            . 'class="btnappoint btn-gradient-info mdi mdi-border-color btn-homeservice-edit" '
                            . 'type="button" '
                            . 'data-toggle="modal" '
                            . 'data-target="#editHomeServiceModal" ';

                        if (Auth::user()->roles[0]["slug"] === "cso") {
                            $result .= 'data-cso="true" ';
                        } else {
                            $result .= 'data-cso="false" ';
                        }

                        $result .= 'onclick="clickEdit(this)" '
                            . 'value="' . $dayData->hs_id . '">'
                            . '</button>'
                            . '</td>'
                            . '<td style="text-align: center">';
                    }
                    else{
                        $result .= '</td>'
                            . '<td style="text-align: center">'
                            . '</td>'
                            . '<td style="text-align: center">';
                    }

                    if(Auth::user()->hasPermission('delete-home_service')){
                        $result .= '<button '
                            . 'class="btnappoint btn-gradient-danger mdi mdi-calendar-remove btn-homeservice-cancel" '
                            . 'type="button" '
                            . 'data-toggle="modal" '
                            . 'data-target="#deleteHomeServiceModal" '
                            . 'onclick="clickCancel(this)" '
                            . 'value="' . $dayData->hs_id . '">'
                            . '</button>'
                            . '</td>';
                    }
                    else{
                         $result .= '</td>';
                    }
                } else {
                    $result .= '<td></td><td></td><td></td><td></td>';
                }

                $result .= '</tr>';

                $i++;
            }
        }

        return response($result, 200)->header("Content-Type", "text/plain");
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
        // dd($request->all());
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
            $all_homeservice = [];
            $get_dateAppointment = $request->date;
            $get_timeAppointment = $request->time;


            if(count($get_dateAppointment) != count(array_unique($get_dateAppointment))){
                return response()->json(['errors' => "Tanggal appointment tidak boleh sama!!"]);
            }else{
                DB::beginTransaction();
                try{
                    foreach ($get_dateAppointment as $key => $value) {
                        $inputAppointment = $value." ".$get_timeAppointment[$key];
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

                        $counterExecutive=0;
                        $counterFamily=0;
                        $counterRumah=0;
                        $homeServiceRawData = HomeService::where([['phone', $request->phone],['active', true]])->get();
                        if ($homeServiceRawData != null && count($homeServiceRawData) > 0){
                            for ($i=0; $i<count($homeServiceRawData);$i++){
                                if ($homeServiceRawData[$i]->type_homeservices == "Upgrade Member" && $request->type_homeservices == "Upgrade Member"){
                                    return response()->json(['errors' => "Upgrade Member Hanya Sekali Saja Untuk Nomer Yang Sama"]);
                                    //return redirect()->back()->with("errors","Upgrade Member Hanya Sekali Saja Untuk Nomer Yang Sama");
                                }

                                if (date("Y-m-d", strtotime($homeServiceRawData[$i]->appointment)) == date("Y-m-d", strtotime($inputAppointment))){
                                    return response()->json(['errors' => ['type_homeservices' => "Appointment dengan nomer ini sudah ada!!"]]);
                                    //return redirect()->back()->with("errors","Appointment dengan nomer ini sudah ada!!");
                                }

                                if($homeServiceRawData[$i]->type_homeservices == "Home Eksklusif Therapy"){
                                    $counterExecutive ++;
                                }
                                if($homeServiceRawData[$i]->type_homeservices == "Home Family Therapy"){
                                    $counterFamily ++;
                                }
                                if($homeServiceRawData[$i]->type_homeservices == "Home WAKi di Rumah Aja"){
                                    $counterRumah ++;
                                }
                            }
                        }

                        $homeServiceDataTiga = HomeService::where([['phone', $request->phone],['active', true],['type_homeservices', 'Home service']])->orderBy('appointment', 'desc')->first();
                        if($homeServiceDataTiga != null && $request->type_homeservices == "Home service"){
                            if(date("Y-m-d", strtotime($homeServiceDataTiga->appointment)) >= date("Y-m-d", strtotime($inputAppointment.' -7 days'))){
                                return response()->json(['errors' => ['type_homeservices' => "Nomer Telpon Tersebut Telah Di Gunakan Dalam Home Service Dengan Type Home service "]]);
                                //return redirect()->back()->with("errors","Nomer Telpon Tersebut Telah Di Gunakan Dalam Home Service Dengan Type Home service ");
                            }
                        }

                        if($counterExecutive >=3 && $request->type_homeservices == "Home Eksklusif Therapy"){
                            return response()->json(['errors' =>['type_homeservices' =>  "Home Service dengan Tipe 'Home Family Therapy' atau 'Home Executive Therapy' Hanya Bisa Masing-Masing di Gunakan 3 Kali Dalam 2 Minggu Dengan Nomor Inputan yang Sama"]]);
                            //return redirect()->back()->with("errors","Home Service dengan Tipe 'Home Eksklusif Therapy' atau 'Home Executive Therapy' Hanya Bisa Masing-Masing di Gunakan 3 Kali Dalam 2 Minggu Dengan Nomor Inputan yang Sama");
                        }
                        else if($counterFamily >=3 && $request->type_homeservices == "Home Family Therapy"){
                            return response()->json(['errors' =>['type_homeservices' =>  "Home Service dengan Tipe 'Home Family Therapy' atau 'Home Executive Therapy' Hanya Bisa Masing-Masing di Gunakan 3 Kali Dalam 2 Minggu Dengan Nomor Inputan yang Sama"]]);
                            //return redirect()->back()->with("errors","Home Service dengan Tipe 'Home Family Therapy' atau 'Home Executive Therapy' Hanya Bisa Masing-Masing di Gunakan 3 Kali Dalam 2 Minggu Dengan Nomor Inputan yang Sama");
                        }
                        else if($counterFamily >=5 && $request->type_homeservices == "Home WAKi di Rumah Aja"){
                            return response()->json(['errors' =>['type_homeservices' =>  "Home Service dengan Tipe 'Home Family Therapy' atau 'Home Executive Therapy' Hanya Bisa Masing-Masing di Gunakan 5 Kali Dalam 2 Minggu Dengan Nomor Inputan yang Sama"]]);
                            //return redirect()->back()->with("errors","Home Service dengan Tipe 'Home WAKi di Rumah Aja' atau 'Home Executive Therapy' Hanya Bisa Masing-Masing di Gunakan 3 Kali Dalam 2 Minggu Dengan Nomor Inputan yang Sama");
                        }

                        $data = $request->all();
                        $data['code'] = "HS/".strtotime(date("Y-m-d H:i:s"))."/".substr($data['phone'], -4)."/".$key;

                        $getAppointment = $value." ".$get_timeAppointment[$key];
                        $getIdCso = Cso::where('code', $data['cso_id'])->first()['id'];
                        $getHomeServices = HomeService::where([
                            ['cso_id', '=', $getIdCso],
                            ['appointment', '=', $getAppointment], ['active', '=', true],
                        ])->get();


                        if (count($getHomeServices) > 0) {
                            return response()->json(['errors' => ['type_homeservices' => "An appointment has been already scheduled."]]);
                            //return redirect()->back()->with("errors","An appointment has been already scheduled.");
                        }

                        $cso = Cso::where('code', $data['cso_id']);
                        $cso2 = Cso::where('code', $data['cso2_id']);
                        $data['cso_id'] = $cso->first()['id'];
                        $data['cso2_id'] = $cso2->first()['id'];
                        $data['appointment'] = $inputAppointment;
                        $data['province'] = (int)$data['province_id'];
                        $data['distric'] = $data['subDistrict'];

                        $startDateTime = $value."T".$get_timeAppointment[$key].":00";
                        $time = strtotime($get_timeAppointment[$key]) + 60*60 * 2;
                        $endDateTime = $value."T".date('H:i', $time).":00";
                        // DB::beginTransaction();
                        // try{
                        $order = HomeService::create($data);
                        array_push($all_homeservice, $order['code']);
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
                    }
                    //return response()->json(['testing' => $all_homeservice[0]]);
                    DB::commit();
                    //return redirect()->route('homeServices_success', ['code'=>$all_homeservice[0]]);
                    return response()->json(['success' => "Berhasil", 'code'=>$all_homeservice]);
                } catch (\Exception $ex) {
                    DB::rollback();
                    //return redirect()->back()->with("errors",$ex->getMessage());
                    return response()->json(['errors' => $ex->getMessage()], 500);
                }
            }



            // if(count($get_dateAppointment) != count(array_unique($get_dateAppointment))){
            //     return response()->json(['errors' => "Tanggal appointment tidak boleh sama!!"]);
            //     // return redirect()->back()->with("errors","Tanggal appointment tidak boleh sama!!");
            // }else{

            // }
        }
    }

    public function edit(Request $request)
    {
        if($request->has('id')){
            $data = HomeService::find($request->id);
            $data['province_name'] = $data->provinceObj['province'];
            $data['city_name'] = $data->cityObj['type'].' '.$data->cityObj['city_name'];
            $data['district_name'] = $data->districObj['subdistrict_name'];
            $data['cso_code_name'] = $data->cso['code'].' - '.$data->cso['name'];
            $data['cso2_code_name'] = $data->cso2['code'].' - '.$data->cso2['name'];
            $data['branch_code_name'] = $data->branch['code'].' '.$data->branch['name'];
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

            if($request->hasFile('cash_image')){
                $cash_img = [];
                $path = "sources/homeservice";

                if (!is_dir($path)) {
                    File::makeDirectory($path, 0777, true, true);
                }

                $imgNya = $request->file('cash_image');
                $fileName = str_replace([' ', ':'], '',
                    Carbon::now()->toDateTimeString()) . "_cashimage." .
                    $imgNya->getClientOriginalExtension();

                //compressed img
                $compres = Image::make($imgNya->getRealPath());
                $compres->resize(540, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($path.'/'.$fileName);

                array_push($cash_img, $fileName);

                $homeService->image = $cash_img;
            }

            $homeService->cash_description = $request['cash_description'];
            $homeService->save();
        }
        else{
            DB::beginTransaction();
            try{
                $appointmentBefore = HomeService::find($request->id);
                $data = $request->all();
                // $data['code'] = "HS/".strtotime(date("Y-m-d H:i:s"))."/".substr($data['phone'], -4);
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

    public function ListHSforSubmission(Request $request)
    {
        $date = date("Y-m-d");
        if($request->has('date')){
            $date = date_create($request->date);
        }

        if($request->has('submission_id')){
            $branch_id = \App\Submission::find($request->submission_id)->branch['id'];
        }
        else{
            $branch_id = $request->branch_id;
        }

        DB::beginTransaction();
        try {
            $homeServices = HomeService::whereDate('home_services.appointment', '=', $date)
                            ->where('home_services.status_reference', false)
                            ->where('home_services.active', true)
                            ->where('home_services.branch_id', $branch_id)
                            ->get();
            $data = ['hs' => $homeServices];
            return response()->json($data, 200);
        }
        catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
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
        return Excel::download(new HomeServicesExport($date, $city, $branch, $cso, $search), 'Home Service.xlsx');
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
    public function export_to_xls_compare(Request $request)
    {
        $startDate = null;
        $endDate = null;

        if($request->has('filter_startDate')&&$request->has('filter_endDate')){
            $startDate = $request->filter_startDate;
            $endDate = $request->filter_endDate;
            $endDate = new \DateTime($endDate);
            $endDate = $endDate->modify('+1 day')->format('Y-m-d');
        }

        return Excel::download(new HomeServicesCompareExport(array($startDate, $endDate)), 'Home Service Comparison By Date.xlsx');
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
            'date_0' => 'required',
            'time_0' => 'required',
            'cso2_id' => ['exists:csos,code']
        ], $messages);

        if ($validator->fails()){
            $data = ['result' => 0,
                     'data' => $validator->errors()
                    ];
            return response()->json($data, 401);
        }
        else{
            $raw_data = $request->all();

            $raw_index = 0;
            $raw_data['arr_date'] = [];
            $raw_data['arr_time'] = [];
            foreach ($raw_data as $key => $item) {
                $arrKey = explode("_", $key);
                if($arrKey[0] == "date"){

                    if(isset($raw_data['time_'.$arrKey[1]])){
                        $raw_data['arr_date'][$raw_index] = [];
                        $raw_data['arr_date'][$raw_index] = $item;

                        $raw_data['arr_time'][$raw_index] = $raw_data['time_'.$arrKey[1]];
                        $raw_index++;
                    }
                }
            }

            $all_homeservice = [];
            $all_appointment = $raw_data['arr_date'];
            $all_appointmentTime = $raw_data['arr_time'];

            if(count($all_appointment)  != count(array_unique($all_appointment))){
                $data = ['result' => 0,
                     'data' => ['date' => ["Tanggal appointment tidak boleh sama!!"]]
                    ];
                return response()->json($data, 401);
            }else{
                foreach ($all_appointment as $key => $value) {
                    //Validation 2
                    $inputAppointment = $value." ".$all_appointmentTime[$key];
                    if($request->type_homeservices == "Home Eksklusif Therapy" || $request->type_homeservices == "Home Free Family Therapy"){
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
                    $counterRumah=0;
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
                            if($homeServiceRawData[$i]->type_homeservices == "Home Free Family Therapy"){
                                $counterFamily ++;
                            }
                            if($homeServiceRawData[$i]->type_homeservices == "Home WAKi di Rumah Aja"){
                                $counterRumah ++;
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

                    if($counterExecutive >3 && $request->type_homeservices == "Home Eksklusif Therapy"){
                        $data = ['result' => 0,
                                 'data' => ['type_homeservices' => ["Hanya Bisa 3 Kali Dalam 2 Minggu Dengan Nomor Sama !"]]
                                ];
                        return response()->json($data, 401);
                    }
                    else if($counterFamily >3 && $request->type_homeservices == "Home Free Family Therapy"){
                        $data = ['result' => 0,
                                 'data' => ['type_homeservices' => ["Hanya Bisa 3 Kali Dalam 2 Minggu Dengan Nomor Sama !"]]
                                ];
                        return response()->json($data, 401);
                    }
                    else if($counterRumah >5 && $request->type_homeservices == "Home WAKi di Rumah Aja"){
                        $data = ['result' => 0,
                                 'data' => ['type_homeservices' => ["Hanya Bisa 5 Kali Dalam 2 Minggu Dengan Nomor Sama !"]]
                                ];
                        return response()->json($data, 401);
                    }


                    $data = $request->all();
                    $data['code'] = "HS/".strtotime(date("Y-m-d H:i:s"))."/".substr($data['phone'], -4)."/".$key;
                    $data['cso_id'] = Cso::where('code', $data['cso_id'])->first()['id'];
                    $data['cso_phone'] = Cso::where('id', $data['cso_id'])->first()['phone'];
                    if($request->has('cso2_id')){
                        $data['cso2_id'] = Cso::where('code', $data['cso2_id'])->first()['id'];
                    }
                    $data['appointment'] = $value." ".$all_appointmentTime[$key];
                    $homeservice = HomeService::create($data);
                    array_push($all_homeservice, $homeservice);
                }

                try{
                    $dt = new DateTime($all_homeservice['appointment']);
                    $phone = preg_replace('/[^A-Za-z0-9\-]/', '', $all_homeservice['phone']);
                    if($phone[0]==0 || $phone[0]=="0"){
                    $phone =  substr($phone, 1);
                    }
                    $phone = "62".$phone;
                    Utils::sendSms($phone, "Terima kasih telah mendaftar layanan 'Home Service' kami. Tim kami akan datang pada tanggal ".$dt->format('j/m/Y')." pukul ".$dt->format('H:i')." WIB. Info lebih lanjut, hubungi ".$data['cso_phone']);
                }
                catch(\Exception $ex){

                }

                $data = ['result' => 1,
                         'data' => $all_homeservice[0]
                        ];
                return response()->json($data, 200);
            }
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

        $validator = Validator::make($request->all(), [
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

        if ($validator->fails()) {
            $data = [
                'result' => 0,
                'data' => $validator->errors(),
            ];

            return response()->json($data, 401);
        } else {
            $appointmentBefore = HomeService::find($request->id);

            $data = $request->all();

            $data['cso_id'] = Cso::where('code', $data['cso_id'])->first()['id'];
            $data['cso_phone'] = Cso::where('id', $data['cso_id'])->first()['phone'];

            if ($request->has('cso2_id')) {
                $data['cso2_id'] = Cso::where('code', $data['cso2_id'])->first()['id'];
            }

            $data['appointment'] = $data['date']." ".$data['time'];
            $homeservice = HomeService::find($data['id']);
            $homeservice->fill($data)->save();

            // Menyimpan riwayat pembaruan ke tabel history_updates
            $historyHomeService = [];
            $historyHomeService["type_menu"] = "Home Service";
            $historyHomeService["method"] = "Update";
            $historyHomeService["meta"] = [
                "user" => $request->user_id,
                "createdAt" => date("Y-m-d h:i:s"),
                'dataChange'=> $data,
                'appointmentBefore'=>$appointmentBefore->appointment,
            ];
            $historyHomeService["user_id"] = $request->user_id;
            $historyHomeService["menu_id"] = $request->id;

            HistoryUpdate::create($historyHomeService);

            $data = [
                'result' => 1,
                'data' => $homeservice,
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

    public function singleReportHomeService($id){
        $homeService = HomeService::find($id);
        if ($homeService != null) {
            $cash = array(
                'cash' => $homeService->cash,
                'cash_description' => $homeService->cash_description
            );
            $data = [
                    'result' => 1,
                    'data' => $cash
            ];
            return response()->json($data, 200);
        }else {
            $data = [
                'result' => 0,
                'data' => "Data Not Found"
            ];
            return response()->json($data, 404);
        }
    }

    public function deleteApi(Request $request)
    {
        $messages = array(
            'id.required' => 'There\'s an error with the data.',
            'id.exists' => 'There\'s an error with the data.'
        );

        $validator = Validator::make($request->all(), [
            'id' => ['required', 'exists:home_services,id,active,1']
        ], $messages);

        if ($validator->fails()) {
            $data = [
                'result' => 0,
                'data' => $validator->errors()
            ];

            return response()->json($data, 401);
        } else {
                $homeservice = HomeService::find($request->id);
                $homeservice->active = false;
                $homeservice->save();

                // Menyimpan riwayat penghapusan ke tabel history_orders
                $historyHomeService = [];
                $historyHomeService["type_menu"] = "Home Service";
                $historyHomeService["method"] = "Delete";
                $historyHomeService["meta"] = [
                    "user" => $request->user_id,
                    "createdAt" => date("Y-m-d h:i:s"),
                    'dataChange'=> $homeservice->getChanges(),
                ];
                $historyHomeService["user_id"] = $request->user_id;
                $historyHomeService["menu_id"] = $request->id;

                HistoryUpdate::create($historyHomeService);

                $data = [
                    'result' => 1,
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
            if(isset($data['filter']['filter_province'])){
                $homeServices = $homeServices->Where('home_services.province', $data['filter']['filter_provinc']);
            }
            if(isset($data['filter']['filter_city'])){
                $homeServices = $homeServices->Where('home_services.city', $data['filter']['filter_city']);
            }
            if(isset($data['filter']['filter_district'])){
                $homeServices = $homeServices->Where('home_services.distric', $data['filter']['filter_district']);
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
                        ->select('home_services.id', 'home_services.code as code', 'home_services.appointment', 'home_services.no_member as no_member', 'home_services.name as customer_name', 'home_services.city as customer_city', 'home_services.cash', 'home_services.cash_description','home_services.address as customer_address','home_services.phone as customer_phone', 'home_services.type_customer as type_customer', 'home_services.type_homeservices as type_homeservices', 'home_services.distric', 'branches.id as branch_id', 'branches.code as branch_code', 'branches.name as branch_name', 'csos.code as cso_code', 'csos.name as cso_name', 'csos.phone as cso_phone')->first();

        $homeServices['district'] = array($homeServices->getDistrict());
        $homeServices['URL'] = route('homeServices_success')."?code=".$homeServices['code'];
        $data = ['result' => 1,
                 'data' => array($homeServices)
                ];
        return response()->json($data, 200);
    }

    public function listAllTypeHS(){
        $type_homeservices = ["Home Service", "Home Tele Voucher", "Home Eksklusif Therapy", "Home Free Family Therapy", "Home Demo Health & Safety with WAKi", "Home Voucher", "Home Tele Free Gift", "Home Refrensi Product", "Home Delivery", "Home Free Refrensi Therapy VIP", "Home WAKi Di Rumah Aja"];
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
