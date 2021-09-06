<?php

namespace App\Http\Controllers;

use App\RajaOngkir_Province;
use App\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
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
        //
    }

    public function update(Request $request)
    {
        //
    }

    public function destroy(Request $request)
    {
        //
    }
}
