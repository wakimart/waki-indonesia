<?php

namespace App\Http\Controllers;

use App\Branch;
use App\Cso;
use App\HistoryUpdate;
use App\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CsoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $branches = Branch::Where('active', true)->orderBy("code", 'asc')->get();
        $csos = Cso::paginate(10);
        $countCso = Cso::count();

        return view('admin.list_cso', compact('csos','countCso', 'branches'));
    }

    public function admin_ListCso(Request $request)
    {
        $url = $request->all();
        $branches = Branch::Where('active', true)->orderBy("code", 'asc')->get();
        $csos = Cso::where('csos.active', true)->orderBy('code', 'asc');
        $countCso = Cso::count();

        if ($request->has('filter_branch')) {
            $csos = $csos->where('branch_id', $request->filter_branch);
        }

        if ($request->has('search')) {
            $csos = $csos->where('name','LIKE', '%'.$request->search.'%')
                ->orWhere('code','LIKE', '%'.$request->search.'%')
                ->orWhere('phone','LIKE', '%'.$request->search.'%');
        }

        $csos = $csos->paginate(10);

        return view('admin.list_cso', compact('csos','countCso', 'branches', 'url'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $branches = Branch::Where('active', true)->orderBy("code", 'asc')->get();
        $banks = Order::$Banks;
        return view('admin.add_cso', compact('branches', 'banks'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => [
                'required',
                Rule::unique('csos')->where('active', 1),
            ],
            'name' => 'required|string|max:255',
            'branch_id' => 'required',
        ]);

        if ($validator->fails()) {
            $arr_Errors = $validator->errors()->all();
            $arr_Keys = $validator->errors()->keys();
            $arr_Hasil = [];
            for ($i = 0; $i < count($arr_Keys); $i++) {
                $arr_Hasil[$arr_Keys[$i]] = $arr_Errors[$i];
            }
            return response()->json(['errors' => $arr_Hasil]);
        }else{
            $data = $request->all();
            $data['code'] = strtoupper($data['code']);
            $data['name'] = strtoupper($data['name']);
            $data['branch_id'] = strtoupper($data['branch_id']);
            $cso = Cso::create($data);
            return response()->json(['success' => 'Berhasil']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    public function delete($id){
        DB::beginTransaction();
        try{
            $cso = Cso::where('id', $id)->first();
            $cso->active = false;
            $cso->save();

            $user = Auth::user();
            $historyUpdate= [];
            $historyUpdate['type_menu'] = "Cso";
            $historyUpdate['method'] = "Delete";
            $historyUpdate['meta'] = json_encode(['user'=>$user['id'],'createdAt' => date("Y-m-d h:i:s"), 'dateChange'=> json_encode(array('Active'=>$cso->active))]);
            $historyUpdate['user_id'] = $user['id'];
            $historyUpdate['menu_id'] = $id;

            $createData = HistoryUpdate::create($historyUpdate);
            DB::commit();
            return redirect()->route('list_cso')->with('success', 'Data Berhasil Di Hapus');
        }catch (\Exception $ex) {
            DB::rollback();
            return response()->json(['error' =>  $ex->getMessage(), 500]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        if($request->has('id')){
            $csos = Cso::find($request->get('id'));
            $branches = Branch::Where('active', true)->orderBy("code", 'asc')->get();
            $banks = Order::$Banks;
            return view('admin.update_cso', compact('branches', 'banks', 'csos'));
        }else{
            return response()->json(['result' => 'Gagal!!']);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => [
                'required',
                Rule::unique('csos')->whereNot('id', $request->get('id'))->where('active', 1),
            ],
            'name' => 'required|string|max:255',
            'branch_id' => 'required',
        ]);

        if ($validator->fails()) {
            $arr_Errors = $validator->errors()->all();
            $arr_Keys = $validator->errors()->keys();
            $arr_Hasil = [];
            for ($i = 0; $i < count($arr_Keys); $i++) {
                $arr_Hasil[$arr_Keys[$i]] = $arr_Errors[$i];
            }
            return response()->json(['errors' => $arr_Errors]);
        }else{
            DB::beginTransaction();
            try{
                $csos = Cso::find($request->input('idCso'));
                $csos->code = $request->input('code');
                $csos->name = $request->input('name');
                $csos->branch_id = $request->input('branch_id');
                $csos->phone = $request->input('phone');
                $csos->save();

                $user = Auth::user();
                $historyUpdate= [];
                $historyUpdate['type_menu'] = "Cso";
                $historyUpdate['method'] = "Update";
                $historyUpdate['meta'] = ['user'=>$user['id'],'createdAt' => date("Y-m-d h:i:s"), 'dateChange'=> $csos];
                $historyUpdate['user_id'] = $user['id'];
                $historyUpdate['menu_id'] = $csos->id;

                $createData = HistoryUpdate::create($historyUpdate);

                DB::commit();
                return response()->json(['success' => 'Berhasil!']);
            }catch (\Exception $ex) {
                DB::rollback();
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function fetchCsoById(Request $request){
        $cso = Cso::Where('id', $request->id)->first();
        return response()->json($cso);
    }

    public function fetchCsoByIdBranch($branch){
        $cso = Cso::Where([['branch_id', $branch], ['active', true]])->get();
        return response()->json($cso);
    }

    public function fetchCso(Request $request){
        $csos = Cso::where('code', $request->cso_code)
                        ->where('active', '=', 1);
        if ($request->has('branch_id')) $csos->where('branch_id', $request->branch_id);
        $csos = $csos->get();
        if(count($csos) > 0) {
            return [
                'result' =>'true',
                'data' => $csos
            ];
        }

        return [
            'result' =>'false',
            'data' => $csos
        ];
    }

    //KHUSUS API APPS
    public function fetchCsoApi($branchId){
        $csos = Cso::where([['active', true],['branch_id', $branchId]])->get();
        $data = ['result' => 1,
                 'data' => $csos
                ];
        return response()->json($data,200);
    }
}
