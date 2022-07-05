<?php

namespace App\Http\Controllers;

use App\AbsentOff;
use App\Branch;
use App\Cso;
use App\HistoryUpdate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AbsentOffController extends Controller
{
    /**
     * @param App\AbsentOff $absentOff
     * @param App\Branc $branches
     * @param App\Cso $csos
     */
    private function checkAbsentOffBranchCso($absentOff = null, $branches = null, $csos = null)
    {
        // Jika user memiliki peran sebagai "branch" atau "area-manager"
        if (
            Auth::user()->roles[0]['slug'] === 'branch'
            || Auth::user()->roles[0]['slug'] === 'area-manager'
            ) {
            // Mengisi variabel $arrBranches dengan branch_id
            $arrBranches = [];
            foreach (Auth::user()->listBranches() as $value) {
                $arrBranches[] = $value["id"];
            }
            if ($absentOff) $absentOff->whereIn('absent_offs.branch_id', $arrBranches);
            if ($branches) $branches->whereIn('branches.id', $arrBranches);
            if ($csos) $csos->whereIn('csos.branch_id', $arrBranches);
        } else if (Auth::user()->roles[0]['slug'] === "cso") {
            // Jika user memiliki peran sebagai "cso"
            if ($absentOff) $absentOff->where('absent_offs.cso_id', Auth::user()->cso_id);
            if ($branches) $branches->where('branches.id', Auth::user()->cso->branch_id);
            if ($csos) $csos->where('csos.id', Auth::user()->cso_id);
        }
    }

    public function index(Request $request)
    {
        $branches = Branch::where('active', true);
        $csos = Cso::where('active', true);
        
        // Jika user memiliki peran sebagai "branch" atau "area-manager" atau "cso"
        $this->checkAbsentOffBranchCso(null, $branches, $csos);

        $branches = $branches->orderBy("code", 'asc')->get();
        $csos = $csos->orderBy('code', 'asc')->get();

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
        
        $temp_date = date("Y-m-d");

        // Mendapatkan count appointment
        $currentMonthDataCount = $this->getCountAppointment(
            $temp_date,
            $arrBranches,
            $request->filter_cso,
        );

        // Inisialisasi variabel $todayDate dan mengisi dengan tanggal hari ini
        $todayDate = date("Y-m-d");

        $currentDayData = $this->printDayData($request);
        $currentDayData = json_decode($currentDayData->getContent(), true)['msg'];


        return view(
            "admin.list_absentoff",
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
        $filterBranch = null,
        $filterCSO = null
    ) {
        // Inisialisasi tanggal awal bulan dari variabel $startDate
        $start = date("Y-m-01", strtotime($startdate));

        // Inisialisasi tanggal akhir bulan dari $startDate
        $end = date("Y-m-t", strtotime($startdate));

        $currentMonthDataCount = AbsentOff::select(
                'start_date', 
                'end_date',
                DB::raw("COUNT(id) AS data_count"),
            )
            ->where("status", AbsentOff::$status['2']);

        if (!empty($filterBranch)) {
            $currentMonthDataCount = $currentMonthDataCount->whereIn(
                "branch_id",
                $filterBranch
            );
        }

        if (!empty($filterCSO)) {
            $cso_id = Cso::where('code', $filterCSO)->first();

            $currentMonthDataCount = $currentMonthDataCount->where(
                "cso_id",
                $cso_id->id
            );
        }

        $currentMonthDataCount = $currentMonthDataCount
            ->where(function($query) use ($start, $end) {
                $query->where(function($query2) use ($start, $end) {
                    $query2->where('start_date', '<=', $start)
                    ->where('end_date', '>=', $end);
                });
                $query->orWhere(function($query2) use ($start, $end) {
                    $query2->whereBetween('start_date', [$start, $end])
                    ->orWhereBetween('end_date', [$start, $end]);
                });
            })
            ->groupBy('start_date', 'end_date')
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

        $currentMonthDataCount = $this->getCountAppointment(
            $request->date,
            $arrBranches,
            $request->filter_cso
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
                        $existDataCount = 0;
                        $fullDayWithZero = $year . "-" . $month . "-" . $dayWithZero;
                        foreach ($currentMonthDataCount as $key => $value) {
                            if ($fullDayWithZero >= $value->start_date && $fullDayWithZero <= $value->end_date) {
                               $existDataCount += $value->data_count;
                            }
                            if ($value->end_date == $fullDayWithZero) {
                                unset($currentMonthDataCount[$key]);
                            }
                        }
                        if ($existDataCount > 0) {
                            $result .= '<span class="cjslib-day-indicator cjslib-indicator-pos-bottom cjslib-indicator-type-numeric" '
                                . 'id="calendarContainer-day-indicator-' . $dayCounter . '">';
                            $result .= $existDataCount;
                            $result .= '</span>';
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
        $filterBranch = null,
        $filterCSO = null
    ) {
        $todayDate = date("Y-m-d", strtotime($requestedDate ?? "now"));

        $currentDayData = AbsentOff::select(
            "ao.id",
            "ao.start_date AS ao_start_date",
            "ao.end_date AS ao_end_date",
            "ao.duration_off AS ao_duration_off",
            "ao.status as ao_status",
            "b.code AS branch_code",
            "b.name AS branch_name",
            "c.code as cso_code",
            "c.name AS cso_name",
            "r.slug AS role_slug",
            "u2.name AS u2_name",
            "ao.created_at AS created_at",
            "ao.updated_at AS updated_at"
        )
        ->from("absent_offs AS ao")
        ->leftJoin(
            "branches AS b",
            "b.id",
            "=",
            "ao.branch_id"
        )
        ->leftJoin(
            "csos AS c",
            "c.id",
            "=",
            "ao.cso_id"
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
        ->leftJoin(
            "users as u2",
            "u2.id",
            "=",
            "ao.user_id"
        )
        ->where("ao.status", AbsentOff::$status['2']);

        if (!empty($filterBranch)) {
            $currentDayData = $currentDayData->whereIn(
                "ao.branch_id",
                $filterBranch
            );
        }

        if (!empty($filterCSO)) {
            $cso_id = Cso::where('code', $filterCSO)->first();

            $currentDayData = $currentDayData->where(
                "ao.cso_id",
                $cso_id->id
            );
        }

        $currentDayData = $currentDayData
            ->where('ao.start_date', '<=', $todayDate)
            ->where('ao.end_date', '>=', $todayDate)
            ->orderBy("ao.start_date", "ASC")
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
        
        $currentDayData = $this->getDayData(
            $request->date,
            $arrBranches,
            $request->filter_cso
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
                    . '<td style="text-align: left; font-weight: bold;">';

                $result .= 'Duration : ' . $dayData->ao_duration_off . ' days'
                    . '<br>' . date('d F Y', strtotime($dayData->ao_start_date)) 
                    . ' - ' . date('d F Y', strtotime($dayData->ao_end_date));

                $result .= '</td>'
                    . '<td>';

                $result .= '<p class="descAppoin">';

                $result .= $dayData->cso_name
                    . '<br>';

                $result .= 'Branch: '
                    . $dayData->branch_code . ' - ' . $dayData->branch_name
                    . '<br>'
                    . 'CSO: ' . $dayData->cso_code
                    . '<br>';

                $result .= 'Created at: ' . $dayData->created_at
                    . ' (by ' . $dayData->u2_name . ')'
                    . '<br class="break">';

                foreach ($dayData->historyUpdateAcc() as $historyUpdateAcc) {
                    if ($historyUpdateAcc['meta']['status_acc'] == "true") {
                        $result .= '<span style="color: green">'
                        . 'Approved : ' . $historyUpdateAcc['meta']['createdAt'] 
                        . ' (Approved by ' . $historyUpdateAcc['u_name'] . ')'
                        . '</span>';
                    } else {
                        $result .= '<span style="color: red">'
                        . 'Rejected : ' . $historyUpdateAcc['meta']['createdAt']
                        . ' (Rejected by ' . $historyUpdateAcc['u_name'] . ')'
                        . '</span>';
                    }
                    $result .= '<br>';
                }

                $result .= '</p>'
                    . '</td>';

                $result .= '<td style="text-align: center">';

                if(Auth::user()->hasPermission('detail-home_service')){
                    $result .= '<button '
                        . 'class="btnappoint btn-gradient-primary mdi mdi-eye btn-homeservice-view" '
                        . 'type="button" '
                        . 'data-toggle="modal" '
                        . 'data-target="#viewCutiModal" '
                        . 'onclick="clickView(this)" '
                        . 'value="' . $dayData->id . '">'
                        . '</button>';
                }
                else{
                    $result .= '</td>';
                }

                $result .= '</tr>';

                $i++;
            }
        }

        return response()->json(array(
            'status'=>'oke',
            'msg'=> $result
        ),200);
    }

    public function create()
    {
        $branches = Branch::where('active', true);
        $csos = Cso::where('active', true);
        
        // Jika user memiliki peran sebagai "branch" atau "area-manager" atau "cso"
        $this->checkAbsentOffBranchCso(null, $branches, $csos);

        $branches = $branches->orderBy("code", 'asc')->get();
        $csos = $csos->orderBy('code', 'asc')->get();

        return view('admin.add_absentoff', compact('branches', 'csos'));   
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'branch_id' => ['required', 'exists:branches,id'],
            'cso_id' => ['required', 'exists:csos,id'],
            'division' => 'required',
            'duration_work' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'duration_off' => 'required|integer',
            'work_on' => 'required',
            'desc' => 'required',
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
            $absentOff = new AbsentOff;
            $absentOff->branch_id = $request->branch_id;
            $absentOff->cso_id = $request->cso_id;
            $absentOff->division = $request->division;
            $absentOff->duration_work = $request->duration_work;
            $absentOff->start_date = $request->start_date;
            $absentOff->end_date = $request->end_date;
            $absentOff->duration_off = $request->duration_off;
            $absentOff->work_on = $request->work_on;
            $absentOff->desc = $request->desc;
            $absentOff->user_id = Auth::user()->id;
            $absentOff->save();

            return response()->json(['success' => "Berhasil"]);
        }
    }

    public function show(Request $request)
    {
        $absentOff = AbsentOff::where('id', $request->id);

        // Jika user memiliki peran sebagai "branch" atau "area-manager" atau "cso"
        $this->checkAbsentOffBranchCso($absentOff);
        $absentOff = $absentOff->firstorFail();
        
        return view('admin.detail_absentoff', compact('absentOff'));
    }

    public function detailResponse(Request $request)
    {
        if($request->has('id')){
            $absentOff = AbsentOff::where('id', $request->id);

            // Jika user memiliki peran sebagai "branch" atau "area-manager" atau "cso"
            $this->checkAbsentOffBranchCso($absentOff);
            $absentOff = $absentOff->firstorFail();
            $absentOff['cso_code'] = $absentOff->cso->code;
            $absentOff['cso_name'] = $absentOff->cso->name;
            $absentOff['branch_code'] = $absentOff->branch->code;
            $absentOff['branch_name'] = $absentOff->branch->name;
            $absentOff['date'] = date('d F Y', strtotime($absentOff->start_date)) . ' - ' . date('d F Y', strtotime($absentOff->end_date));
            $absentOff['work_on'] = date('d F Y', strtotime($absentOff->work_on));

            return response()->json(['result' => $absentOff]);
        } else {
            return response()->json(['result' => 'Gagal!!']);
        }
    }

    public function edit(Request $request)
    {
        if($request->has('id')){
            $branches = Branch::where('active', true);
            $csos = Cso::where('active', true);

            $absentOff = AbsentOff::where('id', $request->id)
                ->where('status', AbsentOff::$status['1'])
                ->whereNull('supervisor_id')
                ->whereNull('coordinator_id');
            
            // Jika user memiliki peran sebagai "branch" atau " area-manager" atau "cso
            $this->checkAbsentOffBranchCso($absentOff, $branches, $csos);
    
            $branches = $branches->orderBy("code", 'asc')->get();
            $csos = $csos->orderBy('code', 'asc')->get();
            $absentOff = $absentOff->firstorFail();

            return view('admin.update_absentoff', compact('branches', 'csos', 'absentOff'));
        }else{
            return response()->json([
                "result" => "0",
                "message" => "Data tidak ditemukan.",
            ], 400);        
        }
    }

    public function update(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'id' => 'required',
            'branch_id' => ['required', 'exists:branches,id'],
            'cso_id' => ['required', 'exists:csos,id'],
            'division' => 'required',
            'duration_work' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'duration_off' => 'required|integer',
            'work_on' => 'required',
            'desc' => 'required',
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
            $user = Auth::user();
            $absentOff = AbsentOff::where('id', $request->id);

            // Jika user memiliki peran sebagai "branch" atau "area-manager" atau "cso
            $this->checkAbsentOffBranchCso($absentOff);
            $absentOff = $absentOff->first();
            if (!$absentOff) return response()->json(["result" => "0", "message" => "Data tidak ditemukan.",], 400);

            $dataBefore = AbsentOff::find($request->id);

            $absentOff->branch_id = $request->branch_id;
            $absentOff->cso_id = $request->cso_id;
            $absentOff->division = $request->division;
            $absentOff->duration_work = $request->duration_work;
            $absentOff->start_date = $request->start_date;
            $absentOff->end_date = $request->end_date;
            $absentOff->duration_off = $request->duration_off;
            $absentOff->work_on = $request->work_on;
            $absentOff->desc = $request->desc;
            $absentOff->user_id = $user['id'];
            $absentOff->save();

            $historyUpdate= [];
            $historyUpdate['type_menu'] = "Absent Off";
            $historyUpdate['method'] = "Update";
            $historyUpdate['meta'] = [
                'user'=>$user['id'],
                'createdAt' => date("Y-m-d H:i:s"),
                'dataChange'=> array_diff(json_decode($absentOff, true), json_decode($dataBefore,true))
            ];
            $historyUpdate['user_id'] = $user['id'];
            $historyUpdate['menu_id'] = $absentOff->id;

            $createData = HistoryUpdate::create($historyUpdate);

            return response()->json(['success' => "Berhasil"]);
        }
    }

    public function destroy(Request $request)
    {
        if($request->has('id')){
            $absentOff = AbsentOff::where('id', $request->id)
                ->where('status', AbsentOff::$status['1'])
                ->whereNull('supervisor_id')
                ->whereNull('coordinator_id');
            
            // Jika user memiliki peran sebagai "branch" atau "area-manager" atau "cso
            $this->checkAbsentOffBranchCso($absentOff);
            $absentOff = $absentOff->firstorFail();

            $user = Auth::user();
            $historyUpdate= [];
            $historyUpdate['type_menu'] = "Absent Off";
            $historyUpdate['method'] = "Delete";
            $historyUpdate['meta'] = ['user'=>$user['id'],'createdAt' => date("Y-m-d H:i:s"), 'dataOld' => json_encode($absentOff)];
            $historyUpdate['user_id'] = $user['id'];
            $historyUpdate['menu_id'] = $request->id;
            $createData = HistoryUpdate::create($historyUpdate);

            $absentOff->delete();
            
            return redirect()->back()->with('success', 'Data berhasil dihapus');
        }else{
            return response()->json([
                "result" => "0",
                "message" => "Data tidak ditemukan.",
            ], 400);        
        }
    }

    public function indexAcc(Request $request)
    {
        $branches = Branch::where('active', true)->orderBy('code', 'asc')->get();
        $csos = Cso::where("active", true)->orderBy("code", 'asc')->get();

        $absentOffs = AbsentOff::orderBy("id", 'desc');
        $startDate = date('y-m-01');
        if ($request->has('filter_start_date')) {
            $startDate = date('Y-m-d', strtotime($request->filter_start_date));
        }
        $endDate = date('Y-m-t');
        if ($request->has('filter_end_date')) {
            $endDate = date('Y-m-d', strtotime($request->filter_end_date));
        }
        
        // Jika user memiliki peran sebagai "branch" atau "area-manager"
        $this->checkAbsentOffBranchCso($absentOffs);

        if ($request->has('filter_branch')) {
            $currentBranch = Branch::find($request->filter_branch);
            $absentOffs->where('branch_id', $currentBranch['id']);
        }
        if ($request->has('filter_cso')) {
            $currentCso = Cso::where("code", $request->filter_cso)->first();
            $absentOffs->where('cso_id', $currentCso['id']);
        }
        
        $absentOffs->where(function($query) use ($startDate, $endDate) {
            $query->where(function($query2) use ($startDate, $endDate) {
                $query2->where('start_date', '<=', $startDate)
                ->where('end_date', '>=', $endDate);
            });
            $query->orWhere(function($query2) use ($startDate, $endDate) {
                $query2->whereBetween('start_date', [$startDate, $endDate])
                ->orWhereBetween('end_date', [$startDate, $endDate]);
            });
        });
        
        $statusAbsentOffs = [];
        foreach (AbsentOff::$status as $status) {
            $statusAbsentOffs[$status] = clone $absentOffs;
            $statusAbsentOffs[$status] = $statusAbsentOffs[$status]
                ->where('status', $status)->paginate(10, ['*'], $status);
        }

        return view('admin.list_absentoff_acc', compact('branches', 'csos', 'startDate', 'endDate', 'statusAbsentOffs'));
    }

    public function updateAcc(Request $request)
    {
        $absentOff = AbsentOff::find($request->id);
        $user = Auth::user();
        if ($request->has('absentOffData')) { // reject all or approved all
            if (substr($request->absentOffData, 0, 4) == "true") {
                $aoID = explode(",", substr($request->absentOffData,5));
            } else {
                $aoID = explode(",", $request->absentOffData);
            }
            $aOID = AbsentOff::whereIn('id', $aoID)->get();
            $acc_cuti_type = $request->acc_cuti_type;
            foreach ($aOID as $absentOff) {
                if ($acc_cuti_type == "supervisor") {
                    $absentOff->supervisor_id = $user['id'];
                } else if ($acc_cuti_type == "coordinator") {
                    $absentOff->coordinator_id = $user['id'];
                }

                if ($request->status_acc == "false") {
                    $absentOff->status = "rejected";
                } else if ($absentOff->supervisor_id && $absentOff->coordinator_id) {
                    $before = HistoryUpdate::where([['type_menu', 'Absent Off Acc'], ['menu_id', $absentOff->id]])->orderBy('id', 'desc')->first();
                    if ($before) {
                        if ($request->status_acc == "true" && $before['meta']['status_acc'] == "true") {
                            $absentOff->status = "approved";
                        }
                    }
                }
                $absentOff->save();
    
                $historyUpdate= [];
                $historyUpdate['type_menu'] = "Absent Off Acc";
                $historyUpdate['method'] = "Update";
                $historyUpdate['meta'] = ['user'=>$user['id'],'createdAt' => date("Y-m-d H:i:s"), 'acc_cuti_type' => $acc_cuti_type, 'status_acc'=> $request->status_acc];
                $historyUpdate['user_id'] = $user['id'];
                $historyUpdate['menu_id'] = $absentOff->id;
    
                $createData = HistoryUpdate::create($historyUpdate);
            }

            return back()->with("success", "Acc Cuti berhasil diproses.");
        } else if ($absentOff) {
            $acc_cuti_type = $request->acc_cuti_type;
            if ($acc_cuti_type == "supervisor") {
                $absentOff->supervisor_id = $user['id'];
            } else if ($acc_cuti_type == "coordinator") {
                $absentOff->coordinator_id = $user['id'];
            }

            if ($request->status_acc == "false") {
                $absentOff->status = "rejected";
            }  else if ($absentOff->supervisor_id && $absentOff->coordinator_id) {
                $before = HistoryUpdate::where([['type_menu', 'Absent Off Acc'], ['menu_id', $absentOff->id]])->orderBy('id', 'desc')->first();
                if ($before) {
                    if ($request->status_acc == "true" && $before['meta']['status_acc'] == "true") {
                        $absentOff->status = "approved";
                    }
                }
            }
            $absentOff->save();

            $historyUpdate= [];
            $historyUpdate['type_menu'] = "Absent Off Acc";
            $historyUpdate['method'] = "Update";
            $historyUpdate['meta'] = ['user'=>$user['id'],'createdAt' => date("Y-m-d H:i:s"), 'acc_cuti_type' => $acc_cuti_type, 'status_acc'=> $request->status_acc];
            $historyUpdate['user_id'] = $user['id'];
            $historyUpdate['menu_id'] = $absentOff->id;

            $createData = HistoryUpdate::create($historyUpdate);

            return back()->with("success", "Acc Cuti berhasil diproses.");
        } else {
            return back()->with("error", "Acc Cuti gagal diproses.");
        }
    }
}
