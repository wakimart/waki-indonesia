<?php

namespace App\Http\Controllers;

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
        $upgrades = Upgrade::where('active', true)->paginate(10);
        return view('admin.list_upgrade_new', compact('upgrades', 'url'));
    }

    public function index()
    {
        //
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Upgrade  $upgrade
     * @return \Illuminate\Http\Response
     */
    public function show(Upgrade $upgrade)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Upgrade  $upgrade
     * @return \Illuminate\Http\Response
     */
    public function edit(Upgrade $upgrade)
    {
        //
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
