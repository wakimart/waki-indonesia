<?php

namespace App\Http\Controllers;

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
            $acceptance = Acceptance::find($data['id']);
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
            ]];
            // 'data'=> $homeservice];

        $this->sendFCM(json_encode($body));
    }
    //===================================================//

    public function addApi(Request $request)
    {
        $messages = array(
            'cso_id.required' => 'The CSO Code field is required.',
            'cso_id.exists' => 'Wrong CSO Code.',
            'branch_id.required' => 'The Branch must be selected.',
            'branch_id.exists' => 'Please choose the branch.'
        );
        $validator = \Validator::make($request->all(), [
            'name' => 'required',
            'cso_id' => ['required', 'exists:csos,code'],
            'branch_id' => ['required', 'exists:branches,id']
        ], $messages);

        if ($validator->fails()){
            $data = ['result' => 0,
                     'data' => $validator->errors()
                    ];
            return response()->json($data, 401);
        }else {
            DB::beginTransaction();
            try{
                $data = $request->all();
                $branch = Branch::find($data['branch_id']);
                $data['code'] = "ACC/".$branch->code."/".$data['cso_id']."/".date("Ymd");
                $data['cso_id'] = Cso::where('code', $data['cso_id'])->first()['id'];
                $data['user_id'] = $data['user_id'];
                $acceptance = Acceptance::create($data);

                $accStatusLog['acceptance_id'] = $acceptance->id;
                $accStatusLog['user_id'] =  $data['user_id'];
                $acceptanceStatusLog = AcceptanceStatusLog::create($accStatusLog);
                DB::commit();
                $data = ['result' => 1,
                        'data' => $acceptance
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
        $messages = array(
            'cso_id.required' => 'The CSO Code field is required.',
            'cso_id.exists' => 'Wrong CSO Code.',
            'branch_id.required' => 'The Branch must be selected.',
            'branch_id.exists' => 'Please choose the branch.'
        );
        $validator = \Validator::make($request->all(), [
            'name' => 'required',
            'cso_id' => ['required', 'exists:csos,code'],
            'branch_id' => ['required', 'exists:branches,id']
        ], $messages);

        if ($validator->fails()){
            $data = ['result' => 0,
                     'data' => $validator->errors()
                    ];
            return response()->json($data, 401);
        }else {
            DB::beginTransaction();
            try{
                $data = $request->all();
                $data['cso_id'] = Cso::where('code', $data['cso_id'])->first()['id'];
                $data['branch_id'] = Branch::where('id', $data['branch_id'])->first()['id'];
                $data['description'] = $data['description'];
                $data['status'] = $data['status'];
                $acceptance = Acceptance::find($data['id']);
                $acceptance->fill($data)->save();

                if ($data['status']!= ''){
                    $accStatusLog['acceptance_id'] = $acceptance->id;
                    $accStatusLog['status'] = $data['status'];
                    $accStatusLog['user_id'] =  $data['user_id'];
                    $acceptanceStatusLog = AcceptanceStatusLog::create($accStatusLog);
                    
                    
                }
                DB::commit();
                
                $data = ['result' => 1,
                        'data' => $acceptance
                        ];
                return response()->json($data, 200);
            } catch (\Exception $ex) {
                DB::rollback();
                return response()->json(['error' => $ex->getMessage()], 500);
            }
            
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
            $acceptance = Acceptance::find($request->id);
            $acceptance->active = false;
            $acceptance->save();

            $data = ['result' => 1,
                     'data' => $acceptance
                    ];
            return response()->json($data, 200);
        }
    }

    public function listApi(Request $request)
    {
        $messages = array(
                'user_id.required' => 'There\'s an error with the data.',
                'user_id.exists' => 'There\'s an error with the data.'
            );

        $validator = \Validator::make($request->all(), [
            'user_id' => ['required', 'exists:users,id'],
        ], $messages);

        if ($validator->fails()){
            $data = ['result' => 0,
                     'data' => $validator->errors()
                    ];
            return response()->json($data, 401);
        }
        else{
            $data = $request->all();
            $userNya = User::find($data['user_id']);

            //khususu head-manager, head-admin, admin
            $acceptance = Acceptance::where('acceptance.active', true);

            //khusus akun CSO
            if($userNya->roles[0]['slug'] == 'cso'){
                $csoIdUser = $userNya->cso['id'];
                $acceptance = Acceptance::where([['acceptance.active', true], ['acceptance.cso_id', $csoIdUser]]);
            }

            //khusus akun branch dan area-manager
            if($userNya->roles[0]['slug'] == 'branch' || $userNya->roles[0]['slug'] == 'area-manager'){
                $arrbranches = [];
                foreach ($userNya->listBranches() as $value) {
                    array_push($arrbranches, $value['id']);
                }
                $acceptance = Acceptance::WhereIn('acceptance.branch_id', $arrbranches)->where('acceptance.active', true);
            }

            $acceptance = $acceptance->leftjoin('branches', 'acceptance.branch_id', '=', 'branches.id')
                                ->leftjoin('csos', 'acceptance.cso_id', '=', 'csos.id')
                                ->select('acceptance.id', 'acceptance.no_do','acceptance.code', 'acceptance.name as customer_name', 'acceptance.description as description', 'acceptance.status as status','branches.code as branch_code', 'branches.name as branch_name', 'csos.code as csos_code', 'csos.name as csos_name')
                                ->get();
            
            $data = ['result' => count($acceptance),
                     'data' => $acceptance
                    ];
            return response()->json($data, 200);
        }        
    }

    public function viewApi(Request $request, $id)
    {
        $acceptance = Acceptance::where('acceptance.active', true);
        $acceptance = $acceptance->leftjoin('branches', 'acceptance.branch_id', '=', 'branches.id')
                                ->leftjoin('csos', 'acceptance.cso_id', '=', 'csos.id')
                                ->select('acceptance.id', 'acceptance.code','acceptance.no_do', 'acceptance.name as customer_name','acceptance.status as status', 'acceptance.description as description','branches.code as branch_code', 'branches.name as branch_name', 'csos.code as csos_code', 'csos.name as csos_name')
                                ->get();

        $acceptance = $acceptance->find($id);
        $statusLog= AcceptanceStatusLog::leftjoin('users', 'acceptance_status_log.user_id', '=', 'users.id')
                    ->select('acceptance_status_log.acceptance_id', 'acceptance_status_log.status', 'acceptance_status_log.user_id', 'users.name', "acceptance_status_log.created_at")
                    ->where('acceptance_id', $id)
                    ->get();
        $acceptance['status_log'] = $statusLog;
        return response()->json($acceptance, 200);

    }
}