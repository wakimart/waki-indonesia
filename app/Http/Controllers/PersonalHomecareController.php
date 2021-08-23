<?php

namespace App\Http\Controllers;

use App\Branch;
use App\Cso;
use App\HistoryUpdate;
use App\PersonalHomecare;
use App\PersonalHomecareChecklist;
use App\PersonalHomecareProduct;
use App\RajaOngkir_City;
use App\RajaOngkir_Province;
use App\RajaOngkir_Subdistrict;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PersonalHomecareController extends Controller
{
    public function index()
    {
        $personalhomecares = PersonalHomecare::where('active', true)
            ->paginate(10);

        return view("admin.list_all_personalhomecare", compact("personalhomecares"))
            ->with('i', (request()->input('page', 1) - 1) * 10);
    }

    public function phForm($id)
    {
        $personalhomecare = PersonalHomecare::find($id);

        return view('personal_homecare', compact('personalhomecare'));
    }

    public function create()
    {
        $branches = Branch::select("id", "code", "name")
            ->where("active", true)
            ->orderBy("code")
            ->get();

        $csos = Cso::select("id", "code", "name")
            ->where("active", true)
            ->get();

        $provinces = RajaOngkir_Province::select(
                "province_id AS id",
                "province AS name",
            )
            ->get();

        return view("admin.add_personal_homecare", compact(
            "branches",
            "csos",
            "provinces",
        ));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            // STORE PERSONAL HOMECARE CHECKLIST
            $phcChecklist = new PersonalHomecareChecklist();

            $condition["completeness"][] = $request->input("completeness");
            if ($request->has("other_completeness")) {
                $condition["other"] = $request->input("other_completeness");
            }
            $condition["machine"] = $request->input("machine_condition");
            $condition["physical"] = $request->input("physical_condition");
            $phcChecklist->condition = $condition;

            $imageArray = [];
            $userId = Auth::user()["id"];
            $path = "sources/phc-checklist";
            if ($request->hasFile("product_photo_1")) {
                $fileName = time()
                    . "_"
                    . $userId
                    . "_"
                    . "1."
                    . $request->file("product_photo_1")->getClientOriginalExtension();
                $request->file("product_photo_1")->move($path, $fileName);
                $imageArray[] = $fileName;
            }

            if ($request->hasFile("product_photo_2")) {
                $fileName = time()
                    . "_"
                    . $userId
                    . "_"
                    . "2."
                    . $request->file("product_photo_2")->getClientOriginalExtension();
                $request->file("product_photo_2")->move($path, $fileName);
                $imageArray[] = $fileName;
            }

            $phcChecklist->image = $imageArray;
            $phcChecklist->save();

            // STORE PERSONAL HOMECARE
            $personalHomecare = new PersonalHomecare();
            $personalHomecare->fill($request->only(
                "schedule",
                "name",
                "phone",
                "address",
                "province_id",
                "city_id",
                "subdistrict_id",
                "branch_id",
                "cso_id",
                "ph_product_id",
            ));

            if ($request->hasFile("id_card_image")) {
                $timestamp = (string) time();
                $fileName = $timestamp
                    . "."
                    . $request->file("id_card_image")->getClientOriginalExtension();

                $request->file("id_card_image")->move("sources/phc", $fileName);

                $personalHomecare->id_card = $fileName;
            }

            $personalHomecare->checklist_out = $phcChecklist->id;
            $personalHomecare->save();

            DB::commit();

            return redirect()
                ->route("add_personal_homecare")
                ->with("success", "Personal Homecare has been successfully created.");
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(["error" => $e->getMessage()], 500);
        }
    }

    public function getPhcProduct(Request $request)
    {
        try {
            $phcProducts = PersonalHomecareProduct::select(
                    "personal_homecare_products.id AS id",
                    "personal_homecare_products.code AS code",
                    "products.name AS name",
                )
                ->leftJoin(
                    "products",
                    "personal_homecare_products.product_id",
                    "=",
                    "products.id"
                )
                ->where("personal_homecare_products.branch_id", $request->branch_id)
                ->where("personal_homecare_products.status", true)
                ->where("personal_homecare_products.active", true)
                ->get();

            return response()->json(["data" => $phcProducts]);
        } catch (Exception $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }

    public function edit(Request $request)
    {
        if (empty($request->id)) {
            return redirect()
                ->route("list_all_phc")
                ->with("danger", "Data not found.");
        }

        $branches = Branch::select("id", "code", "name")
            ->where("active", true)
            ->get();

        $csos = Cso::select("id", "code", "name")
            ->where("active", true)
            ->get();

        $personalhomecare = PersonalHomecare::where("id", $request->id)
            ->with("checklistOut")
            ->first();

        $provinces = RajaOngkir_Province::select(
                "province_id AS id",
                "province AS name",
            )
            ->get();

        $cities = RajaOngkir_City::select(
                "city_id AS id",
                DB::raw("CONCAT(type, ' ', city_name) AS name"),
            )
            ->where("province_id", $personalhomecare->province_id)
            ->get();

        $subdistricts = RajaOngkir_Subdistrict::select(
                "subdistrict_id AS id",
                "subdistrict_name AS name",
            )
            ->where("province_id", $personalhomecare->province_id)
            ->get();

        $phcProducts = PersonalHomecareProduct::select(
                "personal_homecare_products.id AS id",
                "personal_homecare_products.code AS code",
                "products.name AS name",
            )
            ->leftJoin(
                "products",
                "personal_homecare_products.product_id",
                "=",
                "products.id"
            )
            ->where("personal_homecare_products.branch_id", $personalhomecare->branch_id)
            ->where("personal_homecare_products.active", true)
            ->get();

        return view("admin.update_personal_homecare", compact(
            "personalhomecare",
            "branches",
            "csos",
            "provinces",
            "cities",
            "subdistricts",
            "phcProducts",
        ));
    }

    public function update(Request $request)
    {
        DB::beginTransaction();

        try {
            // UPDATE PERSONAL HOMECARE
            $personalHomecare = PersonalHomecare::where("id", $request->id)->first();
            $personalHomecare->fill($request->only(
                "schedule",
                "name",
                "phone",
                "address",
                "province_id",
                "city_id",
                "subdistrict_id",
                "branch_id",
                "cso_id",
                "ph_product_id",
            ));

            if ($request->hasFile("id_card_image")) {
                $timestamp = (string) time();
                $fileName = $timestamp
                    . "."
                    . $request->file("id_card_image")->getClientOriginalExtension();

                $request->file("id_card_image")->move("sources/phc", $fileName);

                $personalHomecare->id_card = $fileName;
            }

            $personalHomecare->save();

            // UPDATE PERSONAL HOMECARE CHECKLIST OUT
            $phcChecklistOut = PersonalHomecareChecklist::where(
                    "id",
                    $personalHomecare->checklist_out
                )
                ->first();

            $condition["completeness"][] = $request->input("completeness");
            if ($request->has("other_completeness")) {
                $condition["other"] = $request->input("other_completeness");
            }
            $condition["machine"] = $request->input("machine_condition");
            $condition["physical"] = $request->input("physical_condition");
            $phcChecklistOut->condition = $condition;

            $imageArray = $phcChecklistOut->image;
            $userId = Auth::user()["id"];
            $path = "sources/phc-checklist";
            if ($request->hasFile("product_photo_1")) {
                $fileName = time()
                    . "_"
                    . $userId
                    . "_"
                    . "1."
                    . $request->file("product_photo_1")->getClientOriginalExtension();
                $request->file("product_photo_1")->move($path, $fileName);
                $imageArray[0] = $fileName;
            }

            if ($request->hasFile("product_photo_2")) {
                $fileName = time()
                    . "_"
                    . $userId
                    . "_"
                    . "2."
                    . $request->file("product_photo_2")->getClientOriginalExtension();
                $request->file("product_photo_2")->move($path, $fileName);
                $imageArray[1] = $fileName;
            }

            $phcChecklistOut->image = $imageArray;
            $phcChecklistOut->save();

            DB::commit();

            return redirect()
                ->route("detail_personal_homecare", ["id" => $request->id])
                ->with("success", "Personal Homecare has been successfully updated.");
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(["error" => $e->getMessage()], 500);
        }
    }

    public function detail(Request $request){
        $personalhomecare = PersonalHomecare::where("id", $request->id)->first();

        return view('admin.detail_personal_homecare', compact(
            'personalhomecare',
        ));
    }

    public function destroy(Request $request)
    {
        DB::beginTransaction();

        try {
            $phc = PersonalHomecare::where("id", $request->id)->first();
            $phc->active = false;
            $phc->save();

            $userId = Auth::user()["id"];
            $historyDeletePhc["type_menu"] = "Personal Homecare";
            $historyDeletePhc["method"] = "Delete";
            $historyDeletePhc["meta"] = json_encode(
                [
                    "user" => $userId,
                    "createdAt" => date("Y-m-d H:i:s"),
                    "dataChange" => $phc->getChanges(),
                ],
                JSON_THROW_ON_ERROR
            );
            $historyDeletePhc["user_id"] = $userId;
            $historyDeletePhc["menu_id"] = $request->id;
            HistoryUpdate::create($historyDeletePhc);

            DB::commit();

            return redirect()
                ->route("list_all_phc")
                ->with("success", "Personal Homecare has been successfully deleted.");
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(["error" => $e->getMessage()], 500);
        }
    }
}
