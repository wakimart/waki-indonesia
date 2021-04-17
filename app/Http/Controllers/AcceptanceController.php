<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Acceptance;
use App\AcceptanceStatusLog;
use App\Branch;
use App\Cso;
use App\User;
use App\Utils;
use App\Product;
use App\Upgrade;
use App\HistoryUpdate;
use App\Role;
use DB;


class AcceptanceController extends Controller
{
    public function create()
    {
        $products = Product::all();
        $branches = Branch::where('active', true)->get();
        $csos = Cso::all();
    	return view('admin.add_acceptance', compact('products', 'branches', 'csos'));
    }

    public function store(Request $request){
        $messages = array(
            'cso_id.required' => 'The CSO Code field is required.',
            'cso_id.exists' => 'Wrong CSO Code.',
            'branch_id.required' => 'The Branch must be selected.',
            'branch_id.exists' => 'Please choose the branch.'
        );
        $validator = \Validator::make($request->all(), [
            'name' => 'required',
            'cso_id' => ['required', 'exists:csos,code'],
            'branch_id' => ['required', 'exists:branches,id'],
            'phone' => 'required|string|min:7',
        ], $messages);

        if($validator->fails()){
            $arr_Errors = $validator->errors()->all();
            $arr_Keys = $validator->errors()->keys();
            $arr_Hasil = [];
            for ($i = 0; $i < count($arr_Keys); $i++) {
                $arr_Hasil[$arr_Keys[$i]] = $arr_Errors[$i];
            }
            return response()->json(['errors' => $arr_Hasil]);
        }
        else {
            DB::beginTransaction();
             try{
                $data = $request->all();
                $branch = Branch::find($data['branch_id']);
                $cso = Cso::where('code', $data['cso_id'])->first();
                $data['branch_id'] = $branch['id'];
                $data['cso_id'] = $cso['id'];
                $data['user_id'] = Auth::user()['id'];
                $data['status'] = "new";
                $data['code'] = "ACC/UPGRADE/".$branch->code."/".$data['cso_id']."/".date("Ymd");
                if(in_array('other', $data['kelengkapan'])){
                    $data['kelengkapan']['other'][] = $data['other_kelengkapan'];
                }
                $data['arr_condition'] = ['kelengkapan' => $data['kelengkapan'], 'kondisi' => $data['kondisi'], 'tampilan' => $data['tampilan']];

                if ($request->hasFile("image")) {
                    $data['image'] = [];
                    $path = "sources/acceptance";
                    $idxImg = 1;

                    if (!is_dir($path)) {
                        File::makeDirectory($path, 0777, true, true);
                    }

                    foreach ($request->file("image") as $imgNya) {
                        $fileName = str_replace([' ', ':'], '', Carbon::now()->toDateTimeString()). $idxImg . "_upgrade." . $imgNya->getClientOriginalExtension();
                        $imgNya->move($path, $fileName);
                        array_push($data['image'], $fileName);
                        $idxImg++;
                    }
                }

                $acc = Acceptance::create($data);

                $accStatusLog['acceptance_id'] = $acc->id;
                $accStatusLog['user_id'] =  $data['user_id'];
                $accStatusLog['status'] = "new";
                $acceptanceStatusLog = AcceptanceStatusLog::create($accStatusLog);

                DB::commit();

                $this->sendNotif($acc);                
                return response()->json(['success' => $acc]);
            } catch (\Exception $ex) {
                DB::rollback();
                return response()->json(['errors' => $ex], 500);
            }
        }
    }

    public function list(Request $request){
        $url = $request->all();
        $acceptances = Acceptance::where('active', true);
        if(isset($url['status'])){
            $acceptances = $acceptances->where('status', $url['status']);
        }
        if (Auth::user()->roles[0]['slug'] === "cso") {
            $acceptances = $acceptances->where('cso_id', Auth::user()->cso["id"]);
        }
        else if(Auth::user()->roles[0]['slug'] === 'branch' || Auth::user()->roles[0]['slug'] === 'area-manager') {
            $acceptances = $acceptances->WhereIn('branch_id', json_decode(Auth::user()['branches_id'], true));
        }
        $acceptances = $acceptances->orderBy('id', 'DESC')->paginate(10);
        return view('admin.list_acceptance', compact('acceptances', 'url'));
    }

    public function detail($id){
        $acceptance = Acceptance::find($id);
        $historyUpdate = HistoryUpdate::where([['type_menu', 'Acceptance'], ['method', 'Update'], ['menu_id', $id]])->get();
        return view('admin.detail_acceptance', compact('acceptance', 'historyUpdate'));
    }

    public function edit($id){        
        $acceptance = Acceptance::find($id);
        $products = Product::all();
        $branches = Branch::where('active', true)->get();
        $csos = Cso::all();
        // dd(date_format(date_create($acceptance['upgrade_date']), 'm-d-Y'));
        return view('admin.update_acceptance', compact('acceptance','products', 'branches', 'csos'));
    }

    public function update(Request $request){
        $data = $request->all();
        if(isset($data['status'])){
            DB::beginTransaction();
            try{
                $acceptance = Acceptance::find($data['id']);
                $acceptance['status'] = $data['status'];
                $acceptance->save();


                $accStatusLog['acceptance_id'] = $data['id'];
                $accStatusLog['user_id'] =  Auth::user()['id'];
                $accStatusLog['status'] =  $data['status'];
                $acceptanceStatusLog = AcceptanceStatusLog::create($accStatusLog);

                if(strtolower($data['status']) == "approved"){
                    $upgrade['acceptance_id'] = $data['id'];
                    $upgrade['status'] = "New";
                    $upgrade['due_date'] = date("Y-m-d H:i:s");
                    Upgrade::create($upgrade);
                }

                $historyUpdate['type_menu'] = "Acceptance";
                $historyUpdate['method'] = "Update";
                $historyUpdate["meta"] = json_encode(["dataChange" => $acceptance->getChanges()]);
                $historyUpdate['user_id'] = Auth::user()['id'];
                $historyUpdate['menu_id'] = $acceptance->id;
                $createData = HistoryUpdate::create($historyUpdate);

                DB::commit();
                return redirect()->route('detail_acceptance_form', ['id'=>$data['id']]);
            }
            catch (\Exception $ex) {
                DB::rollback();
                return redirect()->route('detail_acceptance_form', ['id'=>$data['id']]);
            }
        }
        else{
            $messages = array(
                'cso_id.required' => 'The CSO Code field is required.',
                'cso_id.exists' => 'Wrong CSO Code.',
                'branch_id.required' => 'The Branch must be selected.',
                'branch_id.exists' => 'Please choose the branch.'
            );
            $validator = \Validator::make($request->all(), [
                'name' => 'required',
                'cso_id' => ['required', 'exists:csos,code'],
                'branch_id' => ['required', 'exists:branches,id'],
                'phone' => 'required|string|min:7',
            ], $messages);

            if($validator->fails()){
                $arr_Errors = $validator->errors()->all();
                $arr_Keys = $validator->errors()->keys();
                $arr_Hasil = [];
                for ($i = 0; $i < count($arr_Keys); $i++) {
                    $arr_Hasil[$arr_Keys[$i]] = $arr_Errors[$i];
                }
                return response()->json(['errors' => $arr_Hasil]);
            }
            else{
                DB::beginTransaction();
                try{
                    $acceptance = Acceptance::find($data['id']);

                    $branch = Branch::find($data['branch_id']);
                    $cso = Cso::where('code', $data['cso_id'])->first();
                    $data['branch_id'] = $branch['id'];
                    $data['cso_id'] = $cso['id'];
                    $data['user_id'] = Auth::user()['id'];
                    $data['status'] = "new";
                    $data['code'] = "ACC/UPGRADE/".$branch->code."/".$data['cso_id']."/".date("Ymd");
                    if(in_array('other', $data['kelengkapan'])){
                        $data['kelengkapan']['other'][] = $data['other_kelengkapan'];
                    }
                    $data['arr_condition'] = ['kelengkapan' => $data['kelengkapan'], 'kondisi' => $data['kondisi'], 'tampilan' => $data['tampilan']];

                    if ($request->hasFile("image")) {
                        $image = $request->file("image");
                        $data['image'] = [];
                        $path = "sources/acceptance";

                        if (!is_dir($path)) {
                            File::makeDirectory($path, 0777, true, true);
                        }

                        $fileName = str_replace([' ', ':'], '', Carbon::now()->toDateTimeString()) . "_upgrade." . $image->getClientOriginalExtension();
                        $image->move($path, $fileName);
                        $data["image"] = [$fileName];
                    }

                    $acceptance->fill($data)->save();

                    $historyUpdate['type_menu'] = "Acceptance";
                    $historyUpdate['method'] = "Update";
                    $historyUpdate["meta"] = json_encode(["dataChange" => $acceptance->getChanges()]);
                    $historyUpdate['user_id'] = Auth::user()['id'];
                    $historyUpdate['menu_id'] = $acceptance->id;
                    $createData = HistoryUpdate::create($historyUpdate);
                    
                    DB::commit();
                    return response()->json(['success' => $acceptance]);
                }
                catch (\Exception $ex) {
                    DB::rollback();
                    return response()->json(['errors' => $ex], 500);
                }
            }
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $acceptance = Acceptance::find($id);
            $acceptance['active'] = false;
            $acceptance->save();

            $historyUpdate['type_menu'] = "Acceptance";
            $historyUpdate['method'] = "Update";
            $historyUpdate["meta"] = json_encode(["dataChange" => $acceptance->getChanges()]);
            $historyUpdate['user_id'] = Auth::user()['id'];
            $historyUpdate['menu_id'] = $acceptance->id;
            $createData = HistoryUpdate::create($historyUpdate);

            DB::commit();
            return redirect()->route('list_acceptance_form');
        }
        catch (Exception $e) {
            DB::rollback();
            return redirect()->route('list_acceptance_form');
        }
    }

    //================ Khusus Notifikasi ================//
    function sendFCM($body){
        
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Authorization: key=AAAAfcgwZss:APA91bGg7XK9XjDvLLqR36mKsC-HwEx_l5FPGXDE3bKiysfZ2yzUKczNcAuKED6VCQ619Q8l55yVh4VQyyH2yyzwIJoVajaK4t3TJV-x-4f_a9WUzIcnOYzixPIUB5DeuWRIAh1v8Yld',
            'Content-Type: application/json'
        ));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $body);

        $result = curl_exec($curl);
        if ($result === FALSE) {
            die('Oops! FCM Send Error: ' . curl_error($curl));
            $this->info(curl_error($curl));
        }
        curl_close($curl);
        return $result;
    }

    public function sendNotif($acceptance){
        $fcm_tokenNya = [];
        $userNya = Role::where('slug', 'head-admin')->first()->users;
        foreach ($userNya as $value) {
            if($value['fmc_token'] != null){
                $fcm_tokenNya = array_merge($fcm_tokenNya, $value['fmc_token']);
            }
        }

        $newProduct = $acceptance->newproduct['code'];
        $oldProduct = $acceptance['other_product'];
        if($oldProduct == null){
            $oldProduct = $acceptance->oldproduct['code'];
        }
        $branch = $acceptance->branch['code'];
        $cso = $acceptance->cso['code'];

        $body = ['registration_ids'=>$fcm_tokenNya,
            'collapse_key'=>"type_a",
            "notification" => [
                "body" => "Upgrade from ".$oldProduct." to ".$newProduct.". By ".$branch."-".$cso,
                "title" => "New Acceptance [Upgrade]",
                "icon" => "ic_launcher"
            ],
            "data" => [
                "url" => URL::to(route('detail_acceptance_form', ['id'=>$acceptance['id']])),
                "branch_cso" => $branch."-".$cso,
                "product" => $oldProduct." to ".$newProduct,
                "price" => "Rp. ".number_format($acceptance->request_price),
            ]];
            // 'data'=> $homeservice];

        $this->sendFCM(json_encode($body));
    }
    //===================================================//

    public function addApi(Request $request)
    {
        $messages = [
            'cso_id.required' => 'The CSO Code field is required.',
            'cso_id.exists' => 'Wrong CSO Code.',
            'branch_id.required' => 'The Branch must be selected.',
            'branch_id.exists' => 'Please choose the branch.'
        ];

        $validator = \Validator::make($request->all(), [
            'name' => 'required',
            'cso_id' => ['required', 'exists:csos,code'],
            'branch_id' => ['required', 'exists:branches,id']
        ], $messages);

        if ($validator->fails()) {
            $data = [
                'result' => 0,
                'data' => $validator->errors(),
            ];

            return response()->json($data, 401);
        } else {
            DB::beginTransaction();

            try{
                $data = $request->all();
                $branch = Branch::find($data['branch_id']);
                $data["branch_id"] = $branch["id"];
                $data['code'] = "ACC/UPGRADE/"
                    . $branch->code
                    . "/"
                    . $data['cso_id']
                    . "/"
                    . date("Ymd");
                $data['cso_id'] = Cso::where('code', $data['cso_id'])->first()['id'];
                $data["status"] = "new";

                if (in_array('other', $data['kelengkapan'])) {
                    $data['kelengkapan']['other'][] = $data['other_kelengkapan'];
                }

                $data['arr_condition'] = [
                    'kelengkapan' => $data['kelengkapan'],
                    'kondisi' => $data['kondisi'],
                    'tampilan' => $data['tampilan'],
                ];

                if ($request->hasFile("image")) {
                    $image = $request->file("image");
                    $data['image'] = [];
                    $path = "sources/acceptance";

                    if (!is_dir($path)) {
                        File::makeDirectory($path, 0777, true, true);
                    }

                    $fileName = str_replace(
                        [' ', ':'],
                        '',
                        Carbon::now()->toDateTimeString()
                    )
                        . "_upgrade."
                        . $image->getClientOriginalExtension();

                    $image->move($path, $fileName);

                    $data["image"] = [$fileName];
                }

                $acceptance = Acceptance::create($data);

                $accStatusLog['acceptance_id'] = $acceptance->id;
                $accStatusLog['user_id'] =  $data['user_id'];
                $accStatusLog["status"] = "new";
                AcceptanceStatusLog::create($accStatusLog);

                $upgrade["acceptance_id"] = $acceptance->id;
                $upgrade["status"] = "new";
                $upgrade["due_date"] = $acceptance["created_at"];
                Upgrade::create($upgrade);

                DB::commit();

                $acceptanceView = $this->getDetail($acceptance->id, true);

                $data = [
                    'result' => 1,
                    'data' => $acceptanceView,
                ];

                return response()->json($data, 200);
            } catch (\Exception $ex) {
                DB::rollback();

                return response()->json(['error' => $ex->getMessage()], 500);
            }
        }
    }

    public function updateApi(Request $request)
    {
        $messages = [
            'cso_id.required' => 'The CSO Code field is required.',
            'cso_id.exists' => 'Wrong CSO Code.',
            'branch_id.required' => 'The Branch must be selected.',
            'branch_id.exists' => 'Please choose the branch.'
        ];
        $validator = \Validator::make($request->all(), [
            'name' => 'required',
            'cso_id' => ['required', 'exists:csos,code'],
            'branch_id' => ['required', 'exists:branches,id'],
            'phone' => 'required|string|min:7',
        ], $messages);

        if ($validator->fails()) {
            $data = [
                'result' => 0,
                'data' => $validator->errors(),
            ];

            return response()->json($data, 401);
        } else {
            DB::beginTransaction();

            try{
                $data = $request->all();

                $acceptance = Acceptance::find($data['id']);

                $acceptance["no_mpc"] = $data["no_mpc"];
                $acceptance["name"] = $data["name"];
                $acceptance["address"] = $data["address"];
                $acceptance["phone"] = $data["phone"];
                $acceptance["upgrade_date"] = $data["upgrade_date"];
                $acceptance["newproduct_id"] = $data["newproduct_id"];
                $acceptance["purchase_date"] = $data["purchase_date"];

                if(in_array('other', $data['kelengkapan'])){
                    $data['kelengkapan']['other'][] = $data['other_kelengkapan'];
                }
                $data['arr_condition'] = [
                    'kelengkapan' => $data['kelengkapan'],
                    'kondisi' => $data['kondisi'],
                    'tampilan' => $data['tampilan']
                ];

                $acceptance["arr_condition"] = $data["arr_condition"];
                $acceptance["request_price"] = $data["request_price"];
                $acceptance["description"] = $data["description"];

                if ($request->hasFile("image")) {
                    $image = $request->file("image");
                    $data['image'] = [];
                    $path = "sources/acceptance";

                    if (!is_dir($path)) {
                        File::makeDirectory($path, 0777, true, true);
                    }

                    $fileName = str_replace(
                        [' ', ':'],
                        '',
                        Carbon::now()->toDateTimeString()
                    )
                        . "_upgrade."
                        . $image->getClientOriginalExtension();

                    $image->move($path, $fileName);
                    $data["image"] = [$fileName];
                }

                $acceptance["image"] = $data["image"];

                $branch = Branch::find($data['branch_id']);
                $cso = Cso::where('code', $data['cso_id'])->first();

                $acceptance["branch_id"] = $branch["id"];
                $acceptance["cso_id"] = $cso["id"];
                $acceptance['status'] = $data['status'];
                $acceptance["province"] = $data["province"];
                $acceptance["city"] = $data["city"];
                $acceptance["district"] = $data["district"];

                if (
                    isset($data["other_product"])
                    && !empty($data["other_product"])
                ) {
                    $acceptance["other_product"] = $data["other_product"];
                }

                if (
                    isset($data["oldproduct_id"])
                    && !empty($data["oldproduct_id"])
                ) {
                    $acceptance["oldproduct_id"] = $data["oldproduct_id"];
                }

                $acceptance->save();

                $accStatusLog['acceptance_id'] = $data['id'];
                $accStatusLog['user_id'] =  $data["user_id"];
                $accStatusLog['status'] =  $data['status'];
                $acceptanceStatusLog = AcceptanceStatusLog::create($accStatusLog);

                if(strtolower($data['status']) == "approved"){
                    $upgrade['acceptance_id'] = $data['id'];
                    $upgrade['status'] = "new";
                    $upgrade['due_date'] = date("Y-m-d H:i:s");
                    Upgrade::create($upgrade);
                }

                $historyUpdate['type_menu'] = "Acceptance";
                $historyUpdate['method'] = "Update";
                $historyUpdate["meta"] = json_encode(
                    [
                        "user" => $data["user_id"],
                        "createdAt" => date("Y-m-d H:i:s"),
                        "dataChange" => $acceptance->getChanges(),
                    ]
                );
                $historyUpdate['user_id'] = $data["user_id"];
                $historyUpdate['menu_id'] = $acceptance->id;
                HistoryUpdate::create($historyUpdate);

                $viewAcceptanceUpdate = $this->getDetail($data["id"]);

                DB::commit();

                $data = [
                    'result' => 1,
                    'data' => $viewAcceptanceUpdate,
                ];

                return response()->json($data, 200);
            } catch (\Exception $ex) {
                DB::rollback();

                return response()->json(
                    [
                        'error' => $ex,
                        "error line" => $ex->getLine(),
                        "error messages" => $ex->getMessage(),
                    ],
                    500
                );
            }
        }
    }
    
    public function listApi(Request $request)
    {
        $messages = [
            'user_id.required' => 'There\'s an error with the data.',
            'user_id.exists' => 'There\'s an error with the data.'
        ];

        $validator = \Validator::make($request->all(), [
            'user_id' => ['required', 'exists:users,id'],
        ], $messages);

        if ($validator->fails()) {
            $data = [
                'result' => 0,
                'data' => $validator->errors(),
            ];

            return response()->json($data, 401);
        } else {
            try {
                $data = $request->all();
                $userNya = User::find($data['user_id']);

                // Khusus head-manager, head-admin, admin
                $acceptance = Acceptance::where('acceptances.active', true);

                // Khusus akun CSO
                if($userNya->roles[0]['slug'] == 'cso'){
                    $csoIdUser = $userNya->cso['id'];
                    $acceptance = Acceptance::where(
                        [
                            ['acceptances.active', true],
                            ['acceptances.cso_id', $csoIdUser],
                        ]
                    );
                }

                // Khusus akun branch dan area-manager
                if (
                    $userNya->roles[0]['slug'] == 'branch'
                    || $userNya->roles[0]['slug'] == 'area-manager'
                ) {
                    $arrbranches = [];

                    foreach ($userNya->listBranches() as $value) {
                        array_push($arrbranches, $value['id']);
                    }

                    $acceptance = Acceptance::WhereIn(
                        'acceptances.branch_id',
                        $arrbranches
                    )
                    ->where('acceptances.active', true);
                }

                $acceptance = $acceptance->select(
                    'acceptances.id AS id',
                    'acceptances.code AS code',
                    'acceptances.name AS name',
                    'acceptances.status AS status',
                    DB::raw("DATE_FORMAT(acceptances.created_at, '%d/%m/%Y') AS acceptance_date"),
                    'branches.code AS branch_code',
                    'csos.code AS cso_code',
                    "np.code AS new_product",
                    "op.name AS old_product",
                    "acceptances.other_product AS other_product"
                )
                ->leftJoin(
                    'branches',
                    'acceptances.branch_id',
                    '=',
                    'branches.id'
                )
                ->leftJoin(
                    'csos',
                    'acceptances.cso_id',
                    '=',
                    'csos.id'
                )
                ->leftJoin(
                    "products AS np",
                    "acceptances.newproduct_id",
                    "=",
                    "np.id"
                )
                ->leftJoin(
                    "products AS op",
                    "acceptances.oldproduct_id",
                    "=",
                    "op.id"
                )
                ->get();

                $data = [
                    'result' => count($acceptance),
                    'data' => $acceptance
                ];

                return response()->json($data, 200);
            } catch (\Exception $e) {
                $data = [
                    "result" => 0,
                    "error" => $e,
                    "error line" => $e->getLine(),
                    "error message" => $e->getMessage(),
                ];

                return response()->json($data, 500);
            }
        }
    }

    public function viewApi(Request $request, $id)
    {
        $acceptance = $this->getDetail($id);

        return response()->json($acceptance, 200);
    }

    private function getDetail($id, $isNew = null)
    {
        $acceptance = Acceptance::select(
            'acceptances.id AS id',
            'acceptances.code AS code',
            'acceptances.name AS name',
            "acceptances.address AS address",
            "acceptances.phone AS phone",
            DB::raw("DATE_FORMAT(acceptances.upgrade_date, '%d/%m/%Y') AS upgrade_date"),
            "np.code AS new_product_code",
            "np.name AS new_product_name",
            DB::raw("DATE_FORMAT(acceptances.purchase_date, '%d/%m/%Y') AS purchase_date"),
            "acceptances.arr_condition AS arr_condition",
            "acceptances.request_price AS request_price",
            'acceptances.description AS description',
            "acceptances.image AS image",
            'branches.code AS branch_code',
            'branches.name AS branch_name',
            'csos.code AS cso_code',
            'csos.name AS cso_name',
            'acceptances.status AS status',
            DB::raw("DATE_FORMAT(acceptances.created_at, '%d/%m/%Y') AS acceptance_date"),
            "raja_ongkir__cities.province AS province_name",
            DB::raw("CONCAT(raja_ongkir__cities.type, ' ', raja_ongkir__cities.city_name) AS city_name"),
            "raja_ongkir__subdistricts.subdistrict_name AS district_name",
            "acceptances.other_product AS other_product",
            "op.code AS old_product_code",
            "op.name AS old_product_name"
        )
        ->leftJoin(
            "products AS np",
            "acceptances.newproduct_id",
            "=",
            "np.id"
        )
        ->leftJoin(
            'branches',
            'acceptances.branch_id',
            '=',
            'branches.id'
        )
        ->leftJoin(
            'csos',
            'acceptances.cso_id',
            '=',
            'csos.id'
        )
        ->leftJoin(
            "raja_ongkir__cities",
            "raja_ongkir__cities.city_id",
            "=",
            "acceptances.city"
        )
        ->leftJoin(
            "raja_ongkir__subdistricts",
            "raja_ongkir__subdistricts.subdistrict_id",
            "=",
            "acceptances.district"
        )
        ->leftJoin(
            "products AS op",
            "acceptances.oldproduct_id",
            "=",
            "op.id"
        )
        ->where('acceptances.active', true)
        ->where("acceptances.id", $id)
        ->first();

        $acceptance->kelengkapan = $acceptance->arr_condition["kelengkapan"];

        unset($acceptance->arr_condition);

        $statusLog = AcceptanceStatusLog::select(
            'users.name AS user_name'
        )
        ->leftjoin(
            'users',
            'acceptance_status_logs.user_id',
            '=',
            'users.id'
        )
        ->where('acceptance_status_logs.acceptance_id', $id);

        if ($isNew) {
            $statusLog = $statusLog->where("acceptance_status_logs.status", "new");
        } else {
            $statusLog = $statusLog->where("acceptance_status_logs.status", "!=", "new");
        }

        $statusLog = $statusLog->first();

        $acceptance['status_by'] = $statusLog->user_name;

        return $acceptance;
    }

    public function deleteApi(Request $request)
    {
        $messages = [
            'id.required' => 'There\'s an error with the data.',
            'id.exists' => 'There\'s an error with the data.'
        ];

        $validator = \Validator::make($request->all(), [
            'id' => ['required', 'exists:home_services,id,active,1']
        ], $messages);

        if ($validator->fails()) {
            $data = [
                'result' => 0,
                'data' => $validator->errors(),
            ];

            return response()->json($data, 401);
        } else {
            DB::beginTransaction();

            try {
                $acceptance = Acceptance::find($request->id);
                $acceptance->active = false;
                $acceptance->save();

                $historyDeleteAcceptance = [];
                $historyDeleteAcceptance["type_menu"] = "Acceptance";
                $historyDeleteAcceptance["method"] = "Delete";
                $historyDeleteAcceptance["meta"] = json_encode(
                    [
                        "user" => $request->user_id,
                        "createdAt" => date("Y-m-d H:i:s"),
                        "dataChange" => $acceptance->getChanges(),
                    ]
                );
                $historyDeleteAcceptance["user_id"] = $request->user_id;
                $historyDeleteAcceptance["menu_id"] = $request->id;

                HistoryUpdate::create($historyDeleteAcceptance);

                DB::commit();

                $data = [
                    'result' => 1,
                    'data' => $acceptance,
                ];

                return response()->json($data, 200);
            } catch (\Exception $e) {
                DB::rollback();

                $data =[
                    "result" => 0,
                    "error" => $e,
                    "error line" => $e->getLine(),
                    "error message" => $e->getMessage(),
                ];

                return response()->json($data, 500);
            }
        }
    }
}
