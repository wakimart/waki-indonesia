<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Upgrade;
use Illuminate\Http\Request;

class UpgradeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexNew(Request $request)
    {
        $url = $request->all();
        $upgrades = Upgrade::where([['active', true], ['status', 'new']])->paginate(10);
        return view('admin.list_upgrade_new', compact('upgrades', 'url'));
    }

    public function list(Request $request)
    {
        $url = $request->all();
        $upgrades = Upgrade::where([['active', true], ['status', '!=', 'new']])->paginate(10);
        return view('admin.list_upgrade', compact('upgrades', 'url'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $upgrade = Upgrade::find($id);
        return view('admin.add_upgrade', compact('upgrade'));
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
        $upgrade = Upgrade::find($data['upgrade_id']);
        $upgrade['task'] = $data['task'];
        $upgrade['due_date'] = $data['due_date'];
        $upgrade['status'] = "Process";
        $tempHistoryStatus = [];
        if($upgrade['history_status'] != null){
            $tempHistoryStatus = $upgrade['history_status'];
        }
        array_push($tempHistoryStatus, ['user_id' => Auth::user()['id'], 'status' => $upgrade['status'], 'updated_at' => date("Y-m-d H:i:s")]);
        $upgrade['history_status'] = $tempHistoryStatus;
        $upgrade->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Upgrade  $upgrade
     * @return \Illuminate\Http\Response
     */
    public function detail($id)
    {
        $upgrade = Upgrade::find($id);
        return view('admin.detail_upgrade', compact('upgrade'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Upgrade  $upgrade
     * @return \Illuminate\Http\Response
     */
    public function edit(Upgrade $upgrade)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Upgrade  $upgrade
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Upgrade $upgrade)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Upgrade  $upgrade
     * @return \Illuminate\Http\Response
     */
    public function destroy(Upgrade $upgrade)
    {
        //
    }
}
