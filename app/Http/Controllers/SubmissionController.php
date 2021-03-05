<?php

namespace App\Http\Controllers;

use App\Branch;
use App\Cso;
use App\DeliveryOrder;
use App\Reference;
use App\Utils;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SubmissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function listSubmission(Request $request)
    {
        // Menyimpan request ke dalam variabel $url untuk pagination
        $url = $request->all();

        // Query dari tabel delivery_orders, dan menampilkan 10 data per halaman
        $deliveryOrders = DeliveryOrder::all()->paginate(10);

        return view(
            "admin.list_submission_form",
            compact(
                "deliveryOrders",
                "url",
            )
        );
    }

    public function listReference(Request $request)
    {
        // Menyimpan request ke dalam variabel $url untuk pagination
        $url = $request->all();

        // Query dari tabel references, dan menampilkan 10 data per halaman
        $references = Reference::all()->paginate(10);

        return view("admin.list_reference", compact("references", "url"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $promos = DeliveryOrder::$Promo;
        $branches = Branch::where('active', true)->get();
        $csos = Cso::all();

        return view('admin.add_submission_form', compact('promos', 'branches', 'csos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $data = $request->all();

            $data['code'] = "DO_BOOK/" . strtotime(date("Y-m-d H:i:s")) . "/" . substr($data['phone'], -4);
            $data['cso_id'] = Cso::where('code', $data['cso_id'])->first()['id'];

            // Pembentukan array product
            $data['arr_product'] = [];
            foreach ($data as $key => $value) {
                $arrKey = explode("_", $key);
                if($arrKey[0] == 'product'){
                    if(isset($data['qty_'.$arrKey[1]])){
                        $data['arr_product'][$key] = [];
                        $data['arr_product'][$key]['id'] = $value;

                        // {{-- KHUSUS Philiphin --}}
                        if($value == 'other'){
                            $data['arr_product'][$key]['id'] = $data['product_other_'.$arrKey[1]];
                        }
                        // ===========================

                        $data['arr_product'][$key]['qty'] = $data['qty_'.$arrKey[1]];
                    }
                }
            }

            $data['arr_product'] = json_encode($data['arr_product']);

            $data["province"] = $data["province_id"];

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
            }

            // Memasukkan data delivery order ke tabel delivery_orders
            $deliveryOrder = DeliveryOrder::create($data);

            // Memasukkan data referensi ke tabel Reference
            for ($i = 0; $i < count($data["name_ref"]); $i++) {
                $reference = new Reference();
                $reference->name = $data["name_ref"][$i];
                $reference->age = $data["age_ref"][$i];
                $reference->phone = $data["phone_ref"][$i];
                $reference->province = $data["province_ref"][$i];
                $reference->city = $data["city_ref"][$i];
                $reference->deliveryorder_id = $deliveryOrder->id;
                $reference->save();
            }

            DB::commit();

            $phone = preg_replace('/[^A-Za-z0-9\-]/', '', $deliveryOrder['phone']);

            if($phone[0] == 0 || $phone[0] == "0"){
               $phone =  substr($phone, 1);
            }

            $phone = "62" . $phone;
            $code = $deliveryOrder['code'];
            $url = "https://waki-indonesia.co.id/register-success?code=" . $code . "";
            Utils::sendSms(
                $phone,
                "Terima kasih telah melakukan registrasi di WAKi Indonesia. Berikut link detail registrasi anda (" . $url . "). Info lebih lanjut, hubungi 082138864962."
            );

            return redirect()->route('add_submission_form');
        } catch (\Exception $ex) {
            DB::rollback();

            return response()->json([
                'error' => $ex,
            ]);
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        if ($request->has("id")) {
            $deliveryOrders = DeliveryOrder::find($request->get("id"));
            $promos = DeliveryOrder::$Promo;
            $branches = Branch::all();
            $csos = Cso::all();

            return view(
                "admin.update_submission_form",
                compact(
                    "deliveryOrders",
                    "promos",
                    "branches",
                    "csos",
                )
            );
        } else {
            return response()->json(['result' => 'Gagal!!']);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
