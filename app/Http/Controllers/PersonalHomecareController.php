<?php

namespace App\Http\Controllers;

use App\Branch;
use App\Cso;
use App\User;
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
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;
use Throwable;

class PersonalHomecareController extends Controller
{
    public function index(Request $request)
    {
        $personalhomecares = PersonalHomecare::where('active', true);

        if ($request->has("filter_name_phone")) {
            $filterNamePhone = $request->filter_name_phone;
            $personalhomecares = $personalhomecares->where(function ($q) use ($filterNamePhone) {
                $q->where('name', "like", "%" . $filterNamePhone . "%")
                    ->orWhere('phone', "like", "%" . $filterNamePhone . "%");
            });
        }

        if ($request->has("filter_status")) {
            $personalhomecares = $personalhomecares->where('status', $request->filter_status);
        }

        if ($request->has("filter_branch")) {
            $personalhomecares = $personalhomecares->where('branch_id', $request->filter_branch);
        }
        if ($request->has("filter_cso")) {
            $personalhomecares = $personalhomecares->where('cso_id', $request->filter_cso);
        }

        if ($request->has('filter_product_code')) {
            $id_productNya = PersonalHomecareProduct::where('code', "like", "%" . $request->filter_product_code . "%")->first();
            if ($id_productNya != null) {
                $personalhomecares = $personalhomecares->where("ph_product_id", $id_productNya['id']);
            } else {
                $personalhomecares = $personalhomecares->where("ph_product_id", $id_productNya);
            }
        }

        if ($request->has("filter_schedule")) {
            $personalhomecares = $personalhomecares->where('schedule', $request->filter_schedule);
        }

        if (Auth::user()->roles[0]->slug === "cso") {
            $personalhomecares = $personalhomecares->where("cso_id", Auth::user()->cso["id"]);
        }
        else if (Auth::user()->roles[0]->slug === "branch" || Auth::user()->roles[0]->slug === "area-manager") {
            $arrBranches = [];
            foreach (Auth::user()->listBranches() as $value) {
                $arrBranches[] = $value['id'];
            }
            $personalhomecares = $personalhomecares->whereIn('branch_id', $arrBranches);
        }

        $personalhomecares = $personalhomecares->orderBy('created_at', "desc")->paginate(10);

        $csos = Cso::select("id", "code", "name")
            ->where("active", true)
            ->orderBy("code", 'asc')
            ->get();
        $branches = Branch::select("id", "code", "name")
            ->where("active", true)
            ->orderBy("code", 'asc')
            ->get();

        $url = $request->all();

        return view("admin.list_all_personalhomecare", compact("personalhomecares", "csos", "branches", "url"))
            ->with('i', (request()->input('page', 1) - 1) * 10);
    }

    public function listApproved(Request $request)
    {
        $url = $request->all();

        $branches = Branch::where('active', true)
            ->orderBy('code', 'asc')
            ->get();

        $personalhomecares = PersonalHomecare::where('active', true)->get();

        // Filter
        if ($request->has('filter_branch') && Auth::user()->roles[0]['slug'] != 'branch') {
            $personalhomecares = $personalhomecares->where('branch_id', $request->filter_branch);
        }

        return view('admin.list_approved_phc', compact(
            'personalhomecares',
            'branches',
            'url',
        ));
    }

    public function phForm($id)
    {
        $personalhomecare = PersonalHomecare::find($id);

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
        ->where("history_updates.type_menu", "like", "%Personal Homecare%")
        ->where("history_updates.menu_id", $id)
        ->get();

        return view('personal_homecare', compact('personalhomecare', 'histories'));
    }

    public function thankyouForm($id)
    {
        $personalhomecare = PersonalHomecare::find($id);

        return view('thankyou_form_ph', compact('personalhomecare'));
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

            $condition["completeness"] = $request->input("completeness");
            if ($request->has("other_completeness")) {
                $condition["other"] = $request->input("other_completeness");
            }
            if($condition["completeness"] == null){
                $condition["completeness"] = [];
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

            //reserve the product
            PersonalHomecareProduct::where("id", $request->ph_product_id)
                ->update(["status" => 0]);

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

            if ($request->hasFile("member_wakimart_image")) {
                $timestamp = (string) time();
                $fileName = $timestamp
                    . "_member_wakimart"
                    . "."
                    . $request->file("member_wakimart_image")->getClientOriginalExtension();

                $request->file("member_wakimart_image")->move("sources/phc", $fileName);

                $personalHomecare->member_wakimart = $fileName;
            }

            $personalHomecare->checklist_out = $phcChecklist->id;
            $personalHomecare->save();

            DB::commit();

            $this->accNotif($personalHomecare, "acc_ask");

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
                ->where("personal_homecare_products.active", true)
                ->get();

            $finalPhcProd = [];
            $firstDate = (new Carbon($request->date))->subDays(6);
            $lastDate =  (new Carbon($request->date))->addDays(6);

            foreach ($phcProducts as $perProd) {
                $phcNya = PersonalHomecare::where('ph_product_id', $perProd['id'])
                        ->whereBetween('schedule', [$firstDate, $lastDate])
                        ->where("active", true)
                        ->get();
                if(sizeof($phcNya) == 0){
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
            $query = PersonalHomecare::select("id", "phone", "created_at")
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
                        "data" => "Phone number is not eligible for Personal Homecare."
                    ]);
                }
            }

            return response()->json([
                "result" => 1,
                "data" => "Phone number is eligible for Personal Homecare."
            ]);
        } catch (Throwable $th) {
            return response()->json(["error" => $th->getMessage()], 500);
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
            if($personalHomecare['status'] == "new"){
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
            }
            else{
                $personalHomecare->fill($request->only(
                    "name",
                    "phone",
                    "address",
                    "province_id",
                    "city_id",
                    "subdistrict_id",
                ));
            }
            

            if ($request->hasFile("id_card_image")) {
                $timestamp = (string) time();
                $fileName = $timestamp
                    . "."
                    . $request->file("id_card_image")->getClientOriginalExtension();

                $request->file("id_card_image")->move("sources/phc", $fileName);

                $personalHomecare->id_card = $fileName;
            }

            if ($request->hasFile("member_wakimart_image")) {
                $timestamp = (string) time();
                $fileName = $timestamp
                    . "_member_wakimart"
                    . "."
                    . $request->file("member_wakimart_image")->getClientOriginalExtension();

                $request->file("member_wakimart_image")->move("sources/phc", $fileName);

                $personalHomecare->member_wakimart = $fileName;
            }
            
            if ($request->hasFile("product_photo_2")){
                $personalHomecare->status = "process";
            }

            $personalHomecare->save();

            // FOR HISTORY
            $userId = Auth::user()["id"];
            $historyPH["type_menu"] = "Personal Homecare";
            $historyPH["method"] = "Change Data";
            $historyPH["meta"] = json_encode(
                [
                    "user" => $userId,
                    "createdAt" => date("Y-m-d H:i:s"),
                    "dataChange" => $personalHomecare->getChanges(),
                ],
                JSON_THROW_ON_ERROR
            );
            $historyPH["user_id"] = $userId;
            $historyPH["menu_id"] = $request->id;
            HistoryUpdate::create($historyPH);

            // UPDATE PERSONAL HOMECARE CHECKLIST OUT
            $phcChecklistOut = PersonalHomecareChecklist::where(
                    "id",
                    $personalHomecare->checklist_out
                )
                ->first();

            $condition["completeness"] = $request->input("completeness");
            if ($request->has("other_completeness")) {
                $condition["other"] = $request->input("other_completeness");
            }
            if($condition["completeness"] == null){
                $condition["completeness"] = [];
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

            // FOR HISTORY Checkout
            $userId = Auth::user()["id"];
            $historyPH["type_menu"] = "Personal Homecare Checkout";
            $historyPH["method"] = "Change Data";
            $historyPH["meta"] = json_encode(
                [
                    "user" => $userId,
                    "createdAt" => date("Y-m-d H:i:s"),
                    "dataChange" => $phcChecklistOut->getChanges(),
                ],
                JSON_THROW_ON_ERROR
            );
            $historyPH["user_id"] = $userId;
            $historyPH["menu_id"] = $request->id;
            HistoryUpdate::create($historyPH);

            DB::commit();

            return redirect()
                ->route("detail_personal_homecare", ["id" => $request->id])
                ->with("success", "Personal Homecare has been successfully updated.");
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(["error" => $e->getMessage()], 500);
        }
    }

    public function updateStatus(Request $request)
    {
        DB::beginTransaction();

        try {
            if ($request->status == "approve_out") {
                PersonalHomecareProduct::where("id", $request->id_product)
                    ->update(["status" => 0]);
            }
            else if($request->status == "done" || $request->status == "rejected"){
                PersonalHomecareProduct::where("id", $request->id_product)
                    ->update(["status" => 1]);
            }

            $phc = PersonalHomecare::where("id", $request->id)->first();
            $phc->status = $request->status;
            $phc->save();

            $userId = Auth::user()["id"];
            $historyPH["type_menu"] = "Personal Homecare";
            $historyPH["method"] = "Change Status";
            $historyPH["meta"] = json_encode(
                [
                    "user" => $userId,
                    "createdAt" => date("Y-m-d H:i:s"),
                    "dataChange" => $phc->getChanges(),
                ],
                JSON_THROW_ON_ERROR
            );
            $historyPH["user_id"] = $userId;
            $historyPH["menu_id"] = $request->id;
            HistoryUpdate::create($historyPH);

            DB::commit();

            $this->accNotif($phc, "acc_done");

            return redirect()
                ->route("detail_personal_homecare", ["id" => $request->id])
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
            // STORE PERSONAL HOMECARE CHECKLIST IN
            $phcChecklist = new PersonalHomecareChecklist();

            $condition["completeness"] = $request->input("completeness");
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

            // UPDATE PERSONAL HOMECARE CHECKLIST IN
            $phc = PersonalHomecare::where("id", $request->id)->first();
            $phc->status = "waiting_in";
            $phc->checklist_in = $phcChecklist->id;
            $phc->save();

            $this->accNotif($phc, "acc_ask");

            DB::commit();

            return redirect()
                ->route("detail_personal_homecare", ["id" => $request->id])
                ->with("success", "Personal Homecare has been successfully updated.");
        } catch (Throwable $th) {
            DB::rollBack();

            return response()->json(["error" => $th->getMessage()], 500);
        }

    }

    public function detail(Request $request)
    {
        $personalhomecare = PersonalHomecare::where("id", $request->id)
            ->with([
                "branch",
                "cso",
                "personalHomecareProduct",
                "checklistOut",
                "checklistIn",
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
            ->where("history_updates.type_menu", "like", "%Personal Homecare%")
            ->where("history_updates.menu_id", $request->id)
            ->get();

        return view('admin.detail_personal_homecare', compact(
            'personalhomecare',
            "histories",
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

    //================ Khusus Notifikasi ================//
    function sendFCM($body)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Authorization: key=AAAAfcgwZss:APA91bGg7XK9XjDvLLqR36mKsC-HwEx_l5FPGXDE3bKiysfZ2yzUKczNcAuKED6VCQ619Q8l55yVh4VQyyH2yyzwIJoVajaK4t3TJV-x-4f_a9WUzIcnOYzixPIUB5DeuWRIAh1v8Yld',
            'Content-Type: application/json'
        ));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $body);

        $result = curl_exec($curl);
        if ($result === FALSE) {
            die('Oops! FCM Send Error: ' . curl_error($curl));
            $this->info(curl_error($curl));
        }
        curl_close($curl);
        // return $result;
    }

    public function accNotif($personalhomecare_obj, $notif_type)
    {
        $fcm_tokenNya = [];
        $messageNya = "";
        if($notif_type == "acc_ask"){
            $userNya = User::where('users.fmc_token', '!=', null)
                        ->whereIn('role_users.role_id', [1,2,7])
                        ->leftjoin('role_users', 'users.id', '=', 'role_users.user_id')
                        ->get();
            foreach ($userNya as $value) {
                if($value['fmc_token'] != null){
                    foreach ($value['fmc_token'] as $fcmSatuan) {
                        if($fcmSatuan != null){
                            array_push($fcm_tokenNya, $fcmSatuan);
                        }
                    }
                }
            }

            //khusus pak supri
            $value = User::find(4);
            if($value['fmc_token'] != null){
                foreach ($value['fmc_token'] as $fcmSatuan) {
                    if($fcmSatuan != null){
                        array_push($fcm_tokenNya, $fcmSatuan);
                    }
                }
            }

            $messageNya = "By ".$personalhomecare_obj->branch['code']."-".$personalhomecare_obj->cso['name']." for ".$personalhomecare_obj->name." [".$personalhomecare_obj->personalHomecareProduct['code']." - ".$personalhomecare_obj->personalHomecareProduct->product['name']."] ";
        }
        else if($notif_type == "acc_done"){
            $userNya = $personalhomecare_obj->cso->user;
            if($userNya != null){
                if($userNya['fmc_token'] != null){
                    foreach ($userNya['fmc_token'] as $fcmSatuan) {
                        if($fcmSatuan != null){
                            array_push($fcm_tokenNya, $fcmSatuan);
                        }
                    }
                }
            }

            $messageNya = $personalhomecare_obj->name." [".$personalhomecare_obj->personalHomecareProduct['code']." - ".$personalhomecare_obj->personalHomecareProduct->product['name']."] . Don't Forget to Upload Picture with Customer !";
        }
        

        $body = ['registration_ids' => $fcm_tokenNya,
            'collapse_key' => "type_a",
            "content_available" => true,
            "priority" => "high",
            "notification" => [
                "body" => $messageNya,
                "title" => "ACC ".$personalhomecare_obj->status." [Personal Homecare]",
                "icon" => "ic_launcher"
            ],
            "data" => [
                "url" => URL::to(route('detail_personal_homecare', ['id'=>$personalhomecare_obj['id']])),
                "branch_cso" => $personalhomecare_obj->branch['code']."-".$personalhomecare_obj->cso['name'],
            ]];

        //khusus untuk reminder
        // $body = ['registration_ids'=>$fcm_tokenNya,
        //     'collapse_key'=>"type_a",
        //     "content_available" => true,
        //     "priority" => "high",
        //     "notification" => [
        //         "body" => "Need to pickup for ".$personalhomecare_obj->name." tomorrow (". date("d/m/Y", strtotime($personalhomecare_obj->schedule . "+5 days")) .") [".$personalhomecare_obj->personalHomecareProduct['code']." - ".$personalhomecare_obj->personalHomecareProduct->product['name']."] ",
        //         "title" => "Remminder Pickup [Personal Homecare]",
        //         "icon" => "ic_launcher"
        //     ],
        //     "data" => [
        //         "url" => URL::to(route('detail_personal_homecare', ['id'=>$personalhomecare_obj['id']])),
        //         "branch_cso" => $personalhomecare_obj->branch['code']."-".$personalhomecare_obj->cso['name'],
        //     ]];

        if(sizeof($fcm_tokenNya) > 0)
        {
            $this->sendFCM(json_encode($body));
        }
    }
    //===================================================//
}
