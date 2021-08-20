<?php

namespace App\Http\Controllers;

use App\Branch;
use App\Cso;
use App\HistoryUpdate;
use App\PersonalHomecare;
use App\PersonalHomecareChecklist;
use App\PersonalHomecareProduct;
use App\RajaOngkir_Province;
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

    public function phForm($id){
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

            $timestamp = (string) time();
            if ($request->hasFile("id_card_image")) {
                $request->file("id_card_image")->move(
                    "sources/phc",
                    $timestamp
                    . "."
                        . $request->file("id_card_image")->getClientOriginalExtension()
                );
            }

            $personalHomecare->id_card = $timestamp;
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

        $provinces = RajaOngkir_Province::select(
                "province_id AS id",
                "province AS name",
            )
            ->get();

        $personalhomecare = PersonalHomecare::where("id", $request->id)->first();

        return view("admin.update_personal_homecare", compact(
            "personalhomecare",
            "branches",
            "csos",
            "provinces",
        ));
    }

    public function update(Request $request)
    {
        //
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
