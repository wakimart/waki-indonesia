<?php

namespace App\Http\Controllers;

use App\Branch;
use App\PersonalHomecareProduct;
use App\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PersonalHomecareProductController extends Controller
{
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
                ->where("code", $request->code . "%")
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
}
