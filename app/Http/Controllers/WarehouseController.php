<?php

namespace App\Http\Controllers;

use App\HistoryUpdate;
use App\RajaOngkir_City;
use App\RajaOngkir_Province;
use App\RajaOngkir_Subdistrict;
use App\User;
use App\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WarehouseController extends Controller
{
    public function fetchWarehouse(Request $request)
    {
        $warehouses = Warehouse::where('active', true)
            ->where('parent_warehouse_id', '!=', null);

        if ($request->warehouse_type) {
            $warehouses->where('type', $request->warehouse_type);
        }

        if ($request->check_parent == true) {
            $list_parent_warehouse = json_decode(Auth::user()->list_warehouse_id, true) ?? [];
            $warehouses->whereIn('parent_warehouse_id', $list_parent_warehouse);
        }

        $warehouses = $warehouses->get();
        return response()->json(["data" => $warehouses]);
    }

    public function index(Request $request)
    {
        $warehouses = Warehouse::where('active', true);

        if ($request->has("filter_name")) {
            $filterName = $request->filter_name;
            $warehouses = $warehouses->where(function ($q) use ($filterName) {
                $q->where('name', "like", "%" . $filterName . "%");
            });
        }

        if ($request->has("filter_warehouse_code")) {
            $filterCode = $request->filter_warehouse_code;
            $warehouses = $warehouses->where(function ($q) use ($filterCode) {
                $q->where('code', "like", "%" . $filterCode . "%");
            });
        }

        if ($request->filter_type) {
            $warehouses = $warehouses->where('type', $request->filter_type);
        }

        $warehouses = $warehouses->orderBy('code')->paginate(10);

        return view("admin.list_warehouse", compact("warehouses"))
            ->with('i', (request()->input('page', 1) - 1) * 10);
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
            "type",
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
        $warehouse = Warehouse::find($request->id);

        $parentWarehouses = Warehouse::select("id", "code", "name")
            ->where("parent_warehouse_id", null)
            ->orderBy("code")
            ->get();

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

        return view("admin.update_warehouse", compact("warehouse",
            "parentWarehouses",
            "provinces",
            "cities",
            "subdistricts",
        ));
    }

    public function update(Request $request)
    {
        DB::beginTransaction();

        try {
            $warehouse = Warehouse::find($request->id);
            $warehouse->fill($request->only(
                "code",
                "name",
                "address",
                "province_id",
                "city_id",
                "subdistrict_id",
                "description",
                "parent_warehouse_id",
                "type",
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

            return redirect()
                ->route("list_warehouse")
                ->with("success", "Warehouse successfully updated.");
        } catch (Throwable $th) {
            DB::rollBack();

            return response()->json(["error" => $th->getMessage()], 500);
        }
    }

    public function destroy(Request $request)
    {
        DB::beginTransaction();

        try {
            $warehouse = Warehouse::where('id', $request->id)->first();
            $warehouse->active = false;
            $warehouse->save();

            $userId = Auth::user()["id"];
            $history["type_menu"] = "Warehouse";
            $history["method"] = "Delete";
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

            return redirect()
                ->route("list_warehouse")
                ->with("success", "Warehouse successfully deleted.");
        } catch (Throwable $th) {
            DB::rollBack();

            return response()->json(["error" => $th->getMessage()], 500);
        }
    }
}
