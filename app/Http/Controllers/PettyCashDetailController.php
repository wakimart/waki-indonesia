<?php

namespace App\Http\Controllers;

use App\Bank;
use App\HistoryUpdate;
use App\PettyCash;
use App\PettyCashDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class PettyCashDetailController extends Controller
{
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();
            $pettyCash = PettyCash::find($request->petty_cash_id);
            // $bank_petty_cash_type = Bank::find($pettyCash->bank_id);

            $pettyCashDetail = new PettyCashDetail();
            $pettyCashDetail->petty_cash_id = $pettyCash->id;
            $explode = explode('_', $request->input('tdetail_bank'));
            $bank_petty_cash_type = $explode[0];
            if ($bank_petty_cash_type == "bank") {
                $pettyCashDetail->petty_cash_out_bank_account_id = $explode[1];
            } else if ($bank_petty_cash_type == "account") {
                $pettyCashDetail->petty_cash_out_type_id =  $explode[1];
            }
            $pettyCashDetail->nominal = $request->input('tdetail_nominal');
            $pettyCashDetail->description = $request->input('tdetail_description');

            // Add Image
            $arrImages = [];
            if ($request->hasFile("evidence_image")) {
                $images = $request->file("evidence_image");
                foreach ($images as $key => $image) {
                    $imageName = "pettycash_" . time() . "_" . $key . "." . $image->getClientOriginalExtension();
                    $image->move("sources/pettycash", $imageName);
                    $arrImages[] = $imageName;
                }
            }
            $pettyCashDetail->evidence_image = json_encode($arrImages);
            $pettyCashDetail->save();

            $historyUpdate['type_menu'] = "Petty Cash";
            $historyUpdate['method'] = "Update";
            $historyUpdate['meta'] = json_encode([
                'user'=>$user['id'],
                'createdAt' => date("Y-m-d h:i:s"),
                'dataChange'=> ["Add Petty Cash Detail" => $pettyCashDetail->id]
            ]);

            $historyUpdate['user_id'] = $user['id'];
            $historyUpdate['menu_id'] = $pettyCashDetail['petty_cash_id'];
            $createData = HistoryUpdate::create($historyUpdate);

            DB::commit();
            return redirect()->back()->with('success', 'Petty Cash Detail Berhasil Di Tambah');
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json(["errors" => $ex->getMessage()], 500);
        }
    }

    public function edit(Request $request)
    {
        if($request->has('petty_cash_id') && $request->has('ptc_detail_id')){
            $pettyCashDetail = PettyCashDetail::where('petty_cash_id', $request->get('petty_cash_id'))
                ->where('id', $request->get('ptc_detail_id'))->first();

            if ($pettyCashDetail) {
                return response()->json([
                    'status' => 'success',
                    'result' => $pettyCashDetail
                ]);
            }
        }
        return response()->json(['result' => 'Gagal!!']);   
    }

    public function update(Request $request)
    {
        DB::beginTransaction();
        try {
            if ($request->has('petty_cash_id') && $request->has('ptc_detail_id')) {
                $pettyCashDetail = PettyCashDetail::where('petty_cash_id', $request->get('petty_cash_id'))
                    ->where('id', $request->get('ptc_detail_id'))->first();
                    
                if ($pettyCashDetail) {
                    $pettyCashDetailOld = PettyCashDetail::where('petty_cash_id', $request->get('petty_cash_id'))
                        ->where('id', $request->get('ptc_detail_id'))->first();

                    $data = $request->all();
                    $user = Auth::user();
                    $pettyCash = PettyCash::find($request->petty_cash_id);
                    // $bank_petty_cash_type = Bank::find($pettyCash->bank_id);
                    $explode = explode('_', $request->input('tdetail_bank'));
                    $bank_petty_cash_type = $explode[0];
                    $bank_petty_cash_type_old = $pettyCashDetail->petty_cash_out_bank_account_id ? "bank" : "account";
                    if ($bank_petty_cash_type == "bank") {
                        $pettyCashDetail->petty_cash_out_bank_account_id = $explode[1];
                        $pettyCashDetail->petty_cash_out_type_id =  null;
                    } else if ($bank_petty_cash_type == "account") {
                        $pettyCashDetail->petty_cash_out_bank_account_id = null;
                        $pettyCashDetail->petty_cash_out_type_id =  $explode[1];
                    }
                    $pettyCashDetail->nominal = $request->input('tdetail_nominal');
                    $pettyCashDetail->description = $request->input('tdetail_description');

                    $arrImages = json_decode($pettyCashDetail->evidence_image) ?? [];
                    // Hapus Image
                    if ($request->has('del_image')) {
                        foreach ($request->input('del_image') as $del_image) {
                            if (isset($arrImages[$del_image])) {
                                if (File::exists("sources/pettycash/" . $arrImages[$del_image])) {
                                    File::delete("sources/pettycash/" . $arrImages[$del_image]);
                                }
                                unset($arrImages[$del_image]);
                            }
                        }
                    }
                    
                    // Add Image
                    if ($request->hasFile("evidence_image")) {
                        $images = $request->file("evidence_image");
                        foreach ($images as $key => $image) {
                            $imageName = "pettycash_" . time() . "_" . $key . "." . $image->getClientOriginalExtension();
                            $image->move("sources/pettycash", $imageName);
                            $arrImages[] = $imageName;
                        }
                    }
                    $pettyCashDetail->evidence_image = json_encode(array_values($arrImages));
                    $pettyCashDetail->save();

                    $historyUpdate['type_menu'] = "Petty Cash";
                    $historyUpdate['method'] = "Update";
                    $historyUpdate['meta'] = json_encode([
                        'user'=>$user['id'],
                        'createdAt' => date("Y-m-d h:i:s"),
                        'dataChange'=> ["Update Petty Cash Detail: " => [$pettyCashDetail->id => array_diff(json_decode($pettyCashDetail, true), json_decode($pettyCashDetailOld, true)) ]]
                    ]);

                    $historyUpdate['user_id'] = $user['id'];
                    $historyUpdate['menu_id'] = $data['petty_cash_id'];
                    $createData = HistoryUpdate::create($historyUpdate);
                    DB::commit();

                    return redirect()->back()->with('success', 'Petty Cash Detail Berhasil Di Ubah');
                }
            }
            return response()->json(['result' => 'Gagal!!']);
        } catch (\Exception $ex) {
            DB::rollback();
            return response()->json(['error' =>  $ex->getMessage(), 500]);
        }
    }

    public function destroy(Request $request)
    {
        DB::beginTransaction();
        try {
            if($request->has('id')){
                $pettyCashDetail = PettyCashDetail::find($request->get('id'));
                $pettyCash = PettyCash::find($pettyCashDetail->petty_cash_id);
                // $bank_petty_cash_type = Bank::find($pettyCash->bank_id);
                $bank_petty_cash_type = $pettyCashDetail->petty_cash_out_bank_account_id ? "bank" : "account";
                if ($pettyCashDetail) {
                    $data['petty_cash_id'] = $pettyCashDetail->petty_cash_id;
                    $pettyCashDetailOld = PettyCashDetail::find($request->get('id'));

                    foreach ((json_decode($pettyCashDetail->evidence_image) ?? []) as $imageDel) {
                        if (File::exists("sources/pettycash/" . $imageDel)) {
                            File::delete("sources/pettycash/" . $imageDel);
                        }
                    }
                    $pettyCashDetail->delete();

                    $user = Auth::user();
                    $historyUpdate['type_menu'] = "Petty Cash";
                    $historyUpdate['method'] = "Update";
                    $historyUpdate['meta'] = json_encode([
                        'user'=>$user['id'],
                        'createdAt' => date("Y-m-d h:i:s"),
                        'dataChange'=> ["Deleted Petty Cash Detail" => $pettyCashDetailOld ]
                    ]);

                    $historyUpdate['user_id'] = $user['id'];
                    $historyUpdate['menu_id'] = $data['petty_cash_id'];
                    $createData = HistoryUpdate::create($historyUpdate);
                    DB::commit();

                    return redirect()->back()->with('success', 'Petty Cash Detail Berhasil Di Hapus');
                }
            }
            
            return response()->json(['result' => 'Gagal!!']);
        }catch (\Exception $ex) {
            DB::rollback();
            return response()->json(['error' =>  $ex->getMessage(), 500]);
        }
    }
}
