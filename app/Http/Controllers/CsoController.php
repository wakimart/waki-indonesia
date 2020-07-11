<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Branch;
use App\Order;
use App\Cso;
use Illuminate\Validation\Rule;
use Validator;

class CsoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $csos = Cso::all();
        return view('admin.list_cso', compact('csos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $branches = Branch::all();
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
        $validator = \Validator::make($request->all(), [
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
            $branches = Branch::all();
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
        $validator = \Validator::make($request->all(), [
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
            $csos = Cso::find($request->input('idCso'));
            $csos->code = $request->input('code');
            $csos->name = $request->input('name');
            $csos->branch_id = $request->input('branch_id');
            $csos->save();

            return response()->json(['success' => 'Berhasil!']);
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
        $cso = Cso::Where('branch_id', $branch)->get();
        return response()->json($cso);
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
