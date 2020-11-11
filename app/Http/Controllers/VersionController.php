<?php

namespace App\Http\Controllers;

use App\Version;
use App\HistoryUpdate;
use Illuminate\Support\Facades\Auth;
use DB;

use Illuminate\Http\Request;

class VersionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request){
        $versions = Version::where('active', true)->get();
        $count = count($versions);
        
        return view('admin.list_appversion', compact('versions', 'count'));
    }
    public function create(){
        return view('admin.add_appversion');
    }

    public function delete($id){
        DB::beginTransaction();
        try{
            $app = Version::where('id', $id)->first();
            $app->active = false;
            $app->save();

            $user = Auth::user();
            $historyUpdate= [];
            $historyUpdate['type_menu'] = "App";
            $historyUpdate['method'] = "Delete";
            $historyUpdate['meta'] = json_encode(['user'=>$user['id'],'createdAt' => date("Y-m-d h:i:s"), 'dateChange'=> json_encode(array('Active'=>$app->active))]);
            $historyUpdate['user_id'] = $user['id'];
            $historyUpdate['menu_id'] = $id;

            $createData = HistoryUpdate::create($historyUpdate);
            DB::commit();
            return redirect()->route('list_appVersion')->with('success', 'Data Berhasil Di Hapus');
        }catch (\Exception $ex) {
            DB::rollback();
            return response()->json(['error' =>  $ex->getMessage(), 500]);
        }
    }

    public function store(Request $request){
        $data = $request->all();
        $version = Version::create($data);
        return response()->json(['success' => 'Berhasil']);
    }

    public function edit(Request $request)
    {
        if($request->has('id')){
            $version = Version::find($request->get('id'));
            return view('admin.update_appversion', compact('version'));
        }else{
            return response()->json(['result' => 'Gagal!!']);
        }
    }
    public function update(Request $request){
        DB::beginTransaction();
            try{
                $version = Version::find($request->input('id'));
                $version->version = $request->input('version');
                $version->detail = $request->input('detail');
                $version->url = $request->input('url');
                $version->save();

                $user = Auth::user();
                $historyUpdate= [];
                $historyUpdate['type_menu'] = "App";
                $historyUpdate['method'] = "Update";
                $historyUpdate['meta'] = ['user'=>$user['id'],'createdAt' => date("Y-m-d h:i:s"), 'dateChange'=> $version];
                $historyUpdate['user_id'] = $user['id'];
                $historyUpdate['menu_id'] = $version->id;

                $createData = HistoryUpdate::create($historyUpdate);

                DB::commit();
                return response()->json(['success' => 'Berhasil!'], 200);
            }catch (\Exception $ex) {
                DB::rollback();
                return response()->json(['error' => $ex->getMessage()], 500);
            }
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