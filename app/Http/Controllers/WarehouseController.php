<?php

namespace App\Http\Controllers;

use App\HistoryUpdate;
use App\RajaOngkir_City;
use App\RajaOngkir_Province;
use App\RajaOngkir_Subdistrict;
use App\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class WarehouseController extends Controller
{
    public function index(Request $request)
    {
        //
    }

    public function create()
    {
        $parentWarehouses = Warehouse::select("id", "code", "name")
            ->where("parent_warehouse_id", null)
            ->orderBy("code")
            ->get();

        $provinces = RajaOngkir_Province::select(
                "province_id AS id",
                "province AS name",
            )
            ->get();

        return view("admin.add_warehouse", compact(
            "parentWarehouses",
            "provinces",
        ));
    }

    public function store(Request $request)
    {
        Warehouse::create($request->only(
            "code",
            "name",
            "address",
            "province_id",
            "city_id",
            "subdistrict_id",
            "description",
            "parent_warehouse_id",
        ));

        return redirect()
            ->route("add_warehouse")
            ->with("success", "Warehouse successfully added.");
    }

    public function show(Request $request)
    {
        //
    }

    public function edit(Request $request)
    {
        $warehouse = Warehouse::where($request->id)->first();

        $provinces = RajaOngkir_Province::select(
                "province_id AS id",
                "province AS name",
            )
            ->get();

        $cities = RajaOngkir_City::select(
                "city_id AS id",
                DB::raw("CONCAT(type, ' ', city_name) AS name"),
            )
            ->where("province_id", $warehouse->province_id)
            ->get();

        $subdistricts = RajaOngkir_Subdistrict::select(
                "subdistrict_id AS id",
                "subdistrict_name AS name",
            )
            ->where("province_id", $warehouse->province_id)
            ->get();

        return view("admin.edit_warehouse", compact("warehouse"));
    }

    public function update(Request $request)
    {
        DB::beginTransaction();

        try {
            $warehouse = Warehouse::where($request->id)->first();
            $warehouse->fill($request->only(
                "code",
                "name",
                "address",
                "province_id",
                "city_id",
                "subdistrict_id",
                "description",
                "parent_warehouse_id",
            ));
            $warehouse->save();

            $userId = Auth::user()["id"];
            $history["type_menu"] = "Warehouse";
            $history["method"] = "Update";
            $history["meta"] = json_encode(
                [
                    "user" => $userId,
                    "createdAt" => date("Y-m-d H:i:s"),
                    "dataChange" => $warehouse->getChanges(),
                ],
                JSON_THROW_ON_ERROR
            );
            $history["user_id"] = $userId;
            $history["menu_id"] = $request->id;
            HistoryUpdate::create($history);

            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();

            return response()->json(["error" => $th->getMessage()], 500);
        }
    }

    public function destroy(Request $request)
    {
        //
    }
}
