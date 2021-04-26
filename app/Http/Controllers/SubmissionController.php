<?php

namespace App\Http\Controllers;

use App\Branch;
use App\Cso;
use App\DeliveryOrder;
use App\HistoryUpdate;
use App\RajaOngkir_City;
use App\Product;
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
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class SubmissionController extends Controller
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

            $submissions = Submission::WhereIn(
                "branch_id",
                $arrBranches
            )
            ->where($whereArray)
            ->paginate(10);
        } else {
            $submissions = Submission::where($whereArray)->paginate(10);
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

        return view(
            "admin.add_submission_reference",
            compact(
                'promos',
                'branches',
                'csos',
                "souvenirs",
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

                    if (isset($data["promo_1"][$i])) {
                        if ($data["promo_1"][$i] !== "other") {
                            $referencePromo->promo_1 = $data["promo_1"][$i];
                        }
                    }

                    if (isset($data["promo_2"][$i])) {
                        if ($data["promo_2"][$i] !== "other") {
                            $referencePromo->promo_2 = $data["promo_2"][$i];
                        }
                    }

                    $referencePromo->qty_1 = $data["qty_1"][$i];

                    if (
                        isset($data["promo_2"][$i])
                        || isset($data["other_2"][$i])
                    ) {
                        if (
                            !empty($data["promo_2"][$i])
                            || !empty($data["other_2"][$i])
                        ) {
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
                    foreach ($request->file("do-image_" . ($i + 1)) AS $image) {
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
                    $referenceSouvenir->link_hs = $data["link_hs"][$i];
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

        return view(
            "admin.detail_submission_" . $request->type,
            compact(
                "submission",
                "references",
                "historySubmission",
            )
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse
     */
    /* public function edit(Request $request)
    {
        if ($request->has("id")) {
            $branches = Branch::all();
            $csos = Cso::all();
            $deliveryOrders = DeliveryOrder::find($request->get("id"));
            $promos = Promo::all();
            $references = Reference::where(
                "deliveryorder_id",
                $request->get("id")
            )
            ->orderBy("id", "asc")
            ->get();
            $souvenirs = Souvenir::select("id", "name")
            ->where("active", true)
            ->get();

            $arrayReference = [];
            foreach ($references as $key => $reference) {
                $arrayReference[$key]["id"] = $reference->id;
                $arrayReference[$key]["name"] = $reference->name;
                $arrayReference[$key]["age"] = $reference->age;
                $arrayReference[$key]["phone"] = $reference->phone;
                $arrayReference[$key]["province"] = $reference->province;
                $arrayReference[$key]["city"] = $reference->city;
            }

            return view(
                "admin.update_submission_form",
                compact(
                    "arrayReference",
                    "branches",
                    "csos",
                    "deliveryOrders",
                    "promos",
                    "souvenirs",
                )
            );
        } else {
            return response()->json(['result' => 'Gagal!!']);
        }
    } */

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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        // Inisialisasi variabel $deliveryOrder untuk memperbarui data
        $deliveryOrder = DeliveryOrder::find($request->input("idDeliveryOrder"));

        DB::beginTransaction();

        try {
            $deliveryOrder->no_member = $request->input("no_member");
            $deliveryOrder->name = $request->input("name");
            $deliveryOrder->address = $request->input("address");
            $deliveryOrder->phone = $request->input("phone");

            // Pembentukan array product
            $data = $request->all();
            $data['arr_product'] = [];
            foreach ($data as $key => $value) {
                $arrKey = explode("_", $key);
                if ($arrKey[0] == 'product') {
                    if (isset($data['qty_'.$arrKey[1]])) {
                        $data['arr_product'][$key] = [];
                        $data['arr_product'][$key]['id'] = $value;
                        $data['arr_product'][$key]['qty'] = $data['qty_'.$arrKey[1]];
                    }
                }
            }

            $deliveryOrder->arr_product = json_encode($data['arr_product']);

            $getCSOid = Cso::where('code', $request->input("cso_id"))->first()['id'];
            $deliveryOrder->cso_id = $getCSOid;
            $deliveryOrder->branch_id = $request->input("branch_id");
            $deliveryOrder->province = $request->input("province_id");
            $deliveryOrder->city = $request->input("city");
            $deliveryOrder->distric = $request->input("distric");
            $deliveryOrder->type_register = $request->input("type_register");

            // Mengecek apakah ada gambar yang diupload atau tidak
            if ($request->hasFile("image_proof")) {
                // Inisialisasi variabel data["image"]
                $data["image"] = [];
                // Inisialisasi jalur di mana gambar akan diletakkan
                $path = "sources/registration";

                // Mengecek apakah jalur untuk gambar sudah ada atau belum
                if (!is_dir($path)) {
                    // Jika belum, maka akan membuat folder/directory baru
                    File::makeDirectory($path, 0777, true, true);
                }

                // Inisialisasi variabel $j untuk counter dan sebagai suffix nama file
                $j = 0;
                foreach ($request->file("image_proof") as $image) {
                    // Inisialisasi nama gambar dengan UNIX Timestamp
                    $fileName = strval(time());
                    // Memberikan suffix dengan $j agar nama gambar tidak sama, dan juga menambahkan file extension
                    $fileName .= "_" . $j . "." . $image->getClientOriginalExtension();
                    // Menyimpan gambar
                    $image->move($path, $fileName);
                    // Push nama gambar ke dalam variabel data["image"]
                    $data["image"][] = $fileName;
                    // Increment variabel $j
                    $j++;
                }

                // Convert array pada $data["image"] menjadi JSON
                $data["image"] = json_encode($data["image"], JSON_FORCE_OBJECT);

                $deliveryOrder->image = $data["image"];
            }

            // Menyimpan pembaruan data delivery_orders
            $deliveryOrder->save();

            //Inisialisasi variabel $user untuk riwayat pembaruan
            $user = Auth::user();

            // Mengisi variabel $historyDeliveryOrder dengan data yang diperbarui
            $historyDeliveryOrder = [];
            $historyDeliveryOrder["type_menu"] = "Delivery Order";
            $historyDeliveryOrder["method"] = "Update";
            $historyDeliveryOrder["meta"] = json_encode(
                [
                    "user" => $user["id"],
                    "createdAt" => date("Y-m-d H:i:s"),
                    "dataChange" => $deliveryOrder->getChanges(),
                ]
            );

            $historyDeliveryOrder["user_id"] = $user["id"];
            $historyDeliveryOrder["menu_id"] = $deliveryOrder->id;

            // Menyimpan riwayat pembaruan data delivery_orders ke tabel history_updates
            HistoryUpdate::create($historyDeliveryOrder);

            $dataCount = count($data["name_ref"]);
            for ($i = 0; $i < $dataCount; $i++) {
                $reference = Reference::find($data["id_reference"][$i]);

                $reference->name = $data["name_ref"][$i];
                $reference->age = $data["age_ref"][$i];
                $reference->phone = $data["phone_ref"][$i];
                $reference->province = $data["province_ref"][$i];
                $reference->city = $data["city_ref"][$i];
                $reference->souvenir_id = $data["souvenir_id"][$i];
                $reference->link_hs = $data["link_hs"][$i];

                // Menyimpan pembaruan data references
                $reference->save();

                // Mengisi variabel $historyReference dengan data yang diperbarui
                $historyReference = [];
                $historyReference["type_menu"] = "Reference";
                $historyReference["method"] = "Update";
                $historyReference["meta"] = json_encode(
                    [
                        "user" => $user["id"],
                        "createdAt" => date("Y-m-d H:i:s"),
                        "dataChange" => $reference->getChanges(),
                    ]
                );

                $historyReference["user_id"] = $user["id"];
                $historyReference["menu_id"] = $reference->id;

                // Menyimpan riwayat pembaruan data references ke tabel history_updates
                HistoryUpdate::create($historyReference);
            }

            DB::commit();

            return redirect()
                ->route('list_submission_form')
                ->with('success', 'Data berhasil diperbarui');
        } catch (Exception $e) {
            DB::rollback();

            return response()->json([
                "error" => $e,
                "erroe message" => $e->getMessage(),
            ]);
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

    public function addApi(Request $request)
    {
        $data = $request->all();

        DB::beginTransaction();

        try {
            $data['code'] = "SUB_M/" . strtotime(date("Y-m-d H:i:s")) . "/" . substr($data['phone'], -4);
            $data['cso_id'] = Cso::where('code', $data['cso_id'])->first()['id'];

            if ($data["type"] === "mgm") {
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
            } else {
                return response()->json([
                    "error" => "Bad request.",
                ], 401);
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
                "error" => $e,
                "error message" => $e->getMessage(),
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
            'product_0' => 'required',
            'qty_0' => 'required',
        ], $messages);

        if ($validator->fails()) {
            $data = [
                'result' => 0,
                'data' => $validator->errors(),
            ];

            return response()->json($data, 401);
        } else {
            // Inisialisasi variabel $deliveryOrder untuk memperbarui data
            $deliveryOrder = DeliveryOrder::find($request->input("idDeliveryOrder"));

            DB::beginTransaction();

            try {
                $deliveryOrder->no_member = $request->input("no_member");
                $deliveryOrder->name = $request->input("name");
                $deliveryOrder->address = $request->input("address");
                $deliveryOrder->phone = $request->input("phone");

                // Pembentukan array product
                $data = $request->all();
                $data['arr_product'] = [];
                foreach ($data as $key => $value) {
                    $arrKey = explode("_", $key);
                    if ($arrKey[0] == 'product') {
                        if (isset($data['qty_'.$arrKey[1]])) {
                            $data['arr_product'][$key] = [];
                            $data['arr_product'][$key]['id'] = $value;
                            $data['arr_product'][$key]['qty'] = $data['qty_'.$arrKey[1]];
                        }
                    }
                }

                $deliveryOrder->arr_product = json_encode($data['arr_product']);

                $getCSOid = Cso::where('code', $request->input("cso_id"))->first()['id'];
                $deliveryOrder->cso_id = $getCSOid;
                $deliveryOrder->branch_id = $request->input("branch_id");
                $deliveryOrder->province = $request->input("province_id");
                $deliveryOrder->city = $request->input("city");
                $deliveryOrder->distric = $request->input("distric");
                $deliveryOrder->type_register = $request->input("type_register");

                // Mengecek apakah ada gambar yang diupload atau tidak
                if ($request->hasFile("image_proof")) {
                    // Inisialisasi variabel data["image"]
                    $data["image"] = [];
                    // Inisialisasi jalur di mana gambar akan diletakkan
                    $path = "sources/registration";

                    // Mengecek apakah jalur untuk gambar sudah ada atau belum
                    if (!is_dir($path)) {
                        // Jika belum, maka akan membuat folder/directory baru
                        File::makeDirectory($path, 0777, true, true);
                    }

                    // Inisialisasi variabel $j untuk counter dan sebagai suffix nama file
                    $j = 0;
                    foreach ($request->file("image_proof") as $image) {
                        // Inisialisasi nama gambar dengan UNIX Timestamp
                        $fileName = (string)time();
                        // Memberikan suffix dengan $j agar nama gambar tidak sama, dan juga menambahkan file extension
                        $fileName .= "_" . $j . "." . $image->getClientOriginalExtension();
                        // Menyimpan gambar
                        $image->move($path, $fileName);
                        // Push nama gambar ke dalam variabel data["image"]
                        $data["image"][] = $fileName;
                        // Increment variabel $j
                        $j++;
                    }

                    // Convert array pada $data["image"] menjadi JSON
                    $data["image"] = json_encode($data["image"], JSON_FORCE_OBJECT);

                    $deliveryOrder->image = $data["image"];
                }

                // Menyimpan pembaruan data delivery_orders
                $deliveryOrder->save();

                // Mengisi variabel $historyDeliveryOrder dengan data yang diperbarui
                $historyDeliveryOrder = [];
                $historyDeliveryOrder["type_menu"] = "Delivery Order";
                $historyDeliveryOrder["method"] = "Update";
                $historyDeliveryOrder["meta"] = json_encode(
                    [
                        "user" => $request->input("user_id"),
                        "createdAt" => date("Y-m-d H:i:s"),
                        "dataChange" => $deliveryOrder->getChanges(),
                    ]
                );

                $historyDeliveryOrder["user_id"] = $request->input("user_id");
                $historyDeliveryOrder["menu_id"] = $deliveryOrder->id;

                // Menyimpan riwayat pembaruan data delivery_orders ke tabel history_updates
                HistoryUpdate::create($historyDeliveryOrder);

                $referenceArray = [];
                $dataCount = count($data["name_ref"]);
                for ($i = 0; $i < $dataCount; $i++) {
                    $reference = Reference::find($data["id_reference"][$i]);

                    $reference->name = $data["name_ref"][$i];
                    $reference->age = $data["age_ref"][$i];
                    $reference->phone = $data["phone_ref"][$i];
                    $reference->province = $data["province_ref"][$i];
                    $reference->city = $data["city_ref"][$i];
                    $reference->souvenir_id = $data["souvenir_id"][$i];
                    $reference->link_hs = $data["link_hs"][$i];
                    $reference->status = $data["status"][$i];

                    // Menyimpan pembaruan data references
                    $reference->save();

                    $referenceArray[] = $reference;

                    // Mengisi variabel $historyReference dengan data yang diperbarui
                    $historyReference = [];
                    $historyReference["type_menu"] = "Reference";
                    $historyReference["method"] = "Update";
                    $historyReference["meta"] = json_encode(
                        [
                            "user" => $request->input("user_id"),
                            "createdAt" => date("Y-m-d H:i:s"),
                            "dataChange" => $reference->getChanges(),
                        ]
                    );

                    $historyReference["user_id"] = $request->input("user_id");
                    $historyReference["menu_id"] = $reference->id;

                    // Menyimpan riwayat pembaruan data references ke tabel history_updates
                    HistoryUpdate::create($historyReference);
                }

                DB::commit();

                $dataSubmission = $deliveryOrder;

                $dataSubmission->created_at = date(
                    "Y-m-d",
                    strtotime($dataSubmission->created_at)
                );

                $dataSubmission->customer_name = $dataSubmission->name;
                $dataSubmission->customer_phone = $dataSubmission->phone;

                $getSubmissionCityAndProvince = RajaOngkir_City::leftJoin(
                    "raja_ongkir__subdistricts",
                    "raja_ongkir__subdistricts.city_id",
                    "=",
                    "raja_ongkir__cities.city_id"
                )
                ->select(
                    "raja_ongkir__cities.province",
                    "raja_ongkir__cities.type",
                    "raja_ongkir__cities.city_name",
                    "raja_ongkir__subdistricts.subdistrict_name",
                )
                ->where(
                    "raja_ongkir__subdistricts.subdistrict_id",
                    $dataSubmission->distric
                )
                ->first();

                // Provinsi
                $dataSubmission->province = $getSubmissionCityAndProvince->province;

                // Kota
                $dataSubmission->city = $getSubmissionCityAndProvince->type
                    . " "
                    . $getSubmissionCityAndProvince->city_name;

                // District
                $dataSubmission->district = $getSubmissionCityAndProvince->subdistrict_name;

                // Promo
                $dataSubmission->promo = json_decode($dataSubmission->arr_product);
                $arrayPromo = (Array) $dataSubmission->promo;

                foreach ($arrayPromo as $key => $promoItem) {
                    $getPromo = DeliveryOrder::$Promo[$promoItem->id];
                    $arrayPromo[$key]->name = $getPromo["name"];
                }

                $dataSubmission->promo = $arrayPromo;

                // Branch
                $getBranch = Branch::where("id", $dataSubmission->branch_id)->first();
                $dataSubmission->branch_code = $getBranch->code;
                $dataSubmission->branch_name = $getBranch->name;

                // CSO
                $getCSO = Cso::where("id", $dataSubmission->cso_id)->first();
                $dataSubmission->cso_code = $getCSO->code;
                $dataSubmission->cso_name = $getCSO->name;

                $getReferencesCityAndProvince = RajaOngkir_City::leftJoin(
                    "references",
                    "references.city",
                    "=",
                    "raja_ongkir__cities.city_id"
                )
                ->select(
                    "raja_ongkir__cities.province",
                    "raja_ongkir__cities.type",
                    "raja_ongkir__cities.city_name",
                )
                ->where("references.deliveryorder_id", $dataSubmission->id)
                ->orderBy("references.id")
                ->get();

                foreach ($getReferencesCityAndProvince as $i => $refCityAndProvince) {
                    $referenceArray[$i]->province = $refCityAndProvince->province;
                    $referenceArray[$i]->city = $refCityAndProvince->type
                        . " "
                        . $refCityAndProvince->city_name;

                    unset(
                        $referenceArray[$i]->id,
                        $referenceArray[$i]->deliveryorder_id,
                        $referenceArray[$i]->created_at,
                        $referenceArray[$i]->updated_at,
                    );
                }

                unset(
                    $dataSubmission->name,
                    $dataSubmission->phone,
                    $dataSubmission->arr_product,
                    $dataSubmission->updated_at,
                    $dataSubmission->cso_id,
                    $dataSubmission->branch_id,
                    $dataSubmission->active,
                    $dataSubmission->distric,
                );

                $data = [
                    "result" => 1,
                    "dataSubmission" => $dataSubmission,
                    "dataReference" => $referenceArray,
                ];


                return response()->json($data, 200);
            } catch (Exception $e) {
                DB::rollback();

                $data = [
                    "result" => 0,
                    "error" => $e,
                    "error line" => $e->getLine(),
                    "error messages" => $e->getMessage(),
                ];

                return response()->json($data, 501);
            }
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

        $filterType = "";
        if (!empty($request->filter_type)) {
            $filterType = $request->filter_type;
        }

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
            ->where("submissions.type", $filterType)
            ->where("submissions.active", true)
            ->paginate(10);

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
    public function detailApi(Request $request)
    {
        try {
            $deliveryOrder = DeliveryOrder::select(
                "delivery_orders.id AS id",
                "delivery_orders.code AS code",
                "delivery_orders.no_member AS no_member",
                "delivery_orders.name AS customer_name",
                "delivery_orders.address AS address",
                "delivery_orders.phone AS customer_phone",
                "delivery_orders.arr_product AS arr_product",
                "csos.code AS cso_code",
                "csos.name AS cso_name",
                "branches.code AS branch_code",
                "branches.name AS branch_name",
                "raja_ongkir__cities.province AS province",
                "raja_ongkir__cities.type AS city_type",
                "raja_ongkir__cities.city_name AS city_name",
                "raja_ongkir__subdistricts.subdistrict_name AS district",
            )
            ->leftJoin(
                "raja_ongkir__cities",
                "raja_ongkir__cities.city_id",
                "=",
                "delivery_orders.city"
            )
            ->leftJoin(
                "raja_ongkir__subdistricts",
                "raja_ongkir__subdistricts.subdistrict_id",
                "=",
                "delivery_orders.distric"
            )
            ->leftJoin(
                "branches",
                "branches.id",
                "=",
                "delivery_orders.branch_id"
            )
            ->leftJoin(
                "csos",
                "csos.id",
                "=",
                "delivery_orders.cso_id"
            )
            ->where("delivery_orders.id", $request->id)
            ->where("delivery_orders.active", true)
            ->where("delivery_orders.type_register", "!=", "Normal Register")
            ->first();

            $getPromo = json_decode($deliveryOrder->arr_product);
            $arrayPromo = [];
            foreach ($getPromo as $key => $promoItem) {
                $getIndex = explode("_", $key);

                $promo = Promo::select("code", "product", "price")
                ->where("id", $promoItem->id)
                ->first();

                $productPromo = json_decode($promo["product"]);
                $arrayProductId = [];

                foreach ($productPromo as $pp) {
                    $arrayProductId[] = $pp->id;
                }

                $getProduct = Product::select("code")
                ->whereIn(
                    "id",
                    $arrayProductId
                )
                ->get();

                $arrayProductCode = [];

                foreach ($getProduct as $product) {
                    $arrayProductCode[] = $product->code;
                }

                $productCode = implode(", ", $arrayProductCode);

                $promoName = $promo["code"]
                    . " (" . $productCode . ") "
                    . "Rp. " . number_format((int) $promo["price"], 0, null, ",");

                $arrayPromo[] = [
                    "id" => $getIndex[1],
                    "name" => $promoName,
                    "qty" => $promoItem->qty
                ];
            }

            $deliveryOrder->promo = $arrayPromo;

            $city = $deliveryOrder->city_type . " " . $deliveryOrder->city_name;
            $deliveryOrder->city = $city;

            unset(
                $deliveryOrder->arr_product,
                $deliveryOrder->city_type,
                $deliveryOrder->city_name,
            );

            $references = Reference::select(
                "references.id AS id",
                "references.name AS name",
                "references.age AS age",
                "references.phone AS phone",
                "raja_ongkir__cities.province AS province",
                DB::raw("CONCAT(raja_ongkir__cities.type, ' ', raja_ongkir__cities.city_name) AS city"),
                "souvenirs.name AS souvenir",
                "references.link_hs AS link_hs",
                "references.status AS status",
            )
            ->leftJoin(
                "raja_ongkir__cities",
                "raja_ongkir__cities.city_id",
                "=",
                "references.city"
            )
            ->leftJoin(
                "souvenirs",
                "souvenirs.id",
                "=",
                "references.souvenir_id"
            )
            ->where("references.deliveryorder_id", $request->id)
            ->orderBy("references.id", "asc")
            ->get();

            $data = [
                "result" => 1,
                "dataSubmission" => $deliveryOrder,
                "dataReference" => $references,
            ];

            return response()->json($data, 200);
        } catch (Exception $e) {
            $data = [
                "result" => 0,
                "error" => $e,
                "error line" => $e->getLine(),
                "error messages" => $e->getMessage(),
            ];

            return response()->json($data, 501);
        }

    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteApi(Request $request): \Illuminate\Http\JsonResponse
    {
        $messages = array(
            'id.required' => "There's an error with the data.",
        );

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

    private function querySubmissionMGM($id)
    {
        return Submission::select(
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
            "raja_ongkir__cities.province AS province",
            DB::raw("CONCAT(raja_ongkir__cities.type, ' ', raja_ongkir__cities.city_name) AS city"),
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
        ->where("submissions.id", $id)
        ->first();
    }

    private function querySubmissionReferensi($id)
    {
        return Submission::select(
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
            "raja_ongkir__cities.province AS province",
            DB::raw("CONCAT(raja_ongkir__cities.type, ' ', raja_ongkir__cities.city_name) AS city"),
            "raja_ongkir__subdistricts.subdistrict_name AS district",
            "submissions.created_at AS created_at",
            "submission_images.image_1 AS image_1",
            "submission_images.image_2 AS image_2",
            "submission_images.image_3 AS image_3",
            "submission_images.image_4 AS image_4",
            "submission_images.image_5 AS image_5",
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
        ->leftJoin(
            "submission_images",
            "submissions.id",
            "=",
            "submission_images.submission_id"
        )
        ->where("submissions.id", $id)
        ->first();
    }

    private function querySubmissionTakeaway($id)
    {
        return Submission::select(
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
            "raja_ongkir__cities.province AS province",
            DB::raw("CONCAT(raja_ongkir__cities.type, ' ', raja_ongkir__cities.city_name) AS city"),
            "raja_ongkir__subdistricts.subdistrict_name AS district",
            "submissions.created_at AS created_at",
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
        ->where("submissions.id", $id)
        ->first();
    }

    private function queryReferenceMGM($submissionId)
    {
        return Reference::select(
            "references.submission_id AS submission_id",
            "references.name AS name",
            "references.age AS age",
            "references.phone AS phone",
            "raja_ongkir__cities.province AS province",
            DB::raw("CONCAT(raja_ongkir__cities.type, ' ', raja_ongkir__cities.city_name) AS city"),
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
            "raja_ongkir__cities",
            "references.city",
            "=",
            "raja_ongkir__cities.city_id"
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
        ->where("references.submission_id", $submissionId)
        ->get();
    }

    private function queryReferenceReferensi($submissionId)
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
            "reference_souvenirs.souvenir_id AS souvenir_id",
            "souvenirs.name AS souvenir_name",
            "reference_souvenirs.status AS status",
            "reference_souvenirs.link_hs AS link_hs",
        )
        ->leftJoin(
            "raja_ongkir__cities",
            "references.city",
            "=",
            "raja_ongkir__cities.city_id"
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
        ->where("references.submission_id", $submissionId)
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
