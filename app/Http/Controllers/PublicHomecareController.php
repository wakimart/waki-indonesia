<?php

namespace App\Http\Controllers;

use App\Branch;
use App\Cso;
use App\HistoryUpdate;
use App\PersonalHomecare;
use App\PersonalHomecareChecklist;
use App\PersonalHomecareProduct;
use App\PublicHomecare;
use App\PublicHomecareProduct;
use App\RajaOngkir_City;
use App\RajaOngkir_Province;
use App\RajaOngkir_Subdistrict;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class PublicHomecareController extends Controller
{
    public function index(Request $request)
    {
        $publichomecares = PublicHomecare::where('active', true);

        $csos = Cso::select("id", "code", "name")
            ->where("active", true)
            ->orderBy("code", 'asc')
            ->get();

        $branches = Branch::select("id", "code", "name")
            ->where("active", true)
            ->orderBy("code", 'asc')
            ->get();

        if ($request->has("filter_name")) {
            $filterName = $request->filter_name;
            $publichomecares = $publichomecares->where(function ($q) use ($filterName) {
                $q->where('name', "like", "%" . $filterName . "%");
            });
        }

        if ($request->has("filter_status")) {
            $publichomecares = $publichomecares->where('status', $request->filter_status);
        }

        if ($request->has("filter_branch")) {
            $publichomecares = $publichomecares->where('branch_id', $request->filter_branch);
        }
        if ($request->has("filter_cso")) {
            $publichomecares = $publichomecares->where('cso_id', $request->filter_cso);
        }

        if ($request->has('filter_product_code')) {
            $id_productNya = PersonalHomecareProduct::where('code', "like", "%" . $request->filter_product_code . "%")->first();
            if ($id_productNya != null) {
                $publichomecares = $publichomecares->whereHas('publicHomecareProduct', function($q) use ($id_productNya) {
                    $q->where("ph_product_id", $id_productNya['id']);
                });
            } else {
                $publichomecares = $publichomecares->whereHas('publicHomecareProduct', function($q) use ($request) {
                    $q->where("ph_product_id", $request->filter_product_code);
                });
            }
        }

        if ($request->has("filter_schedule")) {
            $publichomecares = $publichomecares->where('start_date', '<=', $request->filter_schedule)
                ->where('end_date', '>=', $request->filter_schedule);
        }

        if (Auth::user()->roles[0]->slug === "cso") {
            $publichomecares = $publichomecares->where("cso_id", Auth::user()->cso["id"]);
        }
        else if (Auth::user()->roles[0]->slug === "branch" || Auth::user()->roles[0]->slug === "area-manager") {
            $arrBranches = [];
            foreach (Auth::user()->listBranches() as $value) {
                $arrBranches[] = $value['id'];
            }
            $publichomecares = $publichomecares->whereIn('branch_id', $arrBranches);
        }

        $publichomecares = $publichomecares->orderBy('created_at', "desc")->paginate(10);

        $url = $request->all();

        return view("admin.list_all_publichomecare", compact(
            "publichomecares",
            "csos",
            "branches",
            "url"))
            ->with('i', (request()->input('page', 1) - 1) * 10
        );
    }

    public function puhForm($id)
    {
        $publichomecare = PublicHomecare::find($id);

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
        ->where("history_updates.type_menu", "like", "%Public Homecare%")
        ->where("history_updates.menu_id", $id)
        ->get();

        return view('public_homecare', compact('publichomecare', 'histories'));
    }

    public function thankyouForm($id)
    {
        $publichomecare = PublicHomecare::find($id);

        return view('thankyou_form_puh', compact('publichomecare'));
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

        return view("admin.add_public_homecare", compact(
            "branches",
            "csos",
            "provinces",
        ));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            // STORE PUBLIC HOMECARE
            $publicHomecare = new PublicHomecare();
            $publicHomecare->fill($request->only(
                "start_date",
                "end_date",
                "name",
                "phone",
                "address",
                "province_id",
                "city_id",
                "district_id",
                "branch_id",
                "cso_id",
                "cso_optional_id",
                "other_product",
            ));

            if ($request->hasFile("approval_letter")) {
                $timestamp = (string) time();
                $fileName = $timestamp
                    . "."
                    . $request->file("approval_letter")->getClientOriginalExtension();

                $request->file("approval_letter")->move("sources/puhc", $fileName);

                $publicHomecare->approval_letter = $fileName;
            }

            $publicHomecare->save();

            //store public homecare product
            $products_idx = $request->product_idx ?? [];
            foreach ($products_idx as $p_idx) {
                $publicHomecareProduct = new PublicHomecareProduct;
                $publicHomecareProduct->public_homecare_id = $publicHomecare->id;
                $publicHomecareProduct->ph_product_id = $request->get('ph_product_id_'.$p_idx);
                $publicHomecareProduct->save();
            }
            //end store public homecare product

            DB::commit();

            // TODO notif
            // $this->accNotif($publicHomecare, "acc_ask");

            return redirect()
                ->route("add_public_homecare")
                ->with("success", "Public Homecare has been successfully created.");
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(["error" => $e->getMessage()], 500);
        }
    }

    public function getPuhcProduct(Request $request)
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
                ->where("personal_homecare_products.active", true)
                ->where("personal_homecare_products.status", "!=", "pending")
                ->where("personal_homecare_products.product_id", "1")
                ->get();

            $finalPhcProd = [];
            $firstDate = (new Carbon($request->start_date))->subDays(6);
            $lastDate =  (new Carbon($request->end_date))->addDays(6);

            $firstDatePuhc = (new Carbon($request->start_date))->subDays(1);
            $lastDatePuhc = (new Carbon($request->end_date))->addDays(1);

            foreach ($phcProducts as $perProd) {
                $phcNya = PersonalHomecare::where('ph_product_id', $perProd['id'])
                        ->whereBetween('schedule', [$firstDate, $lastDate])
                        ->where("active", true)
                        ->get();
                $puhcNya = PublicHomecareProduct::from('public_homecare_products as puhcp')
                        ->join('public_homecares as puhc', 'puhcp.public_homecare_id', 'puhc.id')
                        ->where('puhcp.ph_product_id', $perProd['id'])
                        ->where(function($query) use ($firstDatePuhc, $lastDatePuhc) {
                            $query->where(function($query2) use ($firstDatePuhc, $lastDatePuhc) {
                                $query2->where('puhc.start_date', '<=', $firstDatePuhc)
                                    ->where('puhc.end_date', '>=', $lastDatePuhc);
                            });
                            $query->orWhere(function($query2) use ($firstDatePuhc, $lastDatePuhc) {
                                $query2->whereBetween('start_date', [$firstDatePuhc, $lastDatePuhc])
                                    ->orWhereBetween('end_date', [$firstDatePuhc, $lastDatePuhc]);
                            });
                        })
                        ->where("puhc.active", true)
                        ->where("puhc.status", "!=", "rejected")
                        ->first();
                if(sizeof($phcNya) == 0 && $puhcNya != true){
                    array_push($finalPhcProd, $perProd);
                }
            }

            return response()->json(["data" => $finalPhcProd]);
        } catch (Exception $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }

    public function checkPhone(Request $request)
    {
        try {
            $query = PublicHomecare::select("id", "phone", "created_at")
                ->where("active", true)
                ->where("phone", $request->phone)
                ->orderBy("id", "desc")
                ->first();

            if (!empty($query)) {
                $queryDate = strtotime($query->created_at);
                $currentDate = strtotime("now");
                $difference = $currentDate - $queryDate;

                // 2 weeks = 1209600 seconds
                if ($difference < 1209600) {
                    return response()->json([
                        "result" => 0,
                        "data" => "Phone number is not eligible for Public Homecare."
                    ]);
                }
            }

            return response()->json([
                "result" => 1,
                "data" => "Phone number is eligible for Public Homecare."
            ]);
        } catch (Throwable $th) {
            return response()->json(["error" => $th->getMessage()], 500);
        }
    }

    public function edit(Request $request)
    {
        if (empty($request->id)) {
            return redirect()
                ->route("list_all_puhc")
                ->with("danger", "Data not found.");
        }

        $branches = Branch::select("id", "code", "name")
            ->where("active", true)
            ->get();

        $csos = Cso::select("id", "code", "name")
            ->where("active", true)
            ->get();

        $publichomecare = PublicHomecare::where("id", $request->id)->first();

        $provinces = RajaOngkir_Province::select(
                "province_id AS id",
                "province AS name",
            )
            ->get();

        $cities = RajaOngkir_City::select(
                "city_id AS id",
                DB::raw("CONCAT(type, ' ', city_name) AS name"),
            )
            ->where("province_id", $publichomecare->province_id)
            ->get();

        $subdistricts = RajaOngkir_Subdistrict::select(
                "subdistrict_id AS id",
                "subdistrict_name AS name",
            )
            ->where("province_id", $publichomecare->province_id)
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
            ->where("personal_homecare_products.branch_id", $publichomecare->branch_id)
            ->where("personal_homecare_products.active", true)
            ->where("personal_homecare_products.status", "!=", "pending")
            ->get();

        $ownProd = PersonalHomecareProduct::select(
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
            ->whereIn("personal_homecare_products.id", $publichomecare->publicHomecareProduct->pluck('ph_product_id')->toArray())
            ->get();

        $finalPhcProd = $ownProd->toArray();
        $firstDate = (new Carbon($publichomecare->start_date))->subDays(6);
        $lastDate =  (new Carbon($publichomecare->end_date))->addDays(6);

        $firstDatePuhc = (new Carbon($publichomecare->start_date))->subDays(1);
        $lastDatePuhc = (new Carbon($publichomecare->end_date))->addDays(1);

        foreach ($phcProducts as $perProd) {
            $phcNya = PersonalHomecare::where('ph_product_id', $perProd['id'])
                    ->whereBetween('schedule', [$firstDate, $lastDate])
                    ->where("active", true)
                    ->get();
            $puhcNya = PublicHomecareProduct::from('public_homecare_products as puhcp')
                    ->join('public_homecares as puhc', 'puhcp.public_homecare_id', 'puhc.id')
                    ->where('puhcp.ph_product_id', $perProd['id'])
                    ->where(function($query) use ($firstDatePuhc, $lastDatePuhc) {
                        $query->where(function($query2) use ($firstDatePuhc, $lastDatePuhc) {
                            $query2->where('puhc.start_date', '<=', $firstDatePuhc)
                                ->where('puhc.end_date', '>=', $lastDatePuhc);
                        });
                        $query->orWhere(function($query2) use ($firstDatePuhc, $lastDatePuhc) {
                            $query2->whereBetween('start_date', [$firstDatePuhc, $lastDatePuhc])
                                ->orWhereBetween('end_date', [$firstDatePuhc, $lastDatePuhc]);
                        });
                    })
                    ->where("puhc.active", true)
                    ->where("puhc.status", "!=", "rejected")
                    ->first();
            if(sizeof($phcNya) == 0 && $puhcNya != true){
                array_push($finalPhcProd, $perProd);
            }
        }

        $phcProducts = $finalPhcProd;

        return view("admin.update_public_homecare", compact(
            "publichomecare",
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
            $publicHomecare = PublicHomecare::where("id", $request->id)->first();
            $dataBefore = PublicHomecare::where("id", $request->id)->first();
            if($publicHomecare['status'] == "new"){
                $publicHomecare->fill($request->only(
                    "start_date",
                    "end_date",
                    "name",
                    "phone",
                    "address",
                    "province_id",
                    "city_id",
                    "district_id",
                    "branch_id",
                    "cso_id",
                    "cso_optional_id",
                    "other_product",
                ));
            } else {
                $publicHomecare->fill($request->only(
                    "name",
                    "phone",
                    "address",
                    "province_id",
                    "city_id",
                    "district_id",
                    "branch_id",
                    "cso_id",
                    "cso_optional_id",
                    "other_product",
                ));
            }

            if ($request->hasFile("approval_letter")) {
                if (File::exists("sources/puhc/" . $publicHomecare->approval_letter)) {
                    File::delete("sources/puhc/" . $publicHomecare->approval_letter);
                }
                $timestamp = (string) time();
                $fileName = $timestamp
                    . "."
                    . $request->file("approval_letter")->getClientOriginalExtension();

                $request->file("approval_letter")->move("sources/puhc", $fileName);

                $publicHomecare->approval_letter = $fileName;
            }            
            $publicHomecare->save();

            $publicHomecareProducts = PublicHomecareProduct::where('public_homecare_id', $request->id)->get();

            //update public homecare product
            $products_idx = $request->product_idx ?? [];
            foreach ($products_idx as $p_idx) {
                $publicHomecareProduct = new PublicHomecareProduct;
                if ($request->get('old_product_id_'.$p_idx)) {
                    $publicHomecareProduct = PublicHomecareProduct::find($request->get('old_product_id_'.$p_idx));
                }
                $publicHomecareProduct->public_homecare_id = $publicHomecare->id;
                $publicHomecareProduct->ph_product_id = $request->get('ph_product_id_'.$p_idx);
                $publicHomecareProduct->save();
            }

            // delete public homecare product
            if ($request->get('delete_product_id')) {
                PublicHomecareProduct::whereIn('id', $request->get('delete_product_id'))
                    ->delete();
            }

            // FOR HISTORY
            $dataChanges = array_diff(json_decode($publicHomecare, true), json_decode($dataBefore, true));
            $key = "publicHomecareProduct";
            $parentChild = $publicHomecare->$key->keyBy('id');
            $child = $publicHomecareProducts->keyBy('id');
            foreach ($child as $i=>$c) {
                $array_diff_c = isset($parentChild[$i]) 
                    ? array_diff(json_decode($parentChild[$i], true), json_decode($c, true)) 
                    : "deleted";
                if ($array_diff_c == "deleted") {
                    $dataChanges[$key][$c['id']."_deleted"] = $c;
                } else if ($array_diff_c) {
                    $dataChanges[$key][$c['id']] = $array_diff_c;
                }
            }
            // if ($parentChild > $child) {
                $array_diff_c = array_diff($parentChild->pluck('id')->toArray(), $child->pluck('id')->toArray());
                if ($array_diff_c) {
                    $dataChanges[$key]["added"] = $array_diff_c;
                }
            // }

            $userId = Auth::user()["id"];
            $historyPH["type_menu"] = "Public Homecare";
            $historyPH["method"] = "Change Data";
            $historyPH["meta"] = json_encode(
                [
                    "user" => $userId,
                    "createdAt" => date("Y-m-d H:i:s"),
                    "dataChange" => $dataChanges,
                ],
                JSON_THROW_ON_ERROR
            );
            $historyPH["user_id"] = $userId;
            $historyPH["menu_id"] = $request->id;
            HistoryUpdate::create($historyPH);

            DB::commit();

            return redirect()
                ->route("detail_public_homecare", ["id" => $request->id])
                ->with("success", "Public Homecare has been successfully updated.");
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(["error" => $e->getMessage()], 500);
        }
    }

    public function updateStatus(Request $request)
    {
        
        DB::beginTransaction();

        try {

            $puhc = PublicHomecare::where("id", $request->id)->first();

            if ($request->status == "verified") {
                $puhc->status = $request->status;
                $puhc->save();

                // TODO Notif
                // $this->accNotif($puhc, "acc_ask");
            }
            elseif ($request->status == "approve_out") {
                $puhc->status = $request->status;
                $puhc->save();
            }
            elseif ($request->status == "rejected") {
                $puhc->status = $request->status;
                $puhc->save();
            }
            elseif($request->status == "process"){
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

                $puhcProductsNya = PublicHomecareProduct::where('public_homecare_id', $request->id)->get();

                foreach ($puhcProductsNya as $puhcProductNya) {
                    $phcProductNya = $puhcProductNya->personalHomecareProduct;
                    $tempChecklist = $phcProductNya->currentChecklist;
                    $phcChecklistOut = $tempChecklist->replicate();
                    $phcChecklistOut->image = $imageArray;
                    $phcChecklistOut->save();

                    $phcProductNya->status = "unavailable";
                    $phcProductNya->current_checklist_id = $phcChecklistOut->id;
                    $phcProductNya->save();

                    $puhcProductNya->checklist_out_id = $phcChecklistOut->id;
                    $puhcProductNya->save();
                }                    
                $puhc->status = $request->status;
                $puhc->save();
            }
            elseif($request->status == "done"){
                PersonalHomecareProduct::whereIn("id", json_decode($request->id_products))
                    ->update(["status" => "available"]);

                $puhc->status = $request->status;
                $puhc->save();
            }

            $userId = Auth::user()["id"];
            $historyPH["type_menu"] = "Public Homecare";
            $historyPH["method"] = "Change Status";
            $historyPH["meta"] = json_encode(
                [
                    "user" => $userId,
                    "createdAt" => date("Y-m-d H:i:s"),
                    "dataChange" => $puhc->getChanges(),
                ],
                JSON_THROW_ON_ERROR
            );
            $historyPH["user_id"] = $userId;
            $historyPH["menu_id"] = $request->id;
            HistoryUpdate::create($historyPH);

            DB::commit();

            // TODO Notif
            // if($request->status == "process_extend" || $request->status == "process_extend_reject"){
            //     $this->accNotif($phc, $request->status);
            // }
            // else{
            //     $this->accNotif($phc, "acc_done");
            // }

            return redirect()
                ->route("detail_public_homecare", ["id" => $request->id])
                ->with("success", "Status has been successfully updated.");
        } catch (Throwable $th) {
            DB::rollBack();

            return response()->json(["error" => $th->getMessage()], 500);
        }
    }

    public function updateChecklistIn(Request $request)
    {
        DB::beginTransaction();

        try {
            // STORE PErSONAL HOMECARE CHECKLIST IN
            $phcChecklist = new PersonalHomecareChecklist();
            if(isset($request['id_checklist'])){
                $phcChecklist = PersonalHomecareChecklist::find($request['id_checklist']);
            }

            $condition["completeness"] = $request->input("completeness");
            if ($request->has("other_completeness")) {
                $condition["other"] = $request->input("other_completeness");
            }
            $condition["machine"] = $request->input("machine_condition");
            $condition["physical"] = $request->input("physical_condition");
            $phcChecklist->condition = $condition;

            $imageArray = [];
            if(isset($request['id_checklist'])){
                $imageArray = $phcChecklist->image;
            }

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


            // UPDATE PUBLIC HOMECARE CHECKLIST IN
            $puhcp = PublicHomecareProduct::where('id', $request->id_product)->first();
            $puhcp->checklist_in_id = $phcChecklist->id;
            $puhcp->save();

            $puhc = PublicHomecare::where('id', $request->id)->first();
            $check_puhcp = $puhc->publicHomecareProduct->where('checklist_in_id', null);
            if (count($check_puhcp) == 0) {
                $puhc->status = "Waiting_in";
                $puhc->save();
            }

            // Store to product personal homecare checklist current
            $productPhc = $puhcp->personalHomecareProduct;
            $productPhc->current_checklist_id = $phcChecklist->id;
            $productPhc->status = "pending";
            $productPhc->save();

            // TODO Notif
            // $this->accNotif($phc, "acc_ask");

            DB::commit();

            return redirect()
                ->route("detail_public_homecare", ["id" => $request->id])
                ->with("success", "Public Homecare has been successfully updated.");
        } catch (Throwable $th) {
            DB::rollBack();

            return response()->json(["error" => $th->getMessage()], 500);
        }
    }

    public function detail(Request $request)
    {
        $publichomecare = PublicHomecare::where("id", $request->id)
            ->with([
                "branch",
                "cso",
                "publicHomecareProduct",
            ])
            ->first();

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
            ->where("history_updates.type_menu", "like", "%Public Homecare%")
            ->where("history_updates.menu_id", $request->id)
            ->get();

        return view('admin.detail_public_homecare', compact(
            'publichomecare',
            "histories",
        ));
    }

    public function destroy(Request $request)
    {
        
        DB::beginTransaction();

        try {
            $titleNya = "";
            $puhc = PublicHomecare::where("id", $request->id)->first();
            $puhc->active = false;
            $puhc->save();

            $user = Auth::user();
            $historyUpdate= [];
            $historyUpdate['type_menu'] = "Public Homecare";
            $historyUpdate['method'] = "Delete";
            $historyUpdate['meta'] = json_encode(['user'=>$user['id'],'createdAt' => date("Y-m-d h:i:s"), 'dateChange'=> json_encode(array('Active'=>$puhc->active))]);
            $historyUpdate['user_id'] = $user['id'];
            $historyUpdate['menu_id'] = $request->id;
            $createData = HistoryUpdate::create($historyUpdate);

            DB::commit();

            // TODO Notif
            //sent notif
            // $titleNya = "ACC Cancel Rejected [Personal Homecare]";
            // $userNya = [$phc->cso->user];
            // foreach ($phc->branch->cso as $perCso) {
            //     if($perCso->user != null){
            //         array_push($userNya, $perCso->user);
            //     }
            // }
            // $messageNya = "By ".$phc->branch['code']."-".$phc->cso['name']." for ".$phc->name." [".$phc->personalHomecareProduct['code']." - ".$phc->personalHomecareProduct->product['name']."] ";
            // $urlNya = null;
            // $this->NotifTo($userNya, $messageNya, $titleNya, $urlNya);

            return redirect()
                ->route("list_all_puhc")
                ->with("success", "Public Homecare has been successfully Deleted.");
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }
}
