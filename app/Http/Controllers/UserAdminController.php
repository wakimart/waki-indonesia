<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Role;
use App\RoleUser;
use App\Cso;
use App\Branch;
use Auth;
use DB;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Validator;

class UserAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        $roles = Role::all();
        return view('admin.list_useradmin', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::all();
        $csos = Cso::all();
        $branches = Branch::all();
        return view('admin.add_useradmin', compact('roles', 'csos', 'branches'));
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
            'name' => 'required|string|max:255',
            'username' => [
                'required',
                Rule::unique('users')->where('active', 1),
            ],
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|exists:roles,id',
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
            $role = Role::find($request->get('role'));
            $count = User::all()->count();
            $count++;

            $data = $request->only('name', 'username');
            $data['name'] = strtoupper($data['name']);
            $data['username'] = strtoupper($data['username']);

            for ($i = strlen($count); $i < 4; $i++) {
                $count = "0" . $count;
            }

            $code = "EMP-" . substr($data['username'], 0, 2) . $count;


            $data['code'] = $code;
            $data['password'] = Hash::make($request->get('password'));
            $data['permissions'] = $role->permissions;
            $data['user_image'] = null;
            if ($request->hasFile('user_image')) {
                $file = $request->file('user_image');
                $filename = $data['code'] . ".jpg";
                $imgPath = storage_path('app/admin');
                $file->move($imgPath, $filename);
                $data['user_image'] = $filename;
            }

            $data['birth_date'] = $request->get('birth_date');

            if($request->has('branch_0')){
                $arr_branchid = [];
                for ($i=0; $i < $request->total_branch; $i++) { 
                    array_push($arr_branchid, $request->get('branch_' . $i));    
                }
                $data['branches_id'] = json_encode($arr_branchid);
            }
            
            if($request->has('cso_id')){
                if($request->get('cso_id') != null){
                    $data['cso_id'] = $request->get('cso_id');    
                }            
            }

            //  return response()->json(['test' => $data]);      
            
            $user = User::create($data); //INSERT INTO DATABASE (with created_at)
            $user->roles()->attach($request['role']);

            return response()->json(['success' => 'Berhasil !!' . $user]);
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
            $users = User::find($request->get('id'));
            $role_users = DB::table('role_users')
                            ->select('role_id')
                            ->where('user_id', $request->get('id'))
                            ->get();
            $roles = Role::all();
            $csos = Cso::all();
            $branches = Branch::all();
            return view('admin.update_useradmin', compact('users', 'roles', 'role_users', 'csos', 'branches'));
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
            'name' => 'required|string|max:255',
            'username' => [
                'required',
                Rule::unique('users')->whereNot('id', $request->get('id'))->where('active', 1),
            ],
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
            $data = $request->only('name', 'username');
            $data['name'] = strtoupper($data['name']);
            $data['username'] = strtoupper($data['username']);

            $user = User::find($request->input('idUserAdmin'));

            $userpermiss = json_decode($user->permissions, true);

            foreach ($userpermiss as $key => $value) {
                $userpermiss[$key] = true;
                if ($request->get($key) == "true") {
                    $userpermiss[$key] = true;
                } else {
                    $userpermiss[$key] = false;
                }
            }
            $data['permissions'] = json_encode($userpermiss);

            $data['birth_date'] = $request->get('birth_date');

            if($request->has('branch_0')){
                $arr_branchid = [];
                for ($i=0; $i < $request->total_branch; $i++) { 
                    array_push($arr_branchid, $request->get('branch_' . $i));    
                }
                $data['branches_id'] = json_encode($arr_branchid);
            }
            
            if($request->has('cso_id')){
                if($request->get('cso_id') != null){
                    $data['cso_id'] = $request->get('cso_id');    
                }            
            }

            $user->fill($data)->save();
            return response()->json(['success' => $user]);

            return response()->json(['success' => 'Berhasil']);
        }
    }

    public function serveImages($file) {
        return response()->download(storage_path('app/admin/' . $file), null, [], null);
    }

    public function changePassword(Request $request){
        $validatedData = $request->validate([
            'current-password' => 'required',
            'new-password' => 'required|string|min:6|confirmed',
        ]);
 
        //Change Password
        $user = Auth::user();
        $user->password = bcrypt($request->get('new-password'));
        $user->save();
 
        return redirect()->back()->with("success","Password changed successfully!");
 
    }

    public function checkChangePassword(Request $request)
    {
        $data = ['current-password' => '', 'new-password' => '', 'new-password-confirm' => ''];
        if (!(Hash::check($request->get('currentPass'), Auth::user()->password))) {
            // The passwords doesn't matches
            $data['current-password'] = 'error';
        }
        if(strcmp($request->get('currentPass'), $request->get('newPass')) == 0 && ($request->get('currentPass') != "" || $request->get('newPass') != "")){
            //Current password and new password are same
            $data['new-password'] = 'error';
        }
        if(strcmp($request->get('newPass'), $request->get('confirmPass')) != 0){
            //New password and new password confirm are not same
            $data['new-password-confirm'] = 'error';
        }
        return $data;
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
}
