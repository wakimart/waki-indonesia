<?php

namespace App\Http\Controllers;

use App\Bank;
use App\BankAccount;
use App\HistoryUpdate;
use App\PettyCash;
use App\PettyCashDetail;
use App\PettyCashOutType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class PettyCashController extends Controller
{
    public static function getUserBank()
    {
        $banks = [];
        $list_bank_account_user = json_decode(Auth::user()->list_bank_account_id, true);
        if ($list_bank_account_user) {
            $banks = BankAccount::where('active', true)
                ->whereIn('id', $list_bank_account_user)
                ->get();
        }
        return $banks;
    }

    public function index(Request $request)
    {
        $banks = self::getUserBank();
        $pettyCashOutTypes = PettyCashOutType::where('active', true)->get();

        $startDate = date('y-m-01');
        if ($request->has('filter_start_date')) {
            $startDate = date('Y-m-d', strtotime($request->filter_start_date));
        }
        $endDate = date('Y-m-d');
        if ($request->has('filter_end_date')) {
            $endDate = date('Y-m-d', strtotime($request->filter_end_date));
        }
        $currentBank="";
        if ($request->has('filter_bank')) {
            $currentBank = BankAccount::where("id", $request->filter_bank)->first();
            if (!in_array($currentBank->id, $banks->pluck('id')->toArray())) $currentBank = "";
        }

        $types = ["in", "out"];
        $pettyCashTypes = [];
        if ($currentBank) {
            $pettyCashTypes['statement'] = PettyCashDetail::from('petty_cash_details as ptcd')
                ->select('ptcd.*', 'ptc.code', 'ptc.transaction_date', 'ptc.type')
                ->join('petty_cashes as ptc', 'ptcd.petty_cash_id', 'ptc.id')
                ->where('ptc.active', true)
                ->whereBetween('transaction_date', [$startDate, $endDate])
                ->where('bank_account_id', $currentBank->id)
                ->orderBy('transaction_date')
                ->orderByRaw('length(code) asc, code asc');
            if ($request->filter_tpye) {
                $pettyCashTypes['statement']->where('ptcd.petty_cash_out_type_id', $request->filter_type);
            }
            $pettyCashTypes['statement'] = $pettyCashTypes['statement']->get();
            foreach ($types as $type) {
                $pettyCashTypes[$type] = PettyCash::where('active', true)
                    ->whereBetween('transaction_date', [$startDate, $endDate])
                    ->where('type', $type)
                    ->where('bank_account_id', $currentBank->id)
                    ->orderBy('transaction_date', 'desc')
                    ->orderByRaw('length(code) desc, code desc');
                if ($request->filter_type) {
                    $pettyCashTypes[$type]->whereHas('pettyCashDetail', function($query) use($request) {
                        $query->where('petty_cash_out_type_id', $request->filter_type);
                    });
                }
                $pettyCashTypes[$type] = $pettyCashTypes[$type]->get();
            }
        }
        return view("admin.list_pettycash", compact('banks', 'pettyCashOutTypes', 'startDate', 'endDate', 'currentBank', 'pettyCashTypes')); 
    }

    public function print(Request $request)
    {
        $banks = self::getUserBank();
        $pettyCashAccs = PettyCashOutType::where('active', true)->get();

        $lastPTCClosedBook = null;
        $startDate = date('Y-m-01');
        if ($request->has('filter_start_date')) {
            $startDate = date('Y-m-d', strtotime($request->filter_start_date));
        }
        $endDate = date('Y-m-d');
        if ($request->has('filter_end_date')) {
            $endDate = date('Y-m-d', strtotime($request->filter_end_date));
        }
        $currentBank="";
        if ($request->has('filter_bank')) {
            $currentBank = BankAccount::where("id", $request->filter_bank)->first();
            if (!in_array($currentBank->id, $banks->pluck('id')->toArray())) $currentBank = "";
        }

        $keyType = $request->type;
        $pettyCashes = [];
        if ($currentBank) {
            if ($keyType == "statement") {
                $pettyCashes = PettyCashDetail::from('petty_cash_details as ptcd')
                    ->select('ptcd.*', 'ptc.code', 'ptc.transaction_date', 'ptc.type')
                    ->join('petty_cashes as ptc', 'ptcd.petty_cash_id', 'ptc.id')
                    ->where('ptc.active', true)
                    ->whereBetween('transaction_date', [$startDate, $endDate])
                    ->where('bank_account_id', $currentBank->id)
                    ->orderBy('transaction_date')
                    ->orderByRaw('length(code) asc, code asc');
                if ($request->filter_acc) {
                    $pettyCashes->where('ptcd.petty_cash_out_type_id', $request->filter_acc);
                }
                $pettyCashes = $pettyCashes->get();
            } else {
                $pettyCashes = PettyCash::where('active', true)
                    ->whereBetween('transaction_date', [$startDate, $endDate])
                    ->where('type', $keyType)
                    ->where('bank_account_id', $currentBank->id)
                    ->orderBy('transaction_date', 'desc')
                    ->orderByRaw('length(code) desc, code desc');
                if ($request->filter_acc) {
                    $pettyCashes->whereHas('pettyCashDetail', function($query) use($request) {
                        $query->where('petty_cash_out_type_id', $request->filter_acc);
                    });
                }
                $pettyCashes = $pettyCashes->get();
            }
        }
        return view("admin.print_pettycash", compact('banks', 'startDate', 'endDate', 'currentBank', 'pettyCashes', 'keyType'));   
    }

    public function createIn(Request $request)
    {
        $banks = self::getUserBank();
        return view('admin.add_pettycash_in', compact('banks'));
    }

    public function createOut(Request $request)
    {
        $banks = self::getUserBank();
        return view('admin.add_pettycash_out', compact('banks'));
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'type' => 'required',
            'transaction_date' => 'required',
            'code' => 'required|unique:petty_cashes,code',
            'bank' => 'required|exists:bank_accounts,id',
            'nominal' => 'sometimes|required',
        ]);
        if($validator->fails()){
            $arr_Errors = $validator->errors()->all();
            $arr_Keys = $validator->errors()->keys();
            $arr_Hasil = [];
            for ($i = 0; $i < count($arr_Keys); $i++) {
                $arr_Hasil[$arr_Keys[$i]] = $arr_Errors[$i];
            }
            return response()->json(['errors' => $arr_Hasil]);
        }
        DB::beginTransaction();
        try {
            $user = Auth::user();
            $pettyCash = new PettyCash();
            $pettyCash->code = $request->code;
            $pettyCash->temp_no = $request->temp_no;
            $pettyCash->transaction_date = $request->transaction_date;
            $pettyCash->type = $request->type;
            $pettyCash->bank_account_id = $request->bank;
            $pettyCash->user_id = $user->id;
            $pettyCash->save();

            $arr_ptc_details = $request->arr_ptc_details ?? [];
            if ($request->type == "in") $arr_ptc_details = [0]; // Restrict only 1 Petty Cash Detail In
            // $bank_petty_cash_type = Bank::find($pettyCash->bank_id);
            foreach ($arr_ptc_details as $arr_ptc_detail) {
                $bank_petty_cash_type = null;
                $pettyCashDetail = new PettyCashDetail();
                $pettyCashDetail->petty_cash_id = $pettyCash->id;
                if ($request->type == "in") {
                    $pettyCashDetail->nominal = $request->nominal;
                    $pettyCashDetail->description = $request->description;
                } else if ($request->type == "out") {
                    $explode = explode('_', $request->input('tdetail_bank_'.$arr_ptc_detail));
                    $bank_petty_cash_type = $explode[0];
                    if ($bank_petty_cash_type == "bank") {
                        $pettyCashDetail->petty_cash_out_bank_account_id =  $explode[1];
                    } else if ($bank_petty_cash_type == "account") {
                        $pettyCashDetail->petty_cash_out_type_id =  $explode[1];
                    }
                    $pettyCashDetail->nominal = $request->input('tdetail_nominal_'.$arr_ptc_detail);
                    $pettyCashDetail->description = $request->input('tdetail_description_'.$arr_ptc_detail);
                }
                
                // Add Image
                $arrImages = [];
                if ($request->hasFile("evidence_image_".$arr_ptc_detail)) {
                    $images = $request->file("evidence_image_".$arr_ptc_detail);
                    foreach ($images as $key => $image) {
                        $imageName = "pettycash_" . time() . $arr_ptc_detail . "_" . $key . "." . $image->getClientOriginalExtension();
                        $image->move("sources/pettycash", $imageName);
                        $arrImages[] = $imageName;
                    }
                }
                $pettyCashDetail->evidence_image = json_encode($arrImages);
                $pettyCashDetail->save();
            }

            DB::commit();
            return response()->json(['success' => $pettyCash]);
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json(["errors" => $ex->getMessage()], 500);
        }
    }

    /**
     * @param string $type in|out
     * @param Date $transaction_date
     * @return string $code
     */
    public static function fnGenerateCode($type, $transaction_date)
    {
        $code = "PTC".ucfirst(substr($type, 0, 1));
        $code .= '-'.date('dmy', strtotime($transaction_date));

        $countPettyCash = 1;
        $lastPettyCash = PettyCash::where('code', 'like', $code.'%')
            ->where('type', $type)
            ->orderByRaw('length(code) desc, code desc')->first();
        if ($lastPettyCash) {
            $lastCountPettyCash = explode("-", $lastPettyCash->code);
            $lastCountPettyCash = end($lastCountPettyCash);
            $countPettyCash = $lastCountPettyCash + 1;
        }
        
        $code .= '-'.$countPettyCash;
        return $code;
    }

    public function generateCode(Request $request)
    {
        if ($request->type && $request->transaction_date) {
            $code = self::fnGenerateCode($request->type, $request->transaction_date);
            return response()->json(["data" => $code], 200);            
        }
        return response()->json(["error" => 'Error Parameter'], 500);
    }

    public function show(Request $request)
    {
        if (empty($request->id)) {
            return redirect()
                ->route("list_petty_cash")
                ->with("danger", "Data not found.");
        }

        $pettyCash = PettyCash::find($request->id);
        $pettyCashBanks = PettyCashOutTypeController::fnGetBankByPettyCashType($pettyCash->bankAccount->id);
        $historyUpdate = HistoryUpdate::leftjoin('users','users.id', '=','history_updates.user_id' )
        ->select('history_updates.method', 'history_updates.created_at','history_updates.meta as meta' ,'users.name as name')
        ->where('type_menu', 'Petty Cash')->where('menu_id', $pettyCash->id)->get();

        return view('admin.detail_pettycash', compact('pettyCash', 'pettyCashBanks', 'historyUpdate'));
    }

    public function editIn(Request $request)
    {
        if (empty($request->id)) {
            return redirect()
                ->route("list_petty_cash")
                ->with("danger", "Data not found.");
        }

        $banks = self::getUserBank();
        $pettyCash = PettyCash::where('id', $request->id)
            ->where('type', 'in')->firstorFail();
        return view('admin.edit_pettycash_in', compact('banks', 'pettyCash'));
    }

    public function editOut(Request $request)
    {
        if (empty($request->id)) {
            return redirect()
                ->route("list_petty_cash")
                ->with("danger", "Data not found.");
        }

        $banks = self::getUserBank();
        $pettyCash = PettyCash::where('id', $request->id)
            ->where('type', 'out')->firstOrFail();
        $pettyCashBanks = PettyCashOutTypeController::fnGetBankByPettyCashType($pettyCash->bankAccount->id);
        return view('admin.edit_pettycash_out', compact('banks', 'pettyCash', 'pettyCashBanks'));
    }

    public function update(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'id' => 'required|exists:petty_cashes,id',
            'type' => 'required',
            'transaction_date' => 'required',
            'code' => 'required|unique:petty_cashes,code,'.$request->id,
            'bank' => 'required|exists:banks,id',
            'nominal' => 'sometimes|required',
        ]);
        if($validator->fails()){
            $arr_Errors = $validator->errors()->all();
            $arr_Keys = $validator->errors()->keys();
            $arr_Hasil = [];
            for ($i = 0; $i < count($arr_Keys); $i++) {
                $arr_Hasil[$arr_Keys[$i]] = $arr_Errors[$i];
            }
            return response()->json(['errors' => $arr_Hasil]);
        }
        DB::beginTransaction();
        try {
            $user = Auth::user();
            $pettyCash = PettyCash::find($request->id);
            $dataBefore = PettyCash::find($request->id);
            $pettyCash->temp_no = $request->temp_no;
            $pettyCash->transaction_date = $request->transaction_date;
            $pettyCash->type = $request->type;
            $pettyCash->bank_account_id = $request->bank;
            $pettyCash->save();

            $pettyCashDetails = PettyCashDetail::where('petty_cash_id', $pettyCash->id)->get();
            
            $arr_ptc_details = $request->arr_ptc_details ?? [];
            if ($request->type == "in") $arr_ptc_details = [0]; // Restrict only 1 Petty Cash Detail In
            // $bank_petty_cash_type = Bank::find($pettyCash->bank_id);
            foreach ($arr_ptc_details as $arr_ptc_detail) {
                $bank_petty_cash_type = null;
                $bank_petty_cash_type_old = null;
                $pettyCashDetail = new PettyCashDetail();
                // Update petty Cash Detail
                if ($request->has('ptc_detail_id_'.$arr_ptc_detail)) {
                    $pettyCashDetail = PettyCashDetail::find($request->input('ptc_detail_id_'.$arr_ptc_detail));
                    $bank_petty_cash_type_old = $pettyCashDetail->petty_cash_out_bank_account_id ? "bank" : "account";
                } else {
                    $pettyCashDetail->petty_cash_id = $pettyCash->id;
                }
                if ($request->type == "in") {
                    $pettyCashDetail->nominal = $request->nominal;
                    $pettyCashDetail->description = $request->description;
                } else if ($request->type == "out") {
                    $explode = explode('_', $request->input('tdetail_bank_'.$arr_ptc_detail));
                    $bank_petty_cash_type = $explode[0];
                    if ($bank_petty_cash_type == "bank") {
                        $pettyCashDetail->petty_cash_out_bank_account_id =  $explode[1];
                        $pettyCashDetail->petty_cash_out_type_id = null;
                    } else if ($bank_petty_cash_type == "account") {
                        $pettyCashDetail->petty_cash_out_bank_account_id = null;
                        $pettyCashDetail->petty_cash_out_type_id =  $explode[1];
                    }
                    $pettyCashDetail->nominal = $request->input('tdetail_nominal_'.$arr_ptc_detail);
                    $pettyCashDetail->description = $request->input('tdetail_description_'.$arr_ptc_detail);
                }
                
                $arrImages = json_decode($pettyCashDetail->evidence_image) ?? [];
                // Hapus Image
                if ($request->has('del_image_'.$arr_ptc_detail)) {
                    foreach ($request->input('del_image_'.$arr_ptc_detail) as $del_image) {
                        if (isset($arrImages[$del_image])) {
                            if (File::exists("sources/pettycash/" . $arrImages[$del_image])) {
                                File::delete("sources/pettycash/" . $arrImages[$del_image]);
                            }
                            unset($arrImages[$del_image]);
                        }
                    }
                }
                
                // Add Image
                if ($request->hasFile("evidence_image_".$arr_ptc_detail)) {
                    $images = $request->file("evidence_image_".$arr_ptc_detail);
                    foreach ($images as $key => $image) {
                        $imageName = "pettycash_" . time() . $arr_ptc_detail . "_" . $key . "." . $image->getClientOriginalExtension();
                        $image->move("sources/pettycash", $imageName);
                        $arrImages[] = $imageName;
                    }
                }
                $pettyCashDetail->evidence_image = json_encode(array_values($arrImages));
                $pettyCashDetail->save();
            }

            // Hapus Old Petty Cash Detail
            $diff_id_ptcDetail = array_diff($pettyCashDetails->pluck('id')->toArray(), ($request->ptc_detail_id ?? []));
            if ($diff_id_ptcDetail) {
                $ptcDetailDeletes = PettyCashDetail::where('petty_cash_id', $pettyCash->id)
                    ->whereIn('id', $diff_id_ptcDetail)->get();
                foreach ($ptcDetailDeletes as $ptcDetailDelete) {
                    $bank_petty_cash_type = $ptcDetailDelete->petty_cash_out_bank_account_id ? "bank" : "account";
                    foreach ((json_decode($ptcDetailDelete->evidence_image) ?? []) as $imageDel) {
                        if (File::exists("sources/pettycash/" . $imageDel)) {
                            File::delete("sources/pettycash/" . $imageDel);
                        }
                    }
                    $ptcDetailDelete->delete();
                }
            }

            $dataChanges = array_diff(json_decode($pettyCash, true), json_decode($dataBefore, true));
            $key = "pettyCashDetail";
            $orderChild = $pettyCash->$key->keyBy('id');
            $child = $pettyCashDetails->keyBy('id');
            foreach ($child as $i=>$c) {
                $array_diff_c = isset($orderChild[$i]) 
                    ? array_diff(json_decode($orderChild[$i], true), json_decode($c, true)) 
                    : "deleted";
                if ($array_diff_c == "deleted") {
                    $dataChanges[$key][$c['id']."_deleted"] = $c;
                } else if ($array_diff_c) {
                    $dataChanges[$key][$c['id']] = $array_diff_c;
                }
            }
            // if ($orderChild > $child) {
                $array_diff_c = array_diff($orderChild->pluck('id')->toArray(), $child->pluck('id')->toArray());
                if ($array_diff_c) {
                    $dataChanges[$key]["added"] = $array_diff_c;
                }
            // }

            $historyUpdate['type_menu'] = "Petty Cash";
            $historyUpdate['method'] = "Update";
            $historyUpdate['meta'] = json_encode([
                'user'=>$user['id'],
                'createdAt' => date("Y-m-d h:i:s"),
                'dataChange'=> $dataChanges
            ]);

            $historyUpdate['user_id'] = $user['id'];
            $historyUpdate['menu_id'] = $pettyCash->id;
            $createData = HistoryUpdate::create($historyUpdate);

            DB::commit();
            return response()->json(['success' => $pettyCash]);
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json(["errors" => $ex->getMessage()], 500);
        }
    }

    public function destroy(Request $request)
    {
        if (empty($request->id)) {
            return redirect()
                ->route("list_petty_cash")
                ->with("danger", "Data not found.");
        }

        DB::beginTransaction();

        try {
            $pettyCash = PettyCash::where("id", $request->id)->first();
            $pettyCash->active = false;
            $pettyCash->save();
            
            $user = Auth::user();
            $historyUpdate= [];
            $historyUpdate['type_menu'] = "Petty Cash";
            $historyUpdate['method'] = "Delete";
            $dataChanges = array('active' => $pettyCash->active);
            $historyUpdate['meta'] = json_encode(['user'=>$user['id'],'createdAt' => date("Y-m-d h:i:s"), 'dataChange'=> $dataChanges]);
            $historyUpdate['user_id'] = $user['id'];
            $historyUpdate['menu_id'] = $pettyCash->id;
            $createData = HistoryUpdate::create($historyUpdate);

            DB::commit();
            return redirect()
                // ->route("list_petty_cash")
                ->back()
                ->with("success", "Petty Cash successfully deleted.");
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                // ->route("list_petty_cash")
                ->back()
                ->with("danger", $e->getMessage());
        }
    }
}
