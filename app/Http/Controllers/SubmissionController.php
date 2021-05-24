<?php

namespace App\Http\Controllers;

use App\Branch;
use App\Cso;
use App\HistoryUpdate;
use App\HomeService;
use App\Prize;
use App\Promo;
use App\Reference;
use App\ReferenceImage;
use App\ReferencePromo;
use App\ReferenceSouvenir;
use App\Submission;
use App\Souvenir;
use App\SubmissionDeliveryorder;
use App\SubmissionImage;
use App\SubmissionPromo;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SubmissionController extends Controller
{
    /**
     * @param string
     * @return bool
     */
    private function isJSON($string)
    {
        return is_string($string)
            && is_array(json_decode($string, true))
            && (json_last_error() === JSON_ERROR_NONE)
            ? true
            : false;
    }

    public function convertHsToForeign()
    {
        if (Auth::user()->roles[0]->slug !== "head-admin") {
            return response()->json([
                "message" => "Tidak memiliki hak akses. ",
            ], 401);
        }

        DB::beginTransaction();
        try {
            $references = ReferenceSouvenir::all();

            foreach ($references as $reference) {
                $findHSCode = strpos($reference->link_hs, "HS");
                $hsCode = substr($reference->link_hs, $findHSCode);
                $getHS = HomeService::select("id", "code")
                    ->where("code", $hsCode)
                    ->first();

                if (!empty($getHS)) {
                    $reference->link_hs = json_encode(
                        [$getHS->id],
                        JSON_FORCE_OBJECT|JSON_THROW_ON_ERROR
                    );
                } else {
                    if (!$this->isJSON($reference->link_hs)) {
                        $reference->link_hs = json_encode(
                            [$reference->link_hs],
                            JSON_FORCE_OBJECT|JSON_THROW_ON_ERROR
                        );
                    }
                }

                $reference->save();
            }

            DB::commit();

            return response()->json([
                "message" => "Link_HS berhasil dikonversi menjadi JSON.",
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                "error" => $e->getMessage(),
            ], 500);
        }
    }

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

        // Memasukkan klausa WHERE yang digunakan berkali-kali ke dalam array
        $whereArray = [];
        $whereArray[] = ["active", true];
        if (!empty($request->filter_type)) {
            $whereArray[] = ["type", $request->filter_type];
        }

        if (Auth::user()->roles[0]->slug === "cso") {
            // Jika user adalah CSO
            // Push klausa WHERE untuk CSO ke dalam array
            $whereArray[] = [
                "cso_id",
                Auth::user()->cso["id"],
            ];

            $submissions = Submission::where($whereArray)->paginate(10);
        } elseif (
            Auth::user()->roles[0]->slug === "branch"
            || Auth::user()->roles[0]->slug === "area-manager"
        ) {
            // Jika user adalah BRANCH atau AREA MANAGER
            // Inisialisasi variabel $arrBranches untuk menyimpan hasil query
            $arrBranches = [];

            // Menyimpan BRANCH yang dipegang oleh user ke dalam $arrBranches
            foreach (Auth::user()->listBranches() as $value) {
                $arrBranches[] = $value['id'];
            }

            $submissions = Submission::whereIn(
                "branch_id",
                $arrBranches
            )
            ->orderBy(DB::raw("DATE(submissions.created_at)"), "desc")
            ->where($whereArray)
            ->paginate(10);
        } else {
            $submissions = Submission::where($whereArray)->orderBy(DB::raw("DATE(submissions.created_at)"), "desc")->paginate(10);
        }

        $countSubmission = $submissions->count();

        return view(
            "admin.list_submission_form",
            compact(
                "countSubmission",
                "submissions",
                "url"
            )
        )
        ->with("i", (request()->input("page", 1) - 1) * 10 + 1);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function createMGM(): \Illuminate\View\View
    {
        $branches = Branch::where('active', true)->get();
        $csos = Cso::all();
        $promos = Promo::all();

        return view(
            "admin.add_submission_mgm",
            compact(
                'promos',
                'branches',
                'csos',
            )
        );
    }

    public function createReference(): \Illuminate\View\View
    {
        $branches = Branch::where('active', true)->get();
        $csos = Cso::all();
        $promos = Promo::all();
        $souvenirs = Souvenir::select("id", "name")
            ->where("active", true)
            ->get();
        $prizes = Prize::select("id", "name")
            ->where("active", true)
            ->get();

        return view(
            "admin.add_submission_reference",
            compact(
                'promos',
                'branches',
                'csos',
                "souvenirs",
                "prizes",
            )
        );
    }

    public function createTakeaway(): \Illuminate\View\View
    {
        $branches = Branch::where('active', true)->get();
        $csos = Cso::all();
        $promos = Promo::all();

        return view(
            "admin.add_submission_takeaway",
            compact(
                'promos',
                'branches',
                'csos',
            )
        );
    }

    public function storeMGM(Request $request)
    {
        $data = $request->all();

        DB::beginTransaction();

        try {
            $data['code'] = "SUB_M/" . strtotime(date("Y-m-d H:i:s")) . "/" . substr($data['phone'], -4);
            $data['cso_id'] = Cso::where('code', $data['cso_id'])->first()['id'];
            $submission = Submission::create($data);

            $dataCount = count($data["name_ref"]);
            for ($i = 0; $i < $dataCount; $i++) {
                if (!empty($data["name_ref"][$i])) {
                    $reference = new Reference();
                    $reference->submission_id = $submission->id;
                    $reference->name = $data["name_ref"][$i];
                    $reference->age = $data["age_ref"][$i];
                    $reference->phone = $data["phone_ref"][$i];
                    $reference->province = $data["province_ref"][$i];
                    $reference->city = $data["city_ref"][$i];
                    $reference->save();

                    $referencePromo = new ReferencePromo();
                    $referencePromo->reference_id = $reference->id;

                    if (isset($data["promo_1"])){
                        if ($data["promo_1"][$i] !== "other") {
                            $referencePromo->promo_1 = $data["promo_1"][$i];
                        }
                    }

                    if (isset($data["promo_2"])){
                        if ($data["promo_2"][$i] !== "other") {
                            $referencePromo->promo_2 = $data["promo_2"][$i];
                        }
                    }

                    $referencePromo->qty_1 = $data["qty_1"][$i];

                    if (isset($data["promo_2"])){
                        if (!empty($data["promo_2"][$i]) || !empty($data["other_2"][$i])) {
                            $referencePromo->qty_2 = $data["qty_2"][$i];
                        }
                    }

                    $referencePromo->other_1 = $data["other_1"][$i];
                    $referencePromo->other_2 = $data["other_2"][$i];
                    $referencePromo->save();

                    $user_id = Auth::user()["id"];
                    $path = "sources/registration";
                    $referenceImage = new ReferenceImage();
                    $referenceImage->reference_id = $reference->id;

                    $idxImg = 1;
                    foreach ($request->file("do-image_" . ($i + 1)) AS $image) {
                        $fileName = ((string)time())
                            . "_"
                            . $user_id
                            . "_"
                            . $i
                            . "_"
                            . $idxImg
                            . "."
                            . $image->getClientOriginalExtension();

                        $image->move($path, $fileName);

                        $referenceImage["image_" . ($idxImg)] = $fileName;

                        $idxImg++;
                    }
                    $referenceImage->save();
                }
            }

            DB::commit();

            return redirect()
                ->route("add_submission_mgm")
                ->with('success', 'Data berhasil dimasukkan.');
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                "error" => $e,
                "error message" => $e->getMessage(),
            ]);
        }
    }

    public function storeReference(Request $request)
    {
        $data = $request->all();

        DB::beginTransaction();

        try {
            $data['code'] = "SUB_M/" . strtotime(date("Y-m-d H:i:s")) . "/" . substr($data['phone'], -4);
            $data['cso_id'] = Cso::where('code', $data['cso_id'])->first()['id'];
            $submission = Submission::create($data);

            $user_id = Auth::user()["id"];
            $i = 1;
            $path = "sources/registration";
            $submissionImage = new SubmissionImage();
            $submissionImage->submission_id = $submission->id;
            foreach ($request->file("proof_image") as $image) {
                $fileName = ((string)time())
                    . "_"
                    . $user_id
                    . "_"
                    . $i
                    . "."
                    . $image->getClientOriginalExtension();

                $image->move($path, $fileName);

                $submissionImage["image_" . $i] = $fileName;

                $i++;
            }
            $submissionImage->save();

            $dataCount = count($data["name_ref"]);
            for ($i = 0; $i < $dataCount; $i++) {
                if (!empty($data["name_ref"][$i])) {
                    $reference = new Reference();
                    $reference->submission_id = $submission->id;
                    $reference->name = $data["name_ref"][$i];
                    $reference->age = $data["age_ref"][$i];
                    $reference->phone = $data["phone_ref"][$i];
                    $reference->province = $data["province_ref"][$i];
                    $reference->city = $data["city_ref"][$i];
                    $reference->save();

                    $referenceSouvenir = new ReferenceSouvenir();
                    $referenceSouvenir->reference_id = $reference->id;
                    $referenceSouvenir->souvenir_id = $data["souvenir_id"][$i];
                    if (
                        isset($data["link_hs"][$i])
                        && !empty($data["link_hs"][$i])
                    ) {
                        $referenceSouvenir->link_hs = json_encode(
                            explode(", ", $data["link_hs"][$i]),
                            JSON_FORCE_OBJECT|JSON_THROW_ON_ERROR
                        );
                    }

                    if (
                        isset($data["order_id"][$i])
                        && !empty($data["order_id"][$i])
                    ) {
                        $referenceSouvenir->order_id = $data["order_id"][$i];
                    }

                    if (
                        isset($data["prize_id"][$i])
                        && !empty($data["prize_id"][$i])
                    ) {
                        $referenceSouvenir->order_id = $data["order_id"][$i];
                    }

                    $referenceSouvenir->save();
                }
            }

            DB::commit();

            return redirect()
                ->route("add_submission_reference")
                ->with('success', 'Data berhasil dimasukkan.');
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                "error" => $e,
                "error message" => $e->getMessage(),
            ], 400);
        }
    }

    public function storeTakeaway(Request $request)
    {
        $data = $request->all();

        DB::beginTransaction();

        try {
            $data['code'] = "SUB_M/" . strtotime(date("Y-m-d H:i:s")) . "/" . substr($data['phone'], -4);
            $data['cso_id'] = Cso::where('code', $data['cso_id'])->first()['id'];
            $submission = Submission::create($data);

            $submissionPromo = new SubmissionPromo();
            $submissionPromo->submission_id = $submission->id;

            if ($data["promo_1"] !== "other") {
                $submissionPromo->promo_1 = $data["promo_1"];
            }

            if ($data["promo_2"] !== "other") {
                $submissionPromo->promo_2 = $data["promo_2"];
            }

            $submissionPromo->qty_1 = $data["qty_1"];

            if (!empty($data["promo_2"]) || !empty($data["other_2"])) {
                $submissionPromo->qty_2 = $data["qty_2"];
            }

            $submissionPromo->other_1 = $data["other_1"];
            $submissionPromo->other_2 = $data["other_2"];
            $submissionPromo->save();

            $submissionDO = new SubmissionDeliveryorder();
            $submissionDO->submission_id = $submission->id;
            $submissionDO->no_deliveryorder = $data["nomor_do"];
            $submissionDO->save();

            $user_id = Auth::user()["id"];
            $i = 1;
            $path = "sources/registration";

            $submissionImage = new SubmissionImage();
            $submissionImage->submission_id = $submission->id;
            foreach ($request->file("do_image") as $image) {
                $fileName = ((string)time())
                    . "_"
                    . $user_id
                    . "_"
                    . $i
                    . "."
                    . $image->getClientOriginalExtension();

                $image->move($path, $fileName);

                $submissionImage["image_" . $i] = $fileName;

                $i++;
            }
            $submissionImage->save();

            DB::commit();

            return redirect()
                ->route("add_submission_takeaway")
                ->with('success', 'Data berhasil dimasukkan.');
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                "error" => $e,
                "error message" => $e->getMessage(),
            ], 400);
        }
    }

    public function show(Request $request)
    {
        $references = "";
        $promos = "";
        $souvenirs = "";
        $prizes = "";
        if ($request->type === "mgm") {
            $submission = $this->querySubmissionMGM($request->id);
            $references = $this->queryReferenceMGM($request->id);
            $promos = Promo::all();
        } elseif ($request->type === "referensi") {
            $submission = $this->querySubmissionReferensi($request->id);
            $references = $this->queryReferenceReferensi($request->id);
            $souvenirs = Souvenir::where("active", true)->get();
            $prizes = Prize::where("active", true)->get();
        } elseif ($request->type === "takeaway") {
            $submission = $this->querySubmissionTakeaway($request->id);
        }

        $historySubmission = $this->queryHistory($request->id);

        return view(
            "admin.detail_submission_" . $request->type,
            compact(
                "submission",
                "references",
                "historySubmission",
                "promos",
                "souvenirs",
                "prizes",
            )
        );
    }

    public function edit(Request $request)
    {
        if (!empty($request->id) && !empty($request->type)) {
            $references = "";
            if ($request->type === "mgm") {
                $submission = $this->querySubmissionMGM($request->id);
                $references = $this->queryReferenceMGM($request->id);
            } elseif ($request->type === "referensi") {
                $submission = $this->querySubmissionReferensi($request->id);
                $references = $this->queryReferenceReferensi($request->id);
            } elseif ($request->type === "takeaway") {
                $submission = $this->querySubmissionTakeaway($request->id);
            }

            $branches = Branch::where("active", true)->get();
            $promos = Promo::all();

            return view(
                "admin.update_submission_" . $request->type,
                compact(
                    "branches",
                    "promos",
                    "submission",
                    "references",
                )
            );
        }

        return response()->json([
            "result" => "0",
            "message" => "Data tidak ditemukan.",
        ], 400);
    }

    public function updateMGM(Request $request)
    {
        $submission = Submission::find($request->id);

        DB::beginTransaction();

        try {
            $submission->fill($request->only(
                "no_member",
                "name",
                "phone",
                "province",
                "city",
                "district",
                "address",
                "branch_id",
            ));
            $csoId = Cso::where('code', $request->cso_id)->first()['id'];
            $submission->cso_id = $csoId;
            $submission->save();

            return redirect()
                ->route("detail_submission_form", ["id" => $request->id, "type" => "mgm"])
                ->with('success', 'Data berhasil diperbarui.');
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                "error" => $e,
                "error message" => $e->getMessage(),
            ], 500);
        }
    }

    public function updateReferensi(Request $request)
    {
        $submission = Submission::find($request->id);

        DB::beginTransaction();

        try {
            $submission->fill($request->only(
                "no_member",
                "name",
                "phone",
                "province",
                "city",
                "district",
                "address",
                "branch_id",
            ));
            $csoId = Cso::where('code', $request->cso_id)->first()['id'];
            $submission->cso_id = $csoId;
            $submission->save();

            $user_id = Auth::user()["id"];
            $path = "sources/registration";
            $submissionImage = SubmissionImage::where("submission_id", $request->id)->first();
            for ($i = 1; $i <= 5; $i++) {
                $imageInput = "proof_image_" . $i;
                if ($request->hasFile($imageInput)) {
                    $fileName = ((string)time())
                        . "_"
                        . $user_id
                        . "_"
                        . $i
                        . "."
                        . $request->file($imageInput)->getClientOriginalExtension();

                    $request->file($imageInput)->move($path, $fileName);

                    $submissionImage["image_" . $i] = $fileName;
                }
            }
            $submissionImage->save();

            return redirect()
                ->route("detail_submission_form", ["id" => $request->id, "type" => "referensi"])
                ->with('success', 'Data berhasil diperbarui.');
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                "error" => $e,
                "error message" => $e->getMessage(),
            ], 500);
        }
    }

    public function updateTakeaway(Request $request)
    {
        $submission = Submission::find($request->id);

        DB::beginTransaction();

        try {
            $submission->fill($request->only(
                "no_member",
                "name",
                "phone",
                "province",
                "city",
                "district",
                "address",
                "branch_id",
            ));
            $csoId = Cso::where('code', $request->cso_id)->first()['id'];
            $submission->cso_id = $csoId;
            $submission->save();

            $submissionPromo = SubmissionPromo::where("submission_id", $request->id)->first();
            if ($request->promo_1 !== "other") {
                $submissionPromo->promo_1 = $request->promo_1;
            }

            if ($request->promo_2 !== "other") {
                $submissionPromo->promo_2 = $request->promo_2;
            }

            $submissionPromo->qty_1 = $request->qty_1;

            if (
                !empty($request->promo_2)
                || !empty($request->other_2)
            ) {
                $submissionPromo->qty_2 = $request->qty_2;
            }

            $submissionPromo->other_1 = $request->other_1;
            $submissionPromo->other_2 = $request->other_2;
            $submissionPromo->save();

            $submissionDO = SubmissionDeliveryorder::where("submission_id", $request->id)->first();
            $submissionDO->no_deliveryorder = $request->other_2;
            $submissionDO->save();

            $user_id = Auth::user()["id"];
            $path = "sources/registration";
            $submissionImage = SubmissionImage::where("submission_id", $request->id)->first();
            for ($i = 1; $i <= 5; $i++) {
                $imageInput = "do_image_" . $i;
                if ($request->hasFile($imageInput)) {
                    $fileName = ((string)time())
                        . "_"
                        . $user_id
                        . "_"
                        . $i
                        . "."
                        . $request->file($imageInput)->getClientOriginalExtension();

                    $request->file($imageInput)->move($path, $fileName);

                    $submissionImage["image_" . $i] = $fileName;
                }
            }
            $submissionImage->save();

            DB::commit();

            return redirect()
                ->route("detail_submission_form", ["id" => $request->id, "type" => "takeaway"])
                ->with('success', 'Data berhasil diperbarui.');
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                "error" => $e,
                "error message" => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        DB::beginTransaction();

        try {
            $submission = Submission::where("id", $request->id)->first();
            $submission->active = false;
            $submission->save();

            $user = Auth::user();
            $historyDeleteSubmission = [];
            $historyDeleteSubmission["type_menu"] = "Submission";
            $historyDeleteSubmission["method"] = "Delete";
            $historyDeleteSubmission["meta"] = json_encode([
                "user" => $user["id"],
                "createdAt" => date("Y-m-d H:i:s"),
                "dataChange" => $submission->getChanges(),
            ], JSON_THROW_ON_ERROR);

            $historyDeleteSubmission["user_id"] = $user["id"];
            $historyDeleteSubmission["menu_id"] = $request->id;

            HistoryUpdate::create($historyDeleteSubmission);

            DB::commit();

            return redirect()
                ->route('list_submission_form')
                ->with('success', 'Data berhasil dihapus.');
        } catch (Exception $e) {
            DB::rollback();

            return response()->json([
                "error" => $e,
                "error message" => $e->getMessage(),
            ], 500);
        }
    }

    public function addApi(Request $request): \Illuminate\Http\JsonResponse
    {
        $data = json_decode($request->acc_data, true)[0];
        // $temp = (json_decode($data[0], true));
        return response()->json([
                        "result" => 0,
                        "data" => ($data["type"] === "MGM"),
                    ], 401);
        DB::beginTransaction();

        try {
            $data['code'] = "SUB_M/" . strtotime(date("Y-m-d H:i:s")) . "/" . substr($data['phone'], -4);
            $data['cso_id'] = Cso::where('code', $data['cso_id'])->first()['id'];

            if ($data["type"] === "MGM") {
                $arrValidator = [
                        "no_member" => ["required"],
                        "name" => ["required", "string", "max:191"],
                        "phone" => ["required"],
                        "province" => ["required"],
                        "city" => ["required"],
                        "district" => ["required"],
                        "address" => ["required"],
                        "branch_id" => ["required"],
                        "cso_id" => ["required"],
                        "name_ref" => ["required", "array", "min:1"],
                        "name_ref.*" => ["required"],
                        "age_ref" => ["required", "array", "min:1"],
                        "age_ref.*" => ["required"],
                        "phone_ref" => ["required", "array", "min:1"],
                        "phone_ref.*" => ["required"],
                        "province_ref" => ["required", "array", "min:1"],
                        "province_ref.*" => ["required"],
                        "city_ref" => ["required", "array", "min:1"],
                        "city_ref.*" => ["required"],
                        "promo_1" => ["required", "array", "min:1"],
                        "promo_1.*" => ["required"],
                        "qty_1" => ["required", "array", "min:1"],
                        "qty_1.*" => ["required"],

                        "promo_2" => ["array"],
                        "promo_2.*" => ["required_with_all:qty_2.*"],
                        "qty_2" => ["array"],
                        "qty_2.*" => ["required_with_all:promo_2.*"],

                        "do-image_1" => ["required", "array"],
                        "do-image_1.*" => ["required", "image"],
                    ];
                for ($i=2; $i <= count($data["name_ref"]); $i++) {
                    $arrValidator["do-image_".$i] = ["required", "array"];
                    $arrValidator["do-image_".$i.".*"] = ["required", "image"];
                }

                $validator = Validator::make(
                    $data, $arrValidator
                );

                if ($validator->fails()) {
                    return response()->json([
                        "result" => 0,
                        "data" => $validator->errors(),
                    ], 401);
                }

                $submission = Submission::create($data);

                $dataCount = count($data["name_ref"]);
                for ($i = 0; $i < $dataCount; $i++) {
                    if (!empty($data["name_ref"][$i])) {
                        $reference = new Reference();
                        $reference->submission_id = $submission->id;
                        $reference->name = $data["name_ref"][$i];
                        $reference->age = $data["age_ref"][$i];
                        $reference->phone = $data["phone_ref"][$i];
                        $reference->province = $data["province_ref"][$i];
                        $reference->city = $data["city_ref"][$i];
                        $reference->save();

                        $referencePromo = new ReferencePromo();
                        $referencePromo->reference_id = $reference->id;

                        if ($data["promo_1"][$i] !== "other") {
                            $referencePromo->promo_1 = $data["promo_1"][$i];
                        }

                        if ($data["promo_2"][$i] !== "other") {
                            $referencePromo->promo_2 = $data["promo_2"][$i];
                        }

                        $referencePromo->qty_1 = $data["qty_1"][$i];

                        if (!empty($data["promo_2"][$i]) || !empty($data["other_2"][$i])) {
                            $referencePromo->qty_2 = $data["qty_2"][$i];
                        }

                        $referencePromo->other_1 = $data["other_1"][$i];
                        $referencePromo->other_2 = $data["other_2"][$i];
                        $referencePromo->save();

                        $user_id = Auth::user()["id"];
                        $path = "sources/registration";
                        $referenceImage = new ReferenceImage();
                        $referenceImage->reference_id = $reference->id;
                        foreach ($request->file("do-image_" . ($i + 1)) as $image) {
                            $fileName = ((string)time())
                                . "_"
                                . $user_id
                                . "_"
                                . $i
                                . "."
                                . $image->getClientOriginalExtension();

                            $image->move($path, $fileName);

                            $referenceImage["image_" . ($i + 1)] = $fileName;

                            $i++;
                        }
                        $referenceImage->save();
                    }
                }
            } elseif ($data["type"] === "referensi") {
                $validator = Validator::make(
                    $request->all(),
                    [
                        "no_member" => ["required"],
                        "name" => ["required", "string", "max:191"],
                        "phone" => ["required"],
                        "province" => ["required"],
                        "city" => ["required"],
                        "district" => ["required"],
                        "address" => ["required"],
                        "branch_id" => ["required"],
                        "cso_id" => ["required"],
                        "proof_image" => ["required", "array"],
                        "proof_image.*" => ["required", "image"],
                        "name_ref" => ["required", "array", "min:1"],
                        "age_ref" => ["required", "array", "min:1"],
                        "phone_ref" => ["required", "array", "min:1"],
                        "province_ref" => ["required", "array", "min:1"],
                        "city_ref" => ["required", "array", "min:1"],
                        "souvenir_id" => ["required", "array", "min:1"],
                    ]
                );

                if ($validator->fails()) {
                    return response()->json([
                        "result" => 0,
                        "data" => $validator->errors(),
                    ], 401);
                }

                $submission = Submission::create($data);

                $user_id = Auth::user()["id"];
                $i = 1;
                $path = "sources/registration";
                $submissionImage = new SubmissionImage();
                $submissionImage->submission_id = $submission->id;
                foreach ($request->file("proof_image") as $image) {
                    $fileName = ((string)time())
                        . "_"
                        . $user_id
                        . "_"
                        . $i
                        . "."
                        . $image->getClientOriginalExtension();

                    $image->move($path, $fileName);

                    $submissionImage["image_" . $i] = $fileName;

                    $i++;
                }
                $submissionImage->save();

                $dataCount = count($data["name_ref"]);
                for ($i = 0; $i < $dataCount; $i++) {
                    if (!empty($data["name_ref"][$i])) {
                        $reference = new Reference();
                        $reference->submission_id = $submission->id;
                        $reference->name = $data["name_ref"][$i];
                        $reference->age = $data["age_ref"][$i];
                        $reference->phone = $data["phone_ref"][$i];
                        $reference->province = $data["province_ref"][$i];
                        $reference->city = $data["city_ref"][$i];
                        $reference->save();

                        $referenceSouvenir = new ReferenceSouvenir();
                        $referenceSouvenir->reference_id = $reference->id;
                        $referenceSouvenir->souvenir_id = $data["souvenir_id"][$i];
                        $referenceSouvenir->link_hs = $data["link_hs"][$i];
                        $referenceSouvenir->save();
                    }
                }
            } elseif ($data["type"] === "takeaway") {
                $validator = Validator::make(
                    $request->all(),
                    [
                        "name" => ["required", "string", "max:191"],
                        "phone" => ["required"],
                        "province" => ["required"],
                        "city" => ["required"],
                        "district" => ["required"],
                        "address" => ["required"],
                        "promo_1" => ["required"],
                        "qty_1" => ["required"],
                        "branch_id" => ["required"],
                        "cso_id" => ["required"],
                        "nomor_do" => ["required"],
                        "do_image" => ["required", "array"],
                        "do_image.*" => ["required", "image"],
                    ]
                );

                if ($validator->fails()) {
                    return response()->json([
                        "result" => 0,
                        "data" => $validator->errors(),
                    ], 401);
                }

                $submission = Submission::create($data);

                $submissionPromo = new SubmissionPromo();
                $submissionPromo->submission_id = $submission->id;

                if ($data["promo_1"] !== "other") {
                    $submissionPromo->promo_1 = $data["promo_1"];
                }

                if ($data["promo_2"] !== "other") {
                    $submissionPromo->promo_2 = $data["promo_2"];
                }

                $submissionPromo->qty_1 = $data["qty_1"];

                if (!empty($data["promo_2"]) || !empty($data["other_2"])) {
                    $submissionPromo->qty_2 = $data["qty_2"];
                }

                $submissionPromo->other_1 = $data["other_1"];
                $submissionPromo->other_2 = $data["other_2"];
                $submissionPromo->save();

                $submissionDO = new SubmissionDeliveryorder();
                $submissionDO->submission_id = $submission->id;
                $submissionDO->no_deliveryorder = $data["nomor_do"];
                $submissionDO->save();

                $user_id = Auth::user()["id"];
                $i = 1;
                $path = "sources/registration";

                $submissionImage = new SubmissionImage();
                $submissionImage->submission_id = $submission->id;
                foreach ($request->file("do_image") as $image) {
                    $fileName = ((string)time())
                        . "_"
                        . $user_id
                        . "_"
                        . $i
                        . "."
                        . $image->getClientOriginalExtension();

                    $image->move($path, $fileName);

                    $submissionImage["image_" . $i] = $fileName;

                    $i++;
                }
                $submissionImage->save();
            }

            DB::commit();

            $queryReference = "";
            if ($data["type"] === "mgm") {
                $querySubmission = $this->querySubmissionMGM($submission->id);
                $queryReference = $this->queryReferenceMGM($submission->id);
            } elseif ($data["type"] === "referensi") {
                $querySubmission = $this->querySubmissionReferensi($submission->id);
                $queryReference = $this->queryReferenceReferensi($submission->id);
            } elseif ($data["type"] === "takeaway") {
                $querySubmission = $this->querySubmissionTakeaway($submission->id);
            }

            return response()->json([
                "result" => 1,
                "dataSubmission" => $querySubmission,
                "dataReference" => $queryReference,
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                "result" => 0,
                "data" => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateApi(Request $request)
    {
        $messages = array(
            'cso_id.required' => 'The CSO Code field is required.',
            'cso_id.exists' => 'Wrong CSO Code.',
            'branch_id.required' => 'The Branch must be selected.',
            'branch_id.exists' => 'Please choose the branch.',
        );

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'city' => 'required',
            'cso_id' => ['required', 'exists:csos,code'],
            'branch_id' => ['required', 'exists:branches,id'],
        ], $messages);

        if ($validator->fails()) {
            $data = [
                'result' => 0,
                'data' => $validator->errors(),
            ];

            return response()->json($data, 401);
        }

        DB::beginTransaction();

        try {
            $submission = Submission::find($request->id);

            if ($request->type === "mgm") {
                $submission->fill($request->only(
                    "no_member",
                    "name",
                    "phone",
                    "province",
                    "city",
                    "district",
                    "address",
                    "branch_id",
                ));
                $csoId = Cso::where('code', $request->cso_id)->first()['id'];
                $submission->cso_id = $csoId;
                $submission->save();
            } elseif ($request->type === "referensi") {
                $submission->fill($request->only(
                    "no_member",
                    "name",
                    "phone",
                    "province",
                    "city",
                    "district",
                    "address",
                    "branch_id",
                ));
                $csoId = Cso::where('code', $request->cso_id)->first()['id'];
                $submission->cso_id = $csoId;
                $submission->save();

                $user_id = Auth::user()["id"];
                $path = "sources/registration";
                $submissionImage = SubmissionImage::where("submission_id", $request->id)->first();
                for ($i = 1; $i <= 5; $i++) {
                    $imageInput = "proof_image_" . $i;
                    if ($request->hasFile($imageInput)) {
                        $fileName = ((string)time())
                            . "_"
                            . $user_id
                            . "_"
                            . $i
                            . "."
                            . $request->file($imageInput)->getClientOriginalExtension();

                        $request->file($imageInput)->move($path, $fileName);

                        $submissionImage["image_" . $i] = $fileName;
                    }
                }

                $submissionImage->save();
            } elseif ($request->type === "takeaway") {
                $submission->fill($request->only(
                    "no_member",
                    "name",
                    "phone",
                    "province",
                    "city",
                    "district",
                    "address",
                    "branch_id",
                ));
                $csoId = Cso::where('code', $request->cso_id)->first()['id'];
                $submission->cso_id = $csoId;
                $submission->save();

                $submissionPromo = SubmissionPromo::where("submission_id", $request->id)->first();
                if ($request->promo_1 !== "other") {
                    $submissionPromo->promo_1 = $request->promo_1;
                }

                if ($request->promo_2 !== "other") {
                    $submissionPromo->promo_2 = $request->promo_2;
                }

                $submissionPromo->qty_1 = $request->qty_1;

                if (
                    !empty($request->promo_2)
                    || !empty($request->other_2)
                ) {
                    $submissionPromo->qty_2 = $request->qty_2;
                }

                $submissionPromo->other_1 = $request->other_1;
                $submissionPromo->other_2 = $request->other_2;
                $submissionPromo->save();

                $submissionDO = SubmissionDeliveryorder::where("submission_id", $request->id)->first();
                $submissionDO->no_deliveryorder = $request->other_2;
                $submissionDO->save();

                $user_id = Auth::user()["id"];
                $path = "sources/registration";
                $submissionImage = SubmissionImage::where("submission_id", $request->id)->first();
                for ($i = 1; $i <= 5; $i++) {
                    $imageInput = "do_image_" . $i;
                    if ($request->hasFile($imageInput)) {
                        $fileName = ((string)time())
                            . "_"
                            . $user_id
                            . "_"
                            . $i
                            . "."
                            . $request->file($imageInput)->getClientOriginalExtension();

                        $request->file($imageInput)->move($path, $fileName);

                        $submissionImage["image_" . $i] = $fileName;
                    }
                }
                $submissionImage->save();
            }

            DB::commit();

            $queryReference = "";
            if ($request->type === "mgm") {
                $querySubmission = $this->querySubmissionMGM($submission->id);
                $queryReference = $this->queryReferenceMGM($submission->id);
            } elseif ($request->type === "referensi") {
                $querySubmission = $this->querySubmissionReferensi($submission->id);
                $queryReference = $this->queryReferenceReferensi($submission->id);
            } elseif ($request->type === "takeaway") {
                $querySubmission = $this->querySubmissionTakeaway($submission->id);
            }

            return response()->json([
                "result" => 1,
                "dataSubmission" => $querySubmission,
                "dataReference" => $queryReference,
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                "result" => 0,
                "data" => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listApi(Request $request): \Illuminate\Http\JsonResponse
    {
        // Menyimpan request ke dalam variabel $url untuk pagination
        $url = $request->all();

        try {
            // Query dari tabel submissions, dan menampilkan 10 data per halaman
            $submissions = Submission::select(
                "submissions.id AS id",
                "submissions.code AS code",
                "submissions.name AS customer_name",
                "submissions.type AS type",
                "submissions.created_at",
                "branches.code AS branch_code",
                "branches.name AS branch_name",
                "csos.code AS cso_code",
                "csos.name AS cso_name",
            )
            ->leftJoin(
                "branches",
                "branches.id",
                "=",
                "submissions.branch_id"
            )
            ->leftJoin(
                "csos",
                "csos.id",
                "=",
                "submissions.cso_id"
            )
            ->where("submissions.active", true)
            ->orderBy(DB::raw("DATE(submissions.created_at)"), "desc");

            $user = User::find($request->user_id);
            if ($user->roles[0]->slug === "cso") {
                $submissions = $submissions->where("cso_id");
            } elseif (
                $user->roles[0]->slug === "branch"
                || $user->roles[0]->slug === "area-manager"
            ) {
                $arrBranches = [];

                foreach ($user->listBranches() as $value) {
                    $arrBranches[] = $value['id'];
                }

                $submissions = $submissions->whereIn("branch_id", $arrBranches);
            }

            if (!empty($request->filter_type)) {
                $submissions = $submissions->where("submissions.type", $request->filter_type);
            }

            $submissions = $submissions->paginate(10);

            return response()->json([
                "result" => 1,
                "data" => $submissions,
                "url" => $url,
            ]);
        } catch (Exception $e) {
            return response()->json([
                "result" => 0,
                "data" => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function detailApi(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $references = "";
            if ($request->type === "mgm") {
                $submission = $this->querySubmissionMGM($request->id);
                $references = $this->queryReferenceMGM($request->id);
            } elseif ($request->type === "referensi") {
                $submission = $this->querySubmissionReferensi($request->id);
                $references = $this->queryReferenceReferensi($request->id);
            } elseif ($request->type === "takeaway") {
                $submission = $this->querySubmissionTakeaway($request->id);
            }

            $historySubmission = $this->queryHistory($request->id);

            return response()->json([
                "result" => 1,
                "dataSubmission" => $submission,
                "dataReference" => $references,
                "history" => $historySubmission,
            ]);
        } catch (Exception $e) {
            return response()->json([
                "result" => 0,
                "data" => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteApi(Request $request): \Illuminate\Http\JsonResponse
    {
        $messages = [
            'id.required' => "There's an error with the data.",
        ];

        $validator = Validator::make($request->all(), [
            'id' => [
                'required',
            ]
        ], $messages);

        if ($validator->fails()) {
            $data = [
                'result' => 0,
                'data' => $validator->errors(),
            ];

            return response()->json($data, 400);
        }

        DB::beginTransaction();

        try {
            $submission = Submission::where("id", $request->id)->first();
            $submission->active = false;
            $submission->save();

            $historyDeleteSubmission = [];
            $historyDeleteSubmission["type_menu"] = "Submission";
            $historyDeleteSubmission["method"] = "Delete";
            $historyDeleteSubmission["meta"] = json_encode([
                "user" => $request->user_id,
                "createdAt" => date("Y-m-d H:i:s"),
                "dataChange" => $submission->getChanges(),
            ], JSON_THROW_ON_ERROR);

            $historyDeleteSubmission["user_id"] = $request->user_id;
            $historyDeleteSubmission["menu_id"] = $request->id;

            HistoryUpdate::create($historyDeleteSubmission);

            DB::commit();

            return response()->json([
                "result" => 1,
            ]);
        } catch (Exception $e) {
            DB::rollback();

            return response()->json([
                "result" => 0,
                "data" => $e->getMessage(),
            ], 500);
        }
    }

    private function querySubmission($id)
    {
        return Submission::select(
            "submissions.id AS id",
            "submissions.code AS code",
            "submissions.no_member AS no_member",
            "branches.code AS branch_code",
            "branches.name AS branch_name",
            "csos.code AS cso_code",
            "csos.name AS cso_name",
            "submissions.type AS type",
            "submissions.name AS name",
            "submissions.address AS address",
            "submissions.phone AS phone",
            "submissions.province AS province_id",
            "raja_ongkir__cities.province AS province",
            "submissions.city AS city_id",
            DB::raw("CONCAT(raja_ongkir__cities.type, ' ', raja_ongkir__cities.city_name) AS city"),
            "submissions.district AS district_id",
            "raja_ongkir__subdistricts.subdistrict_name AS district",
            "submissions.created_at AS created_at",
        )
        ->leftJoin("branches", "submissions.branch_id", "=", "branches.id")
        ->leftJoin("csos", "submissions.cso_id", "=", "csos.id")
        ->leftJoin(
            "raja_ongkir__cities",
            "submissions.city",
            "=",
            "raja_ongkir__cities.city_id"
        )
        ->leftJoin(
            "raja_ongkir__subdistricts",
            "submissions.district",
            "=",
            "raja_ongkir__subdistricts.subdistrict_id"
        )
        ->where("submissions.id", $id);
    }

    private function querySubmissionMGM($id)
    {
        $submission = $this->querySubmission($id);

        return $submission->first();
    }

    private function querySubmissionReferensi($id)
    {
        $submission = $this->querySubmission($id);

        return $submission->addSelect(
            "submission_images.image_1 AS image_1",
            "submission_images.image_2 AS image_2",
            "submission_images.image_3 AS image_3",
            "submission_images.image_4 AS image_4",
            "submission_images.image_5 AS image_5",
        )
        ->leftJoin(
            "submission_images",
            "submissions.id",
            "=",
            "submission_images.submission_id"
        )
        ->first();
    }

    private function querySubmissionTakeaway($id)
    {
        $submission = $this->querySubmission($id);

        return $submission->addSelect(
            "submission_promos.promo_1 AS promo_1",
            "submission_promos.promo_2 AS promo_2",
            "submission_promos.qty_1 AS qty_1",
            "submission_promos.qty_2 AS qty_2",
            "submission_promos.other_1 AS other_1",
            "submission_promos.other_2 AS other_2",
            "submission_deliveryorders.no_deliveryorder AS no_deliveryorder",
            "submission_images.image_1 AS image_1",
            "submission_images.image_2 AS image_2",
            "submission_images.image_3 AS image_3",
            "submission_images.image_4 AS image_4",
            "submission_images.image_5 AS image_5",
        )
        ->leftJoin(
            "submission_promos",
            "submissions.id",
            "=",
            "submission_promos.submission_id"
        )
        ->leftJoin(
            "submission_deliveryorders",
            "submissions.id",
            "=",
            "submission_deliveryorders.submission_id"
        )
        ->leftJoin(
            "submission_images",
            "submissions.id",
            "=",
            "submission_images.submission_id"
        )
        ->first();
    }

    private function queryReference($submissionId)
    {
        return Reference::select(
            "references.id AS id",
            "references.submission_id AS submission_id",
            "references.name AS name",
            "references.age AS age",
            "references.phone AS phone",
            "references.province AS province_id",
            "raja_ongkir__cities.province AS province",
            "references.city AS city_id",
            DB::raw("CONCAT(raja_ongkir__cities.type, ' ', raja_ongkir__cities.city_name) AS city"),
        )
        ->leftJoin(
            "raja_ongkir__cities",
            "references.city",
            "=",
            "raja_ongkir__cities.city_id"
        )
        ->where("references.submission_id", $submissionId)
        ->orderBy("references.id");
    }

    private function queryReferenceMGM($submissionId)
    {
        $references = $this->queryReference($submissionId);

        return $references->addSelect(
            "reference_promos.promo_1 AS promo_1",
            "reference_promos.promo_2 AS promo_2",
            "reference_promos.qty_1 AS qty_1",
            "reference_promos.qty_2 AS qty_2",
            "reference_promos.other_1 AS other_1",
            "reference_promos.other_2 AS other_2",
            "reference_images.image_1 AS image_1",
            "reference_images.image_2 AS image_2",
        )
        ->leftJoin(
            "reference_promos",
            "references.id",
            "=",
            "reference_promos.reference_id"
        )
        ->leftJoin(
            "reference_images",
            "references.id",
            "=",
            "reference_images.reference_id"
        )
        ->get();
    }

    private function queryReferenceReferensi($submissionId)
    {
        $references = $this->queryReference($submissionId);

        return $references->addSelect(
            "reference_souvenirs.souvenir_id AS souvenir_id",
            "souvenirs.name AS souvenir_name",
            "reference_souvenirs.status AS status",
            "reference_souvenirs.link_hs AS link_hs",
        )
        ->leftJoin(
            "reference_souvenirs",
            "references.id",
            "=",
            "reference_souvenirs.reference_id"
        )
        ->leftJoin(
            "souvenirs",
            "reference_souvenirs.souvenir_id",
            "=",
            "souvenirs.id"
        )
        ->get();
    }

    private function queryHistory($submissionId)
    {
        return HistoryUpdate::select(
            "history_updates.method AS method",
            "history_updates.created_at AS created_at",
            "history_updates.meta AS meta",
            "users.name AS name",
        )
        ->leftJoin(
            "users",
            "users.id",
            "=",
            "history_updates.user_id"
        )
        ->where("history_updates.type_menu", "Submission")
        ->where("history_updates.menu_id", $submissionId)
        ->get();
    }
}
