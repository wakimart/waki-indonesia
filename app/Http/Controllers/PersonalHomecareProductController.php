<?php

namespace App\Http\Controllers;

use App\Branch;
use App\HistoryUpdate;
use App\PersonalHomecareProduct;
use App\PersonalHomecareChecklist;
use App\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PersonalHomecareProductController extends Controller
{
    public function index(Request $request)
    {
        $branches = Branch::select("id", "code", "name")
            ->where("active", true)
            ->orderBy("code")
            ->get();

        $products = Product::select("id", "code", "name")
            ->where("active", true)
            ->orderBy("code")
            ->get();

        $phcproducts = PersonalHomecareProduct::where('active', true)
            ->orderBy('code', 'asc');

        if ($request->has("branch_id")) {
            $phcproducts = $phcproducts->where('branch_id', $request->input("branch_id"));
        }

        if ($request->has("status")) {
            $phcproducts = $phcproducts->where('status', $request->input("status"));
        }

        if ($request->has("product_id")) {
            $phcproducts = $phcproducts->where('product_id', $request->input("product_id"));
        }
        else{
            $request['product_id'] = 4;
            $phcproducts = $phcproducts->where('product_id', $request->input("product_id"));
        }

        $phcproducts = $phcproducts->with(["branch", "product"])->paginate(10);

        $url = $request->all();

        return view("admin.list_homecareproduct", compact(
                "branches",
                "products",
                "phcproducts",
                "url",
            ))
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
        // dd($request->all());
        DB::beginTransaction();

        try {

            // STORE PERSONAL HOMECARE CHECKLIST Product
            $phcChecklist = new PersonalHomecareChecklist();
            $condition["completeness"] = $request->input("completeness");
            if ($request->has("other_completeness")) {
                $condition["other"] = $request->input("other_completeness");
            }
            $condition["machine"] = $request->input("machine_condition");
            $condition["physical"] = $request->input("physical_condition");
            $phcChecklist->condition = $condition;
            $phcChecklist->image = [];
            $phcChecklist->save();


            PersonalHomecareProduct::create([
                "code" => $request->input("code"),
                "branch_id" => $request->input("branch_id"),
                "product_id" => $request->input("product_id"),
                "current_checklist_id" => $phcChecklist->id,
                "status" => "pending",
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

    public function show($id){
        $phcproducts = PersonalHomecareProduct::where("id", $id)->first();

        if(substr($phcproducts['code'], 1, 1) == "1"){
            $phcproducts['warehouse'] = "Surabaya";
        }
        elseif(substr($phcproducts['code'], 1, 1) == "2"){
            $phcproducts['warehouse'] = "Semarang";
        }
        else{
            $phcproducts['warehouse'] = "Jakarta";
        }

        $histories = HistoryUpdate::select(
                "history_updates.method AS method",
                "history_updates.created_at AS created_at",
                "history_updates.meta AS meta",
                "users.name AS name"
            )
            ->leftJoin(
                "users",
                "users.id",
                "=",
                "history_updates.user_id"
            )
            ->where("history_updates.type_menu", "like", "%Personal Homecare Product%")
            ->where("history_updates.menu_id", $id)
            ->get();

        return view("admin.detail_phc_product", compact(
            "phcproducts",
            "histories",
        ));
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

    public function update(Request $request)
    {
        DB::beginTransaction();

        try {
            if(isset($request->status)){
                $phcProduct = PersonalHomecareProduct::where("id", $request->id)->first();
                $phcProduct->fill($request->only(
                    "status",
                ));
                $phcProduct->save();

                $userId = Auth::user()["id"];
                if(sizeof($phcProduct->getChanges()) > 0){
                    HistoryUpdate::create([
                        "type_menu" => "Personal Homecare Product",
                        "method" => "update",
                        "meta" => json_encode(
                            [
                                "user" => $userId,
                                "createdAt" => date("Y-m-d H:i:s"),
                                "dataChange" => $phcProduct->getChanges(),
                            ],
                            JSON_THROW_ON_ERROR
                        ),
                        "user_id" => $userId,
                        "menu_id" => $request->id,
                    ]);
                }
            }
            else{
                $phcProduct = PersonalHomecareProduct::where("id", $request->id)->first();
                $phcProduct->fill($request->only(
                    "branch_id",
                ));
                $phcProduct->save();

                $phcProductChecklist = PersonalHomecareChecklist::find($phcProduct->current_checklist_id);

                // UPDATE PERSONAL HOMECARE CHECKLIST Product
                $phcChecklist = PersonalHomecareChecklist::find($phcProduct->current_checklist_id);
                $condition["completeness"] = $request->input("completeness");
                if ($request->has("other_completeness")) {
                    $condition["other"] = $request->input("other_completeness");
                }
                $condition["machine"] = $request->input("machine_condition");
                $condition["physical"] = $request->input("physical_condition");
                $phcChecklist->condition = $condition;
                if(sizeof($phcChecklist->image) < 1){
                    $phcChecklist->image = [];
                }
                $phcChecklist->save();

                $userId = Auth::user()["id"];
                if(sizeof($phcProduct->getChanges()) > 0){
                    HistoryUpdate::create([
                        "type_menu" => "Personal Homecare Product",
                        "method" => "update",
                        "meta" => json_encode(
                            [
                                "user" => $userId,
                                "createdAt" => date("Y-m-d H:i:s"),
                                "dataChange" => $phcProduct->getChanges(),
                            ],
                            JSON_THROW_ON_ERROR
                        ),
                        "user_id" => $userId,
                        "menu_id" => $request->id,
                    ]);
                }
                

                if(sizeof($phcChecklist->getChanges()) > 0){
                    HistoryUpdate::create([
                    "type_menu" => "Personal Homecare Product",
                        "method" => "update",
                        "meta" => json_encode(
                            [
                                "user" => $userId,
                                "createdAt" => date("Y-m-d H:i:s"),
                                "dataChange" => $phcChecklist->getChanges(),
                            ],
                            JSON_THROW_ON_ERROR
                        ),
                        "user_id" => $userId,
                        "menu_id" => $request->id,
                    ]);
                }
            }            

            DB::commit();

            return redirect()
                ->route('detail_phc_product', ['id' => $phcProduct['id']])
                ->with("success", "Successfully update Personal Homecare Product.");
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(["error" => $e->getMessage()], 500);
        }
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
