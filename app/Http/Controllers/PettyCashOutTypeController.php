<?php

namespace App\Http\Controllers;

use App\BankAccount;
use App\PettyCashOutType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PettyCashOutTypeController extends Controller
{
    public static function fnGetBankByPettyCashType($bank_account_id)
    {
        $bank = BankAccount::find($bank_account_id);
        $pettyCashBanks = [
            'banks' => null,
            'accs' => null,
        ];
        if ($bank->petty_cash_type == "bank") {
            $pettyCashBanks['banks'] = BankAccount::where('active', true)
                ->get();
        }
        if ($bank->petty_cash_type == "account") {
            $pettyCashBanks['accs'] = PettyCashOutType::where('active', true)
                ->get();
        }
        return $pettyCashBanks;
    }

    public function getBankByPettyCashType(Request $request)
    {
        if ($request->bank_id) {
            $bank = BankAccount::find($request->bank_id);
            if ($bank) {
                $pettyCashBanks = self::fnGetBankByPettyCashType($bank->id);
                return response()->json([
                    "data" => $pettyCashBanks, 
                    "petty_cash_type" => $bank->petty_cash_type,
                ], 200);            
            }
        }
        return response()->json(["error" => 'Error Parameter'], 500);
    }
    
    public function index(Request $request)
    {
        $pettyCashTypes = PettyCashOutType::where('active', true);

        if ($request->has('filter_string')) {
            $pettyCashTypes->where(function($query) use ($request) {
                $query->where('code', 'like', '%'.$request->filter_string.'%')
                    ->orWhere('name', 'like', '%'.$request->filter_string.'%');
            });
        }

        $pettyCashTypes = $pettyCashTypes->paginate(10);
        return view('admin.list_pettycashtype', compact('pettyCashTypes'));
    }

    public function create()
    {
        return view('admin.add_pettycashtype');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => [
                'required',
                Rule::unique('petty_cash_out_types')->where('active', 1),
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
        }

        $data = $request->all();
        $data['code'] = strtoupper($data['code']);
        $pettyCashType = PettyCashOutType::create($data);
        return response()->json(['success' => 'Berhasil']);
    }

    public function edit(Request $request)
    {
        if (empty($request->id)) {
            return redirect()
                ->route("list_petty_cash_type")
                ->with("danger", "Data not found.");
        }

        $pettyCashTypes = PettyCashOutType::find($request->get('id'));
        return view('admin.edit_pettycashtype', compact('pettyCashTypes'));
    }

    public function update(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'id' => 'required',
            'code' => [
                'required',
                Rule::unique('petty_cash_out_types')->whereNot('id', $request->get('id'))->where('active', 1),
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
            $pettyCashTypes = PettyCashOutType::find($request->input('id'));
            $pettyCashTypes->code = strtoupper($request->input('code'));
            $pettyCashTypes->name = $request->input('name');
            $pettyCashTypes->max = $request->input('max');
            $pettyCashTypes->save();
            return response()->json(['success' => 'Berhasil!']);
        }
    }

    public function destroy(Request $request)
    {
        if (empty($request->id)) {
            return redirect()
                ->route("list_petty_cash_type")
                ->with("danger", "Data not found.");
        }

        DB::beginTransaction();
        try {
            $pettyCashType = PettyCashOutType::where("id", $request->id)->first();
            $pettyCashType->active = false;
            $pettyCashType->save();

            DB::commit();

            return redirect()
                ->route("list_petty_cash_type")
                ->with("success", "Petty Cash Type successfully deleted.");
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->route("list_petty_cash_type")
                ->with("danger", $e->getMessage());
        }
    }
}
