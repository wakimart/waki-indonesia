<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\CommissionType;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use App\HistoryUpdate;

class CommissionTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $url = $request->all();
        $datas = CommissionType::where('active', true);

        if ($request->has('search')) {
            $datas->where(function($q) use($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                ->orWhere('description', 'like', '%'.$request->search.'%');
            });
        }
        $datas = $datas->paginate(10);

        return view("admin.list_commission_type", compact("datas", "url"))->with("i", (request()->input("page", 1) - 1) * 10 + 1);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.add_commission_type');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'takeaway' => 'required',
            'upgrade' => 'required',
            'nominal' => 'required',
            'smgt_nominal' => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }else{
            DB::beginTransaction();
            try {
                CommissionType::create($request->all());
                DB::commit();
                return Redirect::back()->with("success", "Commission type successfully added.");
            } catch (\Exception $ex) {
                DB::rollBack();
                return Redirect::back()->withErrors("Something wrong when add commission type, please call Team IT")->withInput();
            }
        }
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
    public function edit($id)
    {
        $commissionType = CommissionType::find($id);
        return view('admin.update_commission_type', compact('commissionType'));
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
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'takeaway' => 'required',
            'upgrade' => 'required',
            'nominal' => 'required',
            'smgt_nominal' => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }else{
            DB::beginTransaction();
            try {
                $dataBefore = CommissionType::find($request->id);
                $commissionType = CommissionType::find($request->id);
                $commissionType->fill($request->all())->save();

                $user = Auth::user();
                $historyUpdateCommissionType["type_menu"] = "Commission Type";
                $historyUpdateCommissionType["method"] = "Update";
                $historyUpdateCommissionType["meta"] = json_encode(
                    [
                        "user" => $user["id"],
                        "createdAt" => date("Y-m-d H:i:s"),
                        "dataChange" => array_diff(json_decode($commissionType, true), json_decode($dataBefore,true)),
                    ],
                    JSON_THROW_ON_ERROR
                );

                $historyUpdateCommissionType["user_id"] = $user["id"];
                $historyUpdateCommissionType["menu_id"] = $request->id;
                HistoryUpdate::create($historyUpdateCommissionType);

                DB::commit();
                return Redirect::back()->with("success", "Commission type successfully updated.");
            } catch (\Exception $ex) {
                DB::rollBack();
                return Redirect::back()->withErrors("Something wrong when update commission type, please call Team IT")->withInput();
                // return Redirect::back()->withErrors($ex->getMessage());
            }
        }
    }

    /**
     * deactivate commission type.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        DB::beginTransaction();

        if (!empty($request->id)) {
            try {
                $commissionType = CommissionType::find($request->id);
                $commissionType->active = false;
                $commissionType->save();

                $user = Auth::user();
                $historyDeleteCommissionType["type_menu"] = "Commission Type";
                $historyDeleteCommissionType["method"] = "Delete";
                $historyDeleteCommissionType["meta"] = json_encode(
                    [
                        "user" => $user["id"],
                        "createdAt" => date("Y-m-d H:i:s"),
                        "dataChange" => $commissionType->getChanges(),
                    ],
                    JSON_THROW_ON_ERROR
                );

                $historyDeleteCommissionType["user_id"] = $user["id"];
                $historyDeleteCommissionType["menu_id"] = $request->id;
                HistoryUpdate::create($historyDeleteCommissionType);

                DB::commit();

                return Redirect::back()->with("success", "Commission type deleted successfully.");
            } catch (Exception $e) {
                DB::rollback();
                return Redirect::back()->withErrors("Something wrong when delete commission type, please call Team IT");
            }
        }

        return Redirect::back()->withErrors("Data not found");
    }
}
