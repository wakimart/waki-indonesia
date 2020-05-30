<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Role;
use Auth;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

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
        return view('admin.add_useradmin', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
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
        
        $user = User::create($data); //INSERT INTO DATABASE (with created_at)
        $user->roles()->attach($request['role']);

        return response()->json(['success' => 'Berhasil !!' . $user]);
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
            return view('admin.update_useradmin', compact('users'));
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

        $user->fill($data)->save();
        return response()->json(['success' => $user]);

        return response()->json(['success' => 'Berhasil']);
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
