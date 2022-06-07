<?php

namespace App\Http\Controllers;

use App\Branch;
use App\Cso;
use App\DataTherapy;
use App\HistoryUpdate;
use App\TypeCustomer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class DataTherapyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $url = $request->all();

        $data_therapies = DataTherapy::from('data_therapies as dt')
            ->select('dt.*', 
                'b.code as b_code', 
                'c.code as c_code',
                'c.name as c_name',
                'tc.name as tc_name')
            ->leftJoin('branches as b', 'b.id', 'dt.branch_id')
            ->leftJoin('csos as c', 'c.id', 'dt.cso_id')
            ->leftJoin('type_customers as tc', 'tc.id', 'dt.type_customer_id')
            ->where('dt.active', true);

        if ($request->has('search')) {
            $data_therapies->where('dt.name', 'LIKE', '%' . $request->search . '%');
            $data_therapies->orWhere('dt.no_ktp', 'LIKE', '%' . $request->search . '%');
            $data_therapies->orWhere('b.code', 'LIKE', '%' . $request->search . '%');
            $data_therapies->orWhere('c.code', 'LIKE', '%' . $request->search . '%');
            $data_therapies->orWhere('c.name', 'LIKE', '%' . $request->search . '%');
            $data_therapies->orWhere('tc.name', 'LIKE', '%' . $request->search . '%');
        }

        $countDataTherapies = $data_therapies->count();
        $data_therapies = $data_therapies->paginate(10);

        return view(
            "admin.list_datatherapy",
            compact(
                "countDataTherapies",
                "data_therapies",
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
        $branches = Branch::where('active', true)->orderBy('code', 'asc')->get();
        $csos = Cso::where('active', true)->orderBy('code', 'asc')->get();
        $type_customers = TypeCustomer::where('active', true)->get();
        return view('admin.add_datatherapy', compact('branches', 'csos', 'type_customers'));
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
            'no_ktp' => 'required',
            'phone' => 'required',
            'branch_id' => ['required', 'exists:branches,id'],
            'cso_id' => ['required', 'exists:csos,id'],
            'type_customer_id' => ['required', 'exists:type_customers,id'],
            'image' => 'required|mimes:jpg,jpeg,png',
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

            if ($request->hasFile('image')) {
                $image = $request->file("image");
                $imageName = $request->no_ktp . "." . $image->getClientOriginalExtension();
                $image->move("sources/therapy_images", $imageName);
                $data['img_ktp'] = $imageName;
            }

            DataTherapy::create($data);

            return response()->json(['success' => 'Berhasil']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        if($request->has('id')){
            $data_therapy = DataTherapy::find($request->get('id'));
            return view('admin.detail_datatherapy', compact('data_therapy'));
        }else{
            return response()->json(['result' => 'Gagal!!']);
        }
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
            $data_therapy = DataTherapy::find($request->get('id'));
            return view('admin.update_datatherapy', compact('branches', 'csos', 'type_customers', 'data_therapy'));
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
            'no_ktp' => 'required',
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
            $data_therapy = DataTherapy::find($request->input('idDataTherapy'));
            $data_therapy->name = $data['name'];
            $data_therapy->phone = $data['phone'];
            $data_therapy->address = $data['address'];
            $data_therapy->branch_id = $data['branch_id'];
            $data_therapy->cso_id = $data['cso_id'];
            $data_therapy->type_customer_id = $data['type_customer_id'];
            $data_therapy->user_id = Auth::user()['id'];

            if ($request->hasFile('image')) {
                if (File::exists("sources/therapy_images/" . $data_therapy->img_ktp)) {
                    File::delete("sources/therapy_images/" . $data_therapy->img_ktp);
                }

                $image = $request->file("image");
                $imageName = $request->no_ktp . "." . $image->getClientOriginalExtension();
                $image->move("sources/therapy_images", $imageName);
                $data_therapy->img_ktp = $imageName;
            }

            $data_therapy->save();

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
                $data_therapy = DataTherapy::find($request->id);
                $data_therapy->active = false;
                $data_therapy->save();

                $user = Auth::user();
                $historyDeleteDataTherapy["type_menu"] = "Data Therapy";
                $historyDeleteDataTherapy["method"] = "Delete";
                $historyDeleteDataTherapy["meta"] = json_encode(
                    [
                        "user" => $user["id"],
                        "createdAt" => date("Y-m-d H:i:s"),
                        "dataChange" => $data_therapy->getChanges(),
                    ],
                    JSON_THROW_ON_ERROR
                );

                $historyDeleteDataTherapy["user_id"] = $user["id"];
                $historyDeleteDataTherapy["menu_id"] = $request->id;
                HistoryUpdate::create($historyDeleteDataTherapy);

                DB::commit();

                return redirect()
                    ->route("list_data_therapy")
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
