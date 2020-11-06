<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

class VersionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request){
        $data = $request->all();
        $version = Version::create($data);
        return view('', compact());
    }

    public function update(Request $request){
        $version = Version::find($request->input('id'));
        $version->version = $data->input('version');
        $version->detail = $data->input('detail');
        $version->url = $data->input('url');
        $version->save();
        return view('', compact());
    }

    //  API 
    public function storeVersion(Request $request){
        $data = $request->all();
        $version = Version::create($data);

        $result = ['result'=> 1,
                   'data' => $version
        ];
        return response()->json($result, 200);
    }

    public function listVersion(){
        $version = Version::where('active', '1')->orderBy('id', 'desc')->first();
        $result = ['result'=> 1,
                   'data' => $version
        ];
        return response()->json($result, 200);
    }
}