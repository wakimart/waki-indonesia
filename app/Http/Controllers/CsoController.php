<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Branch;
use App\Order;
use App\Cso;

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
        $data = $request->all();
        $data['code'] = strtoupper($data['code']);
        $data['name'] = strtoupper($data['name']);
        $cso = Cso::create($data);
        return response()->json(['success' => 'Berhasil']);
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
        $csos = Cso::find($request->input('idCso'));
        $csos->code = $request->input('code');
        $csos->name = $request->input('name');
        $csos->branch_id = $request->input('branch_id');
        $csos->save();

        return response()->json(['success' => 'Berhasil!']);
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
        $cso = Cso::where('id', $request->id)->first();
        return response()->json($cso);
    }
}
