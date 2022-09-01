<?php

namespace App\Http\Controllers;

use App\Reference;
use App\HistoryUpdate;
use App\RajaOngkir_City;
use App\ReferenceImage;
use App\ReferencePromo;
use App\ReferenceSouvenir;
use App\Souvenir;
use App\HomeService;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\File;
use Validator;

class ReferenceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Menyimpan request ke dalam variabel $url untuk pagination
        $url = $request->all();

        // Query dari tabel references, dan menampilkan 10 data per halaman
        if (
            Auth::user()->roles[0]['slug'] === 'branch'
            || Auth::user()->roles[0]['slug'] === 'area-manager'
        ) {
            $arrbranches = [];
            foreach (Auth::user()->listBranches() as $value) {
                $arrbranches[] = $value['id'];
            }
            $references = Reference::whereIn('submissions.branch_id', $arrbranches)
                ->leftjoin('submissions', 'references.submission_id', '=', 'submissions.id');
        } else if (Auth::user()->roles[0]['slug'] === 'cso') {
            $references = Reference::where('submissions.cso_id', Auth::user()->cso['id'])
                ->leftjoin('submissions', 'references.submission_id', '=', 'submissions.id');
        } else {
            $references = Reference::all();
        }

        $references = $references->paginate(10);

        return view("admin.list_reference", compact("references", "url"));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function storeReferensi(Request $request)
    {
        DB::beginTransaction();

        try {
            $reference = new Reference();
            $reference->fill($request->only(
                "submission_id",
                "name",
                "age",
                "phone",
                "province",
                "city"
            ));
            $reference->save();

            $referenceSouvenir = new ReferenceSouvenir();
            $referenceSouvenir->reference_id = $reference->id;
            $referenceSouvenir->fill($request->only(
                "souvenir_id",
                "status",
                "order_id",
                "prize_id",
                "wakimart_link",
            ));
            $referenceSouvenir->link_hs = json_encode(
                explode(", ", $request->link_hs),
                JSON_FORCE_OBJECT|JSON_THROW_ON_ERROR
            );
            $referenceSouvenir->save();

            DB::commit();

            return redirect($request->url)
                ->with("success", "Data referensi berhasil dimasukkan.");
        } catch (Exception $e) {
            DB::rollback();

            return response()->json([
                "error" => $e,
                "error message" => $e->getMessage(),
            ], 500);
        }
    }

    public function storeReferenceMGM(Request $request)
    {
        DB::beginTransaction();
        try {
            $reference = new Reference();
            $reference->fill($request->only(
                "submission_id",
                "name",
                "age",
                "phone",
                "province",
                "city"
            ));
            $reference->save();

            $referenceSouvenir = new ReferenceSouvenir();
            $referenceSouvenir->reference_id = $reference->id;
            $referenceSouvenir->fill($request->only(
                "order_id",
                "prize_id",
            ));
            $referenceSouvenir->save();

            //history change
            $user = Auth::user();
            $historyUpdateSubmission = [];
            $historyUpdateSubmission["type_menu"] = "Submission";
            $historyUpdateSubmission["method"] = "New Reference";
            $historyUpdateSubmission["meta"] = json_encode([
                "user" => $user["id"],
                "createdAt" => date("Y-m-d H:i:s"),
                "dataChange" => ["New Reference Name" => $reference->name],
            ], JSON_THROW_ON_ERROR);

            $historyUpdateSubmission["user_id"] = $user["id"];
            $historyUpdateSubmission["menu_id"] = $request->submission_id;
            HistoryUpdate::create($historyUpdateSubmission);

            DB::commit();

            return redirect($request->url)->with("success", "Data referensi berhasil dimasukkan.");
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                "error" => $e,
                "error line" => $e->getLine(),
                "error file" => $e->getFile(),
                "error message" => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        if(isset($request->submission_id)){
            $request['id'] = $request->submission_id;
        }
        if (!empty($request->id)) {
            $user = Auth::user();

            return $this->updateReference($request, $user["id"]);
        }

        return response()->json([
            "result" => 0,
            "error" => "Data tidak ditemukan.",
        ], 400);
    }

    private function updateReference(Request $request, int $userId)
    {
        $validator = \Validator::make($request->all(), [
            'wakimart_link' => [
                'nullable',Rule::unique('reference_souvenirs')->ignore($request->id, 'reference_id'),
            ],
        ]);

        if ($validator->fails()) {
            $arr_Errors = $validator->errors()->all();
            $arr_Keys = $validator->errors()->keys();
            $arr_Hasil = [];
            for ($i = 0; $i < count($arr_Keys); $i++) {
                $arr_Hasil[$arr_Keys[$i]] = $arr_Errors[$i];
            }
            if ( !empty($request->url) ) {
                return redirect($request->url)->withErrors($arr_Hasil['wakimart_link']);
            } else {
                return response()->json('errors', $arr_Hasil['wakimart_link']);
            }
        }else{        
            DB::beginTransaction();
    
            try {
                $reference = Reference::find($request->id);
                $reference->fill($request->only(
                    "name",
                    "age",
                    "phone",
                    "province",
                    "city"
                ));
                $reference->save();
    
                $referenceSouvenir = ReferenceSouvenir::where("reference_id", $reference->id)->first();
                $referenceSouvenir->fill($request->only(
                    "souvenir_id",
                    "status",
                    "delivery_status_souvenir",
                    "order_id",
                    "prize_id",
                    "status_prize",
                    "delivery_status_prize",
                    "wakimart_link",
                ));
    
                if (!empty($request->link_hs)) {
                    //updating status homeservice
                    $homeservices = HomeService::find($request->link_hs);
                    $homeservices->status_reference = true;
                    $homeservices->save();
    
                    $referenceSouvenir->link_hs = json_encode(
                        explode(", ", $request->link_hs),
                        JSON_FORCE_OBJECT|JSON_THROW_ON_ERROR
                    );
                }
                $referenceSouvenir->save();
    
                $this->historyReference($reference, "update", $userId);
                $this->historyReferenceSouvenir($referenceSouvenir, "update", $userId);
    
                $city = RajaOngkir_City::select(
                    "province AS province",
                    DB::raw("CONCAT(type, ' ', city_name) AS city")
                )
                ->where("city_id", $reference->city)
                ->first();
    
                $souvenir = "";
                if (!empty($referenceSouvenir->souvenir_id)) {
                    $souvenir = Souvenir::select("name")
                    ->where("id", $referenceSouvenir->souvenir_id)
                    ->first();
                }
    
                DB::commit();
                if ( !empty($request->url) ) {
                    return redirect($request->url)->with("success", "Data referensi berhasil dimasukkan.");
                } else {
                    return response()->json('success');
                }
                // return response()->json([
                //     "result" => 1,
                //     "data" => $reference,
                //     "dataSouvenir" => $referenceSouvenir,
                //     "province" => $city->province,
                //     "city" => $city->city,
                //     "souvenir" => $souvenir->name,
                // ]);
            } catch (Exception $e) {
                DB::rollback();
    
                return response()->json([
                    "error" => $e,
                    "error message" => $e->getMessage(),
                ], 500);
            }
        } 
    }

    public function updateReferenceMGM(Request $request)
    {
        DB::beginTransaction();

        try {
            $reference = Reference::find($request->id);
            $reference->fill($request->only(
                "name",
                "age",
                "phone",
                "province",
                "city"
            ));
            $reference->save();

            $referenceSouvenir = ReferenceSouvenir::where("reference_id", $reference->id)->first();
            $referenceSouvenir->fill($request->only(
                "order_id",
                "prize_id",
                "status_prize",
                "delivery_status_prize",
                "final_status",
            ));

            if($request->has('status_acc')){
                if($request->status_acc == "false"){
                    $referenceSouvenir->delivery_status_prize = null;
                }
            }
            $referenceSouvenir->is_acc = false;
            $referenceSouvenir->save();

            $userId = Auth::user()["id"];
            if(sizeof($reference->getChanges()) > 0){
                $this->historyReference($reference, "Update Data Reference \n(".$reference['name'].")", $userId);
            }

            if(sizeof($referenceSouvenir->getChanges()) > 0){
                if($request->has('status_acc')){
                    if($request->status_acc == "true"){
                        $this->historyReference($reference, "Acc Approved Data Reference \n(".$reference['name'].")", $userId);
                    }
                    elseif($request->status_acc == "false"){
                        $this->historyReference($reference, "Acc Rejected Data Reference \n(".$reference['name'].")", $userId);
                    }
                }
                else{
                    $this->historyReferenceSouvenir($referenceSouvenir, "Update Reference Souvenir \n(".$reference['name'].")", $userId);
                }
            }

            DB::commit();

            if($request->has('status_acc') || $request->has('order_id')){
                // return response()->json([
                //     "success" => $reference,
                // ], 200);
                return redirect()
                    ->route("detail_submission_form", [
                        "id" => $reference->submission['id'],
                        "type" => "mgm",
                    ])
                    ->with('success', 'Data berhasil dimasukkan.');
            }
            else{
                return response()->json([
                    "success" => $reference,
                ], 200);
            }
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                "errors" => $e,
                "error message" => $e->getMessage(),
            ], 500);
        }
    }

    private function historyReference(
        Reference $reference,
        string $method,
        int $userId
    ) {
        $historyReference["type_menu"] = "Submission";
        $historyReference["method"] = $method;
        $historyReference["meta"] = json_encode(
            [
                "user" => $userId,
                "createdAt" => date("Y-m-d H:i:s"),
                "dataChange" => $reference->getChanges(),
            ],
            JSON_THROW_ON_ERROR
        );
        $historyReference["user_id"] = $userId;
        $historyReference["menu_id"] = $reference->submission['id'];
        HistoryUpdate::create($historyReference);
    }

    private function historyReferenceSouvenir(
        ReferenceSouvenir $referenceSouvenir,
        string $method,
        int $userId
    ) {
        $historyReferenceSouvenir["type_menu"] = "Submission";
        $historyReferenceSouvenir["method"] = $method;
        $historyReferenceSouvenir["meta"] = json_encode(
            [
                "user" => $userId,
                "createdAt" => date("Y-m-d H:i:s"),
                "dataChange" => $referenceSouvenir->getChanges(),
            ],
            JSON_THROW_ON_ERROR
        );
        $historyReferenceSouvenir["user_id"] = $userId;
        $historyReferenceSouvenir["menu_id"] = $referenceSouvenir->reference->submission['id'];
        HistoryUpdate::create($historyReferenceSouvenir);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        DB::beginTransaction();

        try {
            $reference = Reference::where("id", $request->id)->first();
            $reference->active = false;
            $reference->save();

            $this->historyReference($reference, "delete", Auth::user()["id"]);

            DB::commit();

            return redirect($request->url)->with("success", "Data referensi berhasil dihapus.");
        } catch (Exception $e) {
            DB::rollback();

            return response()->json([
                "error" => $e->getMessage(),
            ], 500);
        }
    }

    //================ Khusus Notifikasi ================//
    function sendFCM($body){

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

    public function accNotif(Request $request)
    {
        $fcm_tokenNya = [];
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

        $referenceNya = Reference::find($request->id);


        $body = ['registration_ids'=>$fcm_tokenNya,
            'collapse_key'=>"type_a",
            "content_available" => true,
            "priority" => "high",
            "notification" => [
                "body" => "Acc MGM for ".$referenceNya->name." from submission ".$referenceNya->submission->name.". By ".$referenceNya->submission->branch['code']."-".$referenceNya->submission->cso['name'],
                "title" => "Acceptance [MGM]",
                "icon" => "ic_launcher"
            ],
            "data" => [
                "url" => URL::to(route('detail_submission_form', ['id'=>$referenceNya->submission['id'], 'type'=>"mgm", "id_ref"=>$referenceNya['id']])),
                "branch_cso" => $referenceNya->submission->branch['code']."-".$referenceNya->submission->cso['name'],
                "submission" => $referenceNya->submission,
                "reference" => $referenceNya,
            ]];

        //update is_acc di reference souvenir
        $referenceSouvenirs = ReferenceSouvenir::where('reference_id', '=', $request->id)->first();
        $referenceSouvenirs->is_acc = true;
        $referenceSouvenirs->save();

        $userId = Auth::user()["id"];
        $this->historyReferenceSouvenir($referenceSouvenirs, "Ask ACC Reference Souvenir \n(".$referenceNya['name'].")", $userId);
        //end update is_acc

        if(sizeof($fcm_tokenNya) > 0)
        {
            $this->sendFCM(json_encode($body));
        }
        return redirect($request->url)->with("success", "Permintaan Acc telah dikirim.");
    }
    //===================================================//

    public function addApi(Request $request)
    {
        DB::beginTransaction();

        try {
            if ($request->type === "mgm") {
                $reference = new Reference();
                $reference->fill($request->only(
                    "submission_id",
                    "name",
                    "age",
                    "phone",
                    "province",
                    "city"
                ));
                $reference->save();

                $referencePromo = new ReferencePromo();
                $referencePromo->reference_id = $reference->id;
                if (isset($request->promo_1)) {
                    if ($request->promo_1 !== "other") {
                        $referencePromo->promo_1 = $request->promo_1;
                    }
                }

                if (isset($request->promo_2)) {
                    if ($request->promo_2 !== "other") {
                        $referencePromo->promo_2 = $request->promo_2;
                    }
                }

                $referencePromo->qty_1 = $request->qty_1;

                if (
                    isset($request->promo_2)
                    || isset($request->other_2)
                ) {
                    if (
                        !empty($request->promo_2)
                        || !empty($request->other_2)
                    ) {
                        $referencePromo->qty_2 = $request->qty_2;
                    }
                }

                $referencePromo->other_1 = $request->other_1;
                $referencePromo->other_2 = $request->other_2;
                $referencePromo->save();

                $path = "sources/registration";
                $referenceImage = new ReferenceImage();
                $referenceImage->reference_id = $reference->id;
                $userId = $request->user_id;
                for ($i = 1; $i <= 2; $i++) {
                    $imageInput = "image_" . $i;
                    if ($request->hasFile($imageInput)) {
                        $fileName = ((string)time())
                            . "_"
                            . $userId
                            . "_"
                            . $i
                            . "."
                            . $request->file($imageInput)->getClientOriginalExtension();

                        $request->file($imageInput)->move($path, $fileName);

                        $referenceImage["image_" . $i] = $fileName;
                    }
                }
                $referenceImage->save();

                DB::commit();

                return response()->json([
                    "result" => 1,
                    "reference" => $reference,
                    "referencePromo" => $referencePromo,
                    "referenceImage" => $referenceImage,
                ]);
            }

            if ($request->type === "referensi") {
                $reference = new Reference();
                $reference->fill($request->only(
                    "name",
                    "age",
                    "phone",
                    "province",
                    "city"
                ));
                $reference->save();

                $referenceSouvenir = new ReferenceSouvenir();
                $reference->reference_id = $reference->id;
                $referenceSouvenir->fill($request->only(
                    "souvenir_id",
                    "link_hs",
                    "status"
                ));
                $referenceSouvenir->save();

                DB::commit();

                return response()->json([
                    "result" => 1,
                    "reference" => $reference,
                    "referenceSouvenir" => $referenceSouvenir,
                ]);
            }
        } catch (Exception $e) {
            DB::rollback();

            return response()->json([
                "error" => $e,
                "error message" => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listApi(Request $request)
    {
        try {
            // Menyimpan request ke dalam variabel $url untuk pagination
            $url = $request->all();

            // Query dari tabel references, dan menampilkan 10 data per halaman
            $references = Reference::select(
                "references.id AS id",
                "references.submission_id AS submission_id",
                "references.name AS name",
                "references.age AS age",
                "references.phone AS phone",
                "references.province AS province_id",
                "raja_ongkir__cities.province AS province",
                "references.city AS city_id",
                DB::raw("CONCAT(raja_ongkir__cities.type, ' ', raja_ongkir__cities.city_name) AS city")
            )
            ->leftJoin(
                "raja_ongkir__cities",
                "raja_ongkir__cities.city_id",
                "=",
                "references.city"
            )
            ->paginate(10);

            return response()->json([
                "result" => 1,
                "data" => $references,
                "url" => $url,
            ]);
        } catch (Exception $e) {
            return response()->json([
                "result" => 0,
                "data" => $e->getMessage(),
            ], 500);
        }
    }

    public function updateApi(Request $request)
    {
        if (!empty($request->id) && !empty($request->user_id)) {
            return $this->updateReference($request, (int) $request->user_id);
        }

        return response()->json([
            "result" => 0,
            "error" => "Data tidak ditemukan.",
        ], 400);
    }

    public function updateMGMApi(Request $request)
    {
        DB::beginTransaction();

        try {
            $reference = Reference::find($request->id);
            $reference->fill($request->only(
                "name",
                "age",
                "phone",
                "province",
                "city"
            ));
            $reference->save();

            $referencePromo = ReferencePromo::where("reference_id", $request->id)->first();
            if (isset($request->promo_1)) {
                if ($request->promo_1 !== "other") {
                    $referencePromo->promo_1 = $request->promo_1;
                }
            }

            if (isset($request->promo_2)) {
                if ($request->promo_2 !== "other") {
                    $referencePromo->promo_2 = $request->promo_2;
                }
            }

            $referencePromo->qty_1 = $request->qty_1;

            if (
                isset($request->promo_2)
                || isset($request->other_2)
            ) {
                if (
                    !empty($request->promo_2)
                    || !empty($request->other_2)
                ) {
                    $referencePromo->qty_2 = $request->qty_2;
                }
            }

            $referencePromo->other_1 = $request->other_1;
            $referencePromo->other_2 = $request->other_2;

            $path = "sources/registration";
            $referenceImage = ReferenceImage::where("reference_id", $request->id)->first();
            for ($i = 1; $i <= 2; $i++) {
                $imageInput = "image_" . $i;
                if ($request->hasFile($imageInput)) {
                    $fileName = ((string)time())
                    . "_"
                    . $request->user_id
                    . "_"
                    . $i
                    . "."
                    . $request->file($imageInput)->getClientOriginalExtension();

                    $request->file($imageInput)->move($path, $fileName);

                    $referenceImage["image_" . $i] = $fileName;
                }
            }
            $referenceImage->save();

            DB::commit();

            return response()->json([
                "result" => 1,
                "reference" => $reference,
                "referencePromo" => $referencePromo,
                "referenceImage" => $referenceImage,
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                "error" => $e,
                "error message" => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * add online signature in reference
     *
     * Undocumented function long description
     *
     * @param Type $var Description
     * @return type
     * @throws conditon
     **/
    public function addOnlineSignature(Request $request)
    {    
        DB::beginTransaction();

        try {
            $reference = Reference::find($request->ref_id);
            $filename = $request->ref_id . "-signature.png";
            $data_uri = explode(',', $request->online_signature);
            $encoded_image = $data_uri[1];
            $decoded_image = base64_decode($encoded_image);
            if (!is_dir("sources/online_signature")) {
                File::makeDirectory("sources/online_signature", 0777, true, true);
            }
            file_put_contents('sources/online_signature/' . $filename, $decoded_image);
            $reference->online_signature = $filename;
            $reference->reference_souvenir->delivery_status_souvenir = "delivered";
            $reference->save();

            $reference_souvenir = $reference->reference_souvenir;
            $reference_souvenir->delivery_status_souvenir = "delivered";
            $reference_souvenir->save();

            //history change
            $user = Auth::user();
            $historyUpdateSubmission = [];
            $historyUpdateSubmission["type_menu"] = "Submission";
            $historyUpdateSubmission["method"] = "Signature Reference";
            $historyUpdateSubmission["meta"] = json_encode([
                "user" => $user["id"],
                "updatedAt" => date("Y-m-d H:i:s"),
                "dataChange" => ["Delivery Status" => "delivered"],
            ], JSON_THROW_ON_ERROR);

            $historyUpdateSubmission["user_id"] = $user["id"];
            $historyUpdateSubmission["menu_id"] = $reference->submission['id'];
            HistoryUpdate::create($historyUpdateSubmission);

            DB::commit();
            return redirect($request->url)->with("success", "signature added successfully.");           
        } catch (Exception $e) {
            DB::rollback();
            File::deleteDirectory(public_path('sources/online_signature/' . $filename));        
            return redirect($request->url)->with("error", $e->getMessage());           
        }
    }
}
