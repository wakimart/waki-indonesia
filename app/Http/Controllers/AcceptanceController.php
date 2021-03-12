<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Acceptance;
use App\AcceptanceStatusLog;
use App\Branch;
use App\Cso;
use App\User;
use App\Utils;
use DB;

use App\Product;


class AcceptanceController extends Controller
{
    public function create()
    {
        $products = Product::all();
        $branches = Branch::where('active', true)->get();
        $csos = Cso::all();
    	return view('admin.add_acceptance', compact('products', 'branches', 'csos'));
    }

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