<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\HomeService;
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
            'branch_id.exists' => 'Please choose the branch.',
            'cso2_id.required' => 'The CSO Code field is required.',
            'cso2_id.exists' => 'Wrong CSO Code.'
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
            'branch_id.exists' => 'Please choose the branch.',
            'cso2_id.required' => 'The CSO Code field is required.',
            'cso2_id.exists' => 'Wrong CSO Code.'
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
}