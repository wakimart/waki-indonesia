<?php

namespace App\Http\Controllers;

use App\Branch;
use App\Cso;
use App\Exports\TechnicianScheduleExport;
use App\HistoryUpdate;
use App\HomeService;
use App\Product;
use App\ProductTechnicianSchedule;
use App\Service;
use App\TechnicianSchedule;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class TechnicianScheduleController extends Controller
{
    public function index(Request $request)
    {
        $branch = Branch::where('code', 'F00')->first();
        // Inisialisasi variabel $csos dan diisi dengan data dari tabel "csos"
        $csos = Cso::select(
            [
                "code",
                "name",
            ]
        )
        ->where("active", true)
        ->where('branch_id', $branch->id)
        ->orderBy("code", 'asc')
        ->get();

        $temp_date = date("Y-m-d");

        if(isset($_GET['isSubmission'])){
            $get_appointment = strtotime($_GET['appointment']);
            $temp_date = date("Y-m-d", $get_appointment);
        }

        // Mendapatkan count appointment
        $currentMonthDataCount = $this->getCountAppointment(
            $temp_date,
            $request->filter_province,
            $request->filter_city,
            $request->filter_district,
            $request->filter_cso,
            $request->filter_search,
        );

        // Inisialisasi variabel $todayDate dan mengisi dengan tanggal hari ini
        $todayDate = date("Y-m-d");

        $currentDayData = $this->getDayData(
            $todayDate,
            $request->filter_province,
            $request->filter_city,
            $request->filter_district,
            $request->filter_cso,
            $request->filter_search,
        );

        return view(
            "admin.list_technicianschedule",
            compact(
                "currentMonthDataCount",
                "currentDayData",
                "csos"
            )
        );
    }

    private function getCountAppointment(
        $startdate,
        $filterProvince = null,
        $filterCity = null,
        $filterDistrict = null,
        $filterCSO = null,
        $filterSearch = null
    ) {
        // Inisialisasi tanggal awal bulan dari variabel $startDate
        $start = date("Y-m-01 00:00:00", strtotime($startdate));

        // Inisialisasi tanggal akhir bulan dari $startDate
        $end = date("Y-m-t 23:59:59", strtotime($startdate));

        $currentMonthDataCount = TechnicianSchedule::select(
                DB::raw("DATE(t.appointment) AS appointment_date"),
                DB::raw("COUNT(t.id) AS data_count"),
        )
        ->from("technician_schedules AS t")
        ->leftJoin(
            "csos AS c",
            "c.id",
            "=",
            "t.technician_id"
        )
        ->where("t.active", 1);

        if (!empty($filterProvince)) {
            $currentMonthDataCount = $currentMonthDataCount->where(
                "t.province",
                $filterProvince
            );
        }

        if (!empty($filterCity)) {
            $currentMonthDataCount = $currentMonthDataCount->where(
                "t.city",
                $filterCity
            );
        }

        if (!empty($filterDistrict)) {
            $currentMonthDataCount = $currentMonthDataCount->where(
                "t.distric",
                $filterDistrict
            );
        }

        if (!empty($filterCSO)) {
            $cso_id = Cso::where('code', $filterCSO)->first();

            $currentMonthDataCount = $currentMonthDataCount->where(
                "t.technician_id",
                $cso_id->id
            );
        }

        if (!empty($filterSearch)) {
            $currentMonthDataCount = $currentMonthDataCount->where(
                function ($query) use ($filterSearch) {
                    $query->where(
                        "t.name",
                        "like",
                        "%" . $filterSearch . "%"
                    )
                    ->orWhere(
                        "t.phone",
                        "like",
                        "%" . $filterSearch . "%"
                    )
                    ->orWhere(
                        "c.name",
                        "like",
                        "%" . $filterSearch . "%"
                    );
                }
            );
        }

        $currentMonthDataCount = $currentMonthDataCount->whereBetween("appointment", [$start, $end])
        ->groupBy(DB::raw("DATE(appointment)"))
        ->get();

        return $currentMonthDataCount;
    }

    public function printAppointmentCount(Request $request)
    {
        $currentMonthDataCount = $this->getCountAppointment(
            $request->date,
            $request->filter_province,
            $request->filter_city,
            $request->filter_district,
            $request->filter_cso,
            $request->filter_search,
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
        $filterCSO = null,
        $filterSearch = null
    ) {
        $todayDate = date("Y-m-d", strtotime($requestedDate));

        $currentDayData = TechnicianSchedule::select(
            "t.id AS ts_id",
            "t.name AS customer_name",
            "t.phone AS customer_phone",
            "t.appointment AS appointment",
            "t.d_o",
            "hs.code as hs_code",
            "s.id as s_id",
            "s.code as s_code",
            "c.name AS cso_name",
            "r.slug AS role_slug",
            "t.created_at AS created_at",
            "t.updated_at AS updated_at"
        )
        ->from("technician_schedules AS t")
        ->leftJoin(
            "home_services as hs",
            "hs.id",
            "=",
            "t.home_service_id"
        )
        ->leftJoin(
            "services as s",
            "s.technician_schedule_id",
            "=",
            "t.id"
        )
        ->leftJoin(
            "csos AS c",
            "c.id",
            "=",
            "t.technician_id"
        )
        ->leftJoin(
            "users AS u",
            "u.cso_id",
            "=",
            "c.id"
        )
        ->leftJoin(
            "role_users AS ru",
            "ru.user_id",
            "=",
            "u.id"
        )
        ->leftJoin(
            "roles AS r",
            "r.id",
            "=",
            "ru.role_id"
        )
        ->where("t.active", true);

        $beginDay = $todayDate . " 00:00:00";
        $endDay = $todayDate . " 23:59:59";

        if (!empty($filterProvince)) {
            $currentDayData = $currentDayData->where(
                "t.province",
                $filterProvince
            );
        }

        if (!empty($filterCity)) {
            $currentDayData = $currentDayData->where(
                "t.city",
                $filterCity
            );
        }

        if (!empty($filterDistrict)) {
            $currentDayData = $currentDayData->where(
                "t.distric",
                $filterDistrict
            );
        }

        if (!empty($filterCSO)) {
            $cso_id = Cso::where('code', $filterCSO)->first();

            $currentDayData = $currentDayData->where(
                "t.technician_id",
                $cso_id->id
            );
        }

        if (!empty($filterSearch)) {
            $currentDayData = $currentDayData->where(
                function ($query) use ($filterSearch) {
                    $query->where(
                        "t.name",
                        "like",
                        "%" . $filterSearch . "%"
                    )
                    ->orWhere(
                        "t.phone",
                        "like",
                        "%" . $filterSearch . "%"
                    )
                    ->orWhere(
                        "c.name",
                        "like",
                        "%" . $filterSearch . "%"
                    );
                }
            );
        }

        $currentDayData = $currentDayData->whereBetween(
            "t.appointment",
            [
                $beginDay,
                $endDay,
            ]
        )
        ->orderBy("t.appointment", "ASC")
        ->get();

        return $currentDayData;
    }

    public function printDayData(Request $request)
    {
        $currentDayData = $this->getDayData(
            $request->date,
            $request->filter_province,
            $request->filter_city,
            $request->filter_district,
            $request->filter_cso,
            $request->filter_search,
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

                $result .= '<p class="titleAppoin">';
                if ($dayData->hs_code) {
                    $result .= '<a href="'
                    . route('homeServices_success')
                    . '?code='
                    . $dayData->hs_code
                    . '" target="_blank">'
                    . $dayData->hs_code
                    . '</a><br>';
                }
                if ($dayData->s_code) {
                    $result .= '<a href="'
                    . route('detail_service', $dayData->s_id)
                    . '" target="_blank">'
                    . $dayData->s_code
                    . '</a>';
                }
                $result .='</p>';

                $result .= '<p class="descAppoin">';

                $result .= $dayData->customer_name . ' - ' . $dayData->customer_phone
                    . '<br>';

                $result .= 'D/O: ' . $dayData->d_o
                . '<br>';
                $result .= 'CSO Teknisi: ' . $dayData->cso_name
                    . '<br>';

                $result .= 'Created at: ' . $dayData->created_at
                    . '<br>'
                    . 'Last update: ' . $dayData->updated_at;

                $result .= '</p>'
                    . '</td>';

                $result .= '<td style="text-align: center">';

                if(Auth::user()->hasPermission('detail-technician_schedule')){
                    $result .= '<button '
                        . 'class="btnappoint btn-gradient-primary mdi mdi-eye btn-homeservice-view" '
                        . 'type="button" '
                        . 'data-toggle="modal" '
                        . 'data-target="#viewTechnicianScheduleModal" '
                        . 'onclick="clickView(this)" '
                        . 'value="' . $dayData->ts_id . '">'
                        . '</button>'
                        . '</td>'
                        . '<td style="text-align: center">';
                }
                else{
                    $result .= '</td>'
                        . '<td style="text-align: center">';
                }

                if(Auth::user()->hasPermission('edit-technician_schedule')){
                    $result .= "<a "
                        . 'class="btnappoint btn-gradient-info mdi mdi-border-color btn-homeservice-edit"'
                        . "href='" . route('edit_technician_schedule', ['id' => $dayData->ts_id]) 
                        . "'></a>"
                        . '</td>'
                        . '<td style="text-align: center">';
                }
                else{
                    $result .= '</td>'
                        . '<td style="text-align: center">'
                        . '</td>'
                        . '<td style="text-align: center">';
                }

                if(Auth::user()->hasPermission('delete-technician_schedule')){
                    $result .= '<button '
                        . 'class="btnappoint btn-gradient-danger mdi mdi-delete btn-homeservice-cancel" '
                        . 'type="button" '
                        . 'data-toggle="modal" '
                        . 'data-target="#deleteTechnicianScheduleModal" '
                        . 'onclick="clickCancel(this)" '
                        . 'value="' . $dayData->ts_id . '">'
                        . '</button>'
                        . '</td>';
                }
                else{
                    $result .= '</td>';
                }

                $result .= '</tr>';
                $i++;
            }
        }

        return response($result, 200)->header("Content-Type", "text/plain");
    }

    public function create(Request $request)
    {
        $branch = Branch::where('code', 'F00')->first();
        $products = Product::all();
        $services = Service::where('active', 1)->orderby('created_at', 'desc')->get();
        if ($request->has('hs_id')) {
            $autofill = HomeService::find($request->hs_id);
            return view('admin.add_technicianschedule', compact('branch', 'products', 'services', 'autofill'));
        }
        return view('admin.add_technicianschedule', compact('branch', 'products', 'services'));
    }

    public function store(Request $request)
    {
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
            DB::beginTransaction();
            try{
                $data = $request->all();
                $inputAppointment = $data['date']." ".$data['time'];
                
                $getIdCso = Cso::where('code', $data['cso_id'])->first()['id'];
                $getTechnicianSchedules = TechnicianSchedule::where([
                    ['technician_id', '=', $getIdCso],
                    ['appointment', '=', $inputAppointment], // ['active', '=', true],
                ])->get();


                if (count($getTechnicianSchedules) > 0) {
                    return response()->json(['errors' => ['Technician' => "An appointment has been already scheduled."]]);
                }

                $data['home_service_id'] = $data["hs_id"] ?? null;
                $data['technician_id'] = $getIdCso;
                $data['appointment'] = $inputAppointment;
                $data['province'] = (int)$data['province_id'];
                $data['city'] = $data['city'];
                $data['district'] = $data['subDistrict'];

                $technician_schedule = TechnicianSchedule::create($data);
                
                if ($request->service_id) {
                    Service::where('id', $request->service_id)->update([
                        'technician_schedule_id' => $technician_schedule['id']
                    ]);
                }

                $get_allProductTs= json_decode($request->productservices);

                foreach ($get_allProductTs as $key => $value) {
                    $data['technician_schedule_id'] = $technician_schedule['id'];
                    $data['product_id'] = null;
                    $data['other_product'] = null;
                    if($value[0] != "other"){
                        $data['product_id'] = $value[0];
                    }else{
                        $data['other_product'] = $value[4];
                    }

                    $data['arr_issues'] = [];
                    $data['arr_issues'][0]['issues'] = $value[1];
                    $data['arr_issues'][1]['desc'] = $value[2];

                    $data['issues'] = json_encode($data['arr_issues']);
                    $data['due_date'] = $value[3];

                    $product_services = ProductTechnicianSchedule::create($data);
                }

                DB::commit();
                return response()->json(['success' => "Berhasil", 'code'=>$technician_schedule['id']]);
            } catch (\Exception $ex) {
                DB::rollback();
                return response()->json(['errors' => $ex->getMessage()], 500);
            }
        }
    }
    
    public function show(Request $request)
    {
        if($request->has('id')){
            $data = TechnicianSChedule::where('id', $request->id)->first();
            $data['province_name'] = $data->provinceObj['province'];
            $data['city_name'] = $data->cityObj['type'].' '.$data->cityObj['city_name'];
            $data['district_name'] = $data->districObj['subdistrict_name'];
            $data['cso_code_name'] = $data->cso['code'].' - '.$data->cso['name'];
            $data['code_homeservice'] = $data->homeService['code'];
            $data['id_service'] = $data->service['id'];
            $data['code_service'] = $data->service['code'];
            $data['products_technician_schedule'] = $data->product_technician_schedule_withProduct;
            return response()->json(['result' => $data]);
        }else{
            return response()->json(['result' => 'Gagal!!']);
        }
    }

    public function edit(Request $request)
    {
        if ($request->has('id')) {
            $branch = Branch::where('code', 'F00')->first();
            $products = Product::all();
            $services = Service::where('active', 1)->orderby('created_at', 'desc')->get();
            $autofill = TechnicianSchedule::with('service')->where('id', $request->id)->first();
            $product_tss = ProductTechnicianSchedule::where([
                ['active', '=', 1],
                ['technician_schedule_id', '=', $request->id]
            ])->get();
            return view('admin.update_technicianschedule', compact('branch', 'products', 'services', 'autofill', 'product_tss'));
        }
    }

    public function update(Request $request){
        $technicianSchedule = TechnicianSchedule::find($request->id);
    
        DB::beginTransaction();
        try{
            $data = $request->all();
            $inputAppointment = $data['date']." ".$data['time'];
            
            $getIdCso = Cso::where('code', $data['cso_id'])->first()['id'];
            $getTechnicianSchedules = TechnicianSchedule::where([
                ['technician_id', '=', $getIdCso],
                ['appointment', '=', $inputAppointment],
                ['id', '!=', $data['ts_id']],
            ])->get();

            if (count($getTechnicianSchedules) > 0) {
                return response()->json(['errors' => ['Technician' => "An appointment has been already scheduled."]]);
            }

            $technician_schedule = TechnicianSchedule::find($data['ts_id']);
            $technician_schedule->appointment = $data['date']." ".$data['time'];
            $technician_schedule->name = $data['name'];
            $technician_schedule->phone = $data['phone'];
            $technician_schedule->address = $data['address'];
            $technician_schedule->province = (int)$data['province_id'];
            $technician_schedule->city = $data['city'];
            $technician_schedule->district = $data['subDistrict'];
            $technician_schedule->technician_id = $getIdCso;
            $technician_schedule->d_o = $data['d_o'];
            $technician_schedule->save();

            if ($request->service_id != $request->service_id_old) {
                Service::where('id', $request->service_id_old)->update([
                    'technician_schedule_id' => null
                ]);
                Service::where('id', $request->service_id)->update([
                    'technician_schedule_id' => $data['ts_id']
                ]);
            }

            $get_allProductTs = json_decode($request->productservices);
            $get_oldProductTs = ProductTechnicianSchedule::where('technician_schedule_id', $data['ts_id'])->get();

            $appointmentBefore = TechnicianSchedule::find($data['ts_id']);
            foreach ($get_allProductTs as $key => $value) {
                if ($value[0] != null ) {
                    $product_ts = ProductTechnicianSchedule::find($value[0]);
                } else {
                    // Ada produk service baru
                    $product_ts = new ProductTechnicianSchedule();
                    $product_ts->technician_schedule_id = $data['ts_id'];
                }
                if ($value[1] != 'other') {
                    $product_ts->product_id = $value[1];
                } else {
                    $product_ts->product_id = null;
                    $product_ts->other_product = $value[5];
                }

                $data['arr_issues'] = [];
                $data['arr_issues'][0]['issues'] = $value[2];
                $data['arr_issues'][1]['desc'] = $value[3];
                $product_ts->issues = json_encode($data['arr_issues']);

                $product_ts->due_date = $value[4];
                if ($value[6] == "0") {
                    $product_ts->active = false;
                }
                $product_ts->save();
            }

            $user = Auth::user();
            $historyUpdate= [];
            $historyUpdate['type_menu'] = "Technician Schedule";
            $historyUpdate['method'] = "Update";
            $historyUpdate['meta'] = ['user'=>$user['id'],'createdAt' => date("Y-m-d h:i:s"), 'dateChange'=> $data, 'appointmentBefore'=>$appointmentBefore->appointment];
            $historyUpdate['user_id'] = $user['id'];
            $historyUpdate['menu_id'] = $data['ts_id'];
            $createData = HistoryUpdate::create($historyUpdate);
            DB::commit();
            return response()->json(['success' => "Berhasil", 'code'=>$technician_schedule['id']]);
        } catch (\Exception $ex) {
            DB::rollback();
            return response()->json(['errors' => $ex->getMessage()], 500);
        }
    }

    public function destroy(Request $request)
    {
        if (!empty($request->id)) {
            DB::beginTransaction();

            try {
                $services = TechnicianSchedule::find($request->id);
                $services->active = false;
                $services->save();

                DB::commit();

                return redirect()
                    ->route("list_technician_schedule")
                    ->with("success", "Data Technician Schedule berhasil dihapus.");
            } catch (Exception $e) {
                DB::rollback();

                return response()->json([
                    "error" => $e,
                ]);
            }
        }

        return response()->json(["error" => "Data tidak ditemukan."]);
    }

    public function export_to_xls(Request $request)
    {
        $city = null;
        $date = null;
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
        if($request->has('filter_cso') && $request->filter_cso != "undefined"){
            $csos = Cso::where('code', $request->filter_cso)->get();
            $cso = $csos[0]['id'];
        }
        
        return Excel::download(new TechnicianScheduleExport($date, $city, $cso, $search), 'Technician Schedule.xlsx');
    }
}
