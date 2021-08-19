<?php

namespace App\Http\Controllers;

use App\Branch;
use App\HistoryUpdate;
use App\PersonalHomecareProduct;
use App\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PersonalHomecareProductController extends Controller
{
    public function index()
    {
        $phcproducts = PersonalHomecareProduct::where('active', true)
            ->orderBy('code', 'asc')
            ->paginate(10);

        return view("admin.list_homecareproduct", compact("phcproducts"))
            ->with('i', (request()->input('page', 1) - 1) * 10);
    }

    public function create()
    {
        $branches = Branch::select("id", "code", "name")
            ->where("active", true)
            ->get();

        $products = Product::select("id", "code", "name")
            ->where("active", true)
            ->get();

        return view("admin.add_phc_product", compact("branches", "products"));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            PersonalHomecareProduct::create([
                "code" => $request->input("code"),
                "branch_id" => $request->input("branch_id"),
                "product_id" => $request->input("product_id"),
                "status" => $request->input("status"),
            ]);

            DB::commit();

            return redirect()
                ->route("add_phc_product")
                ->with("success", "Successfully add product to Personal Homecare.");
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(["error" => $e->getMessage()], 500);
        }
    }

    public function getProductIncrement(Request $request)
    {
        try {
            $phcProduct = PersonalHomecareProduct::select("id", "code")
                ->where("code", "like", $request->code . "%")
                ->orderBy("id", "desc")
                ->first();

            if ($phcProduct) {
                $phcProductSanitized = filter_var($phcProduct->code, FILTER_SANITIZE_NUMBER_FLOAT);
                $phcProductIncrement = ((int) substr($phcProductSanitized, -3)) + 1;

                return response()->json([
                    "data" => $phcProductIncrement,
                ]);
            }

            return response()->json(["data" => 1]);
        } catch (Exception $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }

    public function edit(Request $request)
    {
        if (empty($request->id)) {
            return redirect()
                ->route("list_phc_product")
                ->with("danger", "Data not found.");
        }

        $branches = Branch::select("id", "code", "name")
            ->where("active", true)
            ->get();

        $products = Product::select("id", "code", "name")
            ->where("active", true)
            ->get();

        $phcproducts = PersonalHomecareProduct::where("id", $request->id)->first();

        return view("admin.update_phc_product", compact(
            "phcproducts", "branches", "products",
        ));
    }

    public function destroy(Request $request)
    {
        DB::beginTransaction();

        try {
            $phcProduct = PersonalHomecareProduct::where("id", $request->id)
                ->update(["active" => false]);

            $user = Auth::user();
            HistoryUpdate::create([
                "type_menu" => "Personal Homecare Product",
                "method" => "delete",
                "meta" => json_encode(
                    [
                        "user" => $user["id"],
                        "createdAt" => date("Y-m-d H:i:s"),
                        "dataChange" => $phcProduct->getChanges(),
                    ],
                    JSON_THROW_ON_ERROR
                ),
                "user_id" => $user["id"],
                "menu_id" => $request->id,
            ]);

            DB::commit();

            return redirect()
                ->route("list_phc_product")
                ->with("success", "Personal Homecare Product has been successfully deleted.");
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(["error" => $e->getMessage()], 500);
        }
    }
}
