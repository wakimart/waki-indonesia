<?php

namespace App\Http\Controllers;

use App\Branch;
use App\Cso;
use App\DataSourcing;
use App\HistoryUpdate;
use App\Imports\DataSourcingImport;
use App\Imports\TypeCustomerImport;
use App\TypeCustomer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class DataSourcingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $url = $request->all();

        $data_sourcings = DataSourcing::from('data_sourcings as ds')
            ->select('ds.*', 
                'b.code as b_code', 
                'c.code as c_code',
                'c.name as c_name',
                'tc.name as tc_name')
            ->leftJoin('branches as b', 'b.id', 'ds.branch_id')
            ->leftJoin('csos as c', 'c.id', 'ds.cso_id')
            ->leftJoin('type_customers as tc', 'tc.id', 'ds.type_customer_id')
            ->where('ds.active', true);

        if ($request->has('search')) {
            $data_sourcings->where('ds.name', 'LIKE', '%' . $request->search . '%');
            $data_sourcings->orWhere('b.code', 'LIKE', '%' . $request->search . '%');
            $data_sourcings->orWhere('c.code', 'LIKE', '%' . $request->search . '%');
            $data_sourcings->orWhere('c.name', 'LIKE', '%' . $request->search . '%');
            $data_sourcings->orWhere('tc.name', 'LIKE', '%' . $request->search . '%');
        }

        $countDataSourcings = $data_sourcings->count();
        $data_sourcings = $data_sourcings->paginate(10);

        return view(
            "admin.list_datasourcing",
            compact(
                "countDataSourcings",
                "data_sourcings",
                "url"
            )
        )
            ->with("i", (request()->input("page", 1) - 1) * 10 + 1);;    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $branches = Branch::where('active', true)->orderBy('code', 'asc')->get();
        $csos = Cso::where('active', true)->orderBy('code', 'asc')->get();
        $type_customers = TypeCustomer::where('active', true)->get();
        return view('admin.add_datasourcing', compact('branches', 'csos', 'type_customers'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $messages = array(
            'cso_id.required' => 'The CSO Code field is required.',
            'cso_id.exists' => 'Wrong CSO Code.',
            'branch_id.required' => 'The Branch must be selected.',
            'branch_id.exists' => 'Please choose the branch.',
            'type_customer_id.required' => 'The Type Customer must be selected.',
            'type_customer_id.exists' => 'Please choose the type customer.'
        );
        $validator = \Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required',
            'branch_id' => ['required', 'exists:branches,id'],
            'cso_id' => ['required', 'exists:csos,id'],
            'type_customer_id' => ['required', 'exists:type_customers,id'],
        ], $messages);
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
            $data['user_id'] = Auth::user()['id'];
            DataSourcing::create($data);

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
            $branches = Branch::where('active', true)->orderBy('code', 'asc')->get();
            $csos = Cso::where('active', true)->orderBy('code', 'asc')->get();
            $type_customers = TypeCustomer::where('active', true)->get();    
            $data_sourcings = DataSourcing::find($request->get('id'));
            return view('admin.update_datasourcing', compact('branches', 'csos', 'type_customers', 'data_sourcings'));
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
        $messages = array(
            'cso_id.required' => 'The CSO Code field is required.',
            'cso_id.exists' => 'Wrong CSO Code.',
            'branch_id.required' => 'The Branch must be selected.',
            'branch_id.exists' => 'Please choose the branch.',
            'type_customer_id.required' => 'The Type Customer must be selected.',
            'type_customer_id.exists' => 'Please choose the type customer.'
        );
        $validator = \Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required',
            'branch_id' => ['required', 'exists:branches,id'],
            'cso_id' => ['required', 'exists:csos,id'],
            'type_customer_id' => ['required', 'exists:type_customers,id'],
        ], $messages);
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
            $data_sourcing = DataSourcing::find($request->input('idDataSourcing'));
            $data_sourcing->name = $data['name'];
            $data_sourcing->phone = $data['phone'];
            $data_sourcing->address = $data['address'];
            $data_sourcing->branch_id = $data['branch_id'];
            $data_sourcing->cso_id = $data['cso_id'];
            $data_sourcing->type_customer_id = $data['type_customer_id'];
            $data_sourcing->user_id = Auth::user()['id'];
            $data_sourcing->save();

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
                $data_sourcing = DataSourcing::find($request->id);
                $data_sourcing->active = false;
                $data_sourcing->save();

                $user = Auth::user();
                $historyDeleteDataSourcing["type_menu"] = "Data Sourcing";
                $historyDeleteDataSourcing["method"] = "Delete";
                $historyDeleteDataSourcing["meta"] = json_encode(
                    [
                        "user" => $user["id"],
                        "createdAt" => date("Y-m-d H:i:s"),
                        "dataChange" => $data_sourcing->getChanges(),
                    ],
                    JSON_THROW_ON_ERROR
                );

                $historyDeleteDataSourcing["user_id"] = $user["id"];
                $historyDeleteDataSourcing["menu_id"] = $request->id;
                HistoryUpdate::create($historyDeleteDataSourcing);

                DB::commit();

                return redirect()
                    ->route("list_data_sourcing")
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

    public function importDataSourcing(Request $request)
    {
        return view('admin.import_datasourcing');
    }

    public function storeImportDataSourcing(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'importmenu' => 'required',
            'file' => 'required|mimes:csv,txt',
        ]);
        if($validator->fails()){
            $arr_Errors = $validator->errors()->all();
            $arr_Keys = $validator->errors()->keys();
            $arr_Hasil = [];
            for ($i = 0; $i < count($arr_Keys); $i++) {
                $arr_Hasil[$arr_Keys[$i]] = $arr_Errors[$i];
            }
            return response()->json(['errors' => $arr_Hasil]);
        } else {
            DB::beginTransaction();            
            try {
                $importmenu = $request->importmenu;
                $file = $request->file('file');
                
                if ($importmenu == "data_sourcing") {
                    Excel::import(new DataSourcingImport, $file);
                } else if ($importmenu == "type_customer") {
                    Excel::import(new TypeCustomerImport, $file);
                }
                DB::commit();

                return response()->json(['success' => 'Berhasil']);
            } catch (Exception $e) {
                DB::rollback();

                return response()->json([
                    "error" => $e,
                ], 500);                
            }
        }
    }
}
