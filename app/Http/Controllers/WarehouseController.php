<?php

namespace App\Http\Controllers;

use App\User;
use App\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
}
