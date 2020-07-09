<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Branch;
use Illuminate\Validation\Rule;
use Validator;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        $branches = Branch::where('branches.active', true);
        $countBranches = Branch::where('branches.active', true)->count();

        if($request->has('search')){
            $branches = $branches->where( 'name', 'LIKE', '%'.$request->search.'%' )->orWhere( 'code', 'LIKE', '%'.$request->search.'%' );
        }
        $branches = $branches->paginate(10);
        return view('admin.list_branch', compact('branches', 'countBranches'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.add_branch');
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
                Rule::unique('branches')->where('active', 1),
            ],
            'name' => 'required|string|max:255',
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
            $branch = Branch::create($data);
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
            $branches = Branch::find($request->get('id'));
            return view('admin.update_branch', compact('branches'));
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
                Rule::unique('branches')->whereNot('id', $request->get('id'))->where('active', 1),
            ],
            'name' => 'required|string|max:255',
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
            $branches = Branch::find($request->input('idBranch'));
            $branches->code = $request->input('code');
            $branches->name = $request->input('name');
            $branches->save();

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

    public function fetchBranchById(Request $request){
        $branch = Branch::where('id', $request->id)->first();
        return response()->json($branch);
    }
}
