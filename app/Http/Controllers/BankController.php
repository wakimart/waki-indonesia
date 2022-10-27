<?php

namespace App\Http\Controllers;

use App\Bank;
use App\HistoryUpdate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BankController extends Controller
{
    public function index(Request $request)
    {
        $url = $request->all();

        $banks = Bank::where('active', true);

        if ($request->has('search')) {
            $banks->where('name', 'LIKE', '%' . $request->search . '%');
        }

        $countBanks = $banks->count();
        $banks = $banks->paginate(10);

        return view(
            "admin.list_bank",
            compact(
                "countBanks",
                "banks",
                "url"
            )
        )
            ->with("i", (request()->input("page", 1) - 1) * 10 + 1);;
    }

    public function create()
    {
        return view('admin.add_bank');
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required',
        ]);
        if($validator->fails()){
            $arr_Errors = $validator->errors()->all();
            $arr_Keys = $validator->errors()->keys();
            $arr_Hasil = [];
            for ($i = 0; $i < count($arr_Keys); $i++) {
                $arr_Hasil[$arr_Keys[$i]] = $arr_Errors[$i];
            }
            return response()->json(['errors' => $arr_Hasil]);
        }else {
            $data = $request->all();
            Bank::create($data);

            return response()->json(['success' => 'Berhasil']);
        }
    }

    public function edit(Request $request)
    {
        if($request->has('id')){
            $bank = Bank::find($request->get('id'));
            return view('admin.update_bank', compact('bank'));
        }else{
            return response()->json(['result' => 'Gagal!!']);
        }
    }

    public function update(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required',
        ]);
        if($validator->fails()){
            $arr_Errors = $validator->errors()->all();
            $arr_Keys = $validator->errors()->keys();
            $arr_Hasil = [];
            for ($i = 0; $i < count($arr_Keys); $i++) {
                $arr_Hasil[$arr_Keys[$i]] = $arr_Errors[$i];
            }
            return response()->json(['errors' => $arr_Hasil]);
        }else {
            $data = $request->all();
            $bank = Bank::find($request->get('idBank'));
            $bank->name = $data['name'];
            $bank->save();

            return response()->json(['success' => 'Berhasil']);
        }
    }

    public function destroy(Request $request)
    {
        DB::beginTransaction();

        if (!empty($request->id)) {
            try {
                $bank = Bank::find($request->id);
                $bank->active = false;
                $bank->save();

                $user = Auth::user();
                $historyDeleteBank["type_menu"] = "Bank";
                $historyDeleteBank["method"] = "Delete";
                $historyDeleteBank["meta"] = json_encode(
                    [
                        "user" => $user["id"],
                        "createdAt" => date("Y-m-d H:i:s"),
                        "dataChange" => $bank->getChanges(),
                    ],
                    JSON_THROW_ON_ERROR
                );

                $historyDeleteBank["user_id"] = $user["id"];
                $historyDeleteBank["menu_id"] = $request->id;
                HistoryUpdate::create($historyDeleteBank);

                DB::commit();

                return redirect()
                    ->route("list_bank")
                    ->with("success", "Data berhasil dihapus!");
            } catch (Exception $e) {
                DB::rollback();

                return response()->json([
                    "error" => $e,
                ], 500);
            }
        }

        return response()->json(["result" => "Data tidak ditemukan."], 400);
    }
}
