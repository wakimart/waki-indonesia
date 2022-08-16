<?php

namespace App\Http\Controllers;

use App\HistoryUpdate;
use App\TypeCustomer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TypeCustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $url = $request->all();

        $type_customers = TypeCustomer::where('active', true);

        if ($request->has('search')) {
            $type_customers->where('name', 'LIKE', '%' . $request->search . '%');
        }

        $countTypeCustomers = $type_customers->count();
        $type_customers = $type_customers->paginate(10);

        return view(
            "admin.list_typecustomer",
            compact(
                "countTypeCustomers",
                "type_customers",
                "url"
            )
        )
            ->with("i", (request()->input("page", 1) - 1) * 10 + 1);;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.add_typecustomer');
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
            TypeCustomer::create($data);

            return response()->json(['success' => 'Berhasil']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        if($request->has('id')){
            $type_customers = TypeCustomer::find($request->get('id'));
            return view('admin.update_typecustomer', compact('type_customers'));
        }else{
            return response()->json(['result' => 'Gagal!!']);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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
            $type_customer = TypeCustomer::find($request->get('idTypeCustomer'));
            $type_customer->name = $data['name'];
            $type_customer->save();

            return response()->json(['success' => 'Berhasil']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        DB::beginTransaction();

        if (!empty($request->id)) {
            try {
                $type_customer = TypeCustomer::find($request->id);
                $type_customer->active = false;
                $type_customer->save();

                $user = Auth::user();
                $historyDeleteTypeCustomer["type_menu"] = "Type Customer";
                $historyDeleteTypeCustomer["method"] = "Delete";
                $historyDeleteTypeCustomer["meta"] = json_encode(
                    [
                        "user" => $user["id"],
                        "createdAt" => date("Y-m-d H:i:s"),
                        "dataChange" => $type_customer->getChanges(),
                    ],
                    JSON_THROW_ON_ERROR
                );

                $historyDeleteTypeCustomer["user_id"] = $user["id"];
                $historyDeleteTypeCustomer["menu_id"] = $request->id;
                HistoryUpdate::create($historyDeleteTypeCustomer);

                DB::commit();

                return redirect()
                    ->route("list_type_customer")
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
