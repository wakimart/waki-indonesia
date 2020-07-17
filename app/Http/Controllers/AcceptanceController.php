<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Acceptance;
use App\Branch;
use App\Cso;
use App\User;
use App\Utils;




class AcceptanceController extends Controller
{
    public function index()
    {
    	// 
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
            $data = $request->all();
            $branch = Branch::find($data['branch_id']);
            $cso = Cso::find($data['cso_id']);
            $data['code'] = "ACC/".$branch->code."/".$cso->code."/".strtotime(date("Ymd"));
            $acceptance = Acceptance::create($data);

            $data = ['result' => 1,
                     'data' => $homeservice
                    ];
            return response()->json($data, 200);
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
            $data = $request->all();
            $data['cso_id'] = Cso::where('code', $data['cso_id'])->first()['id'];
            $data['branch_id'] = Branch::where('code', $data['cso_id'])->first()['id'];
            $data['description'] = $data['description'];
            $acceptance = Acceptance::find($data['id']);
            $acceptance->fill($data)->save();
            $data = ['result' => 1,
                     'data' => $acceptance
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
                                ->select('acceptance.id', 'acceptance.code', 'acceptance.name as customer_name', 'acceptance.description as description','branches.code as branch_code', 'branches.name as branch_name', 'csos.code as csos_code', 'csos.name as csos_name')
                                ->get();


            $data = ['result' => 1,
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
                                ->select('acceptance.id', 'acceptance.code', 'acceptance.name as customer_name', 'acceptance.description as description','branches.code as branch_code', 'branches.name as branch_name', 'csos.code as csos_code', 'csos.name as csos_name')
                                ->get();

        $acceptance = $acceptance->find($id);

        return response()->json($acceptance, 200);

    }
}