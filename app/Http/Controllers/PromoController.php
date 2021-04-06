<?php

namespace App\Http\Controllers;

use App\Promo;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\File;

class PromoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $promos = Promo::all();
        $products = Product::all();
        return view('admin.list_promo', compact('promos', 'products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $products = Product::all();
        return view('admin.add_promo', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $data['code'] = strtoupper($request->input('code'));

        //pembentukan array product
        $index = 0;
        $data['arr_product'] = [];
        foreach ($data as $key => $value) {
            $arrKey = explode("_", $key);
            if($arrKey[0] == 'product'){
                if(isset($data['qty_'.$arrKey[1]])){
                    $data['arr_product'][$index] = [];
                    $data['arr_product'][$index]['id'] = $value;
                    $data['arr_product'][$index]['qty'] = $data['qty_'.$arrKey[1]];
                    $index++;
                }
            }
        }
        $data['product'] = json_encode($data['arr_product']);

        //arr_image
        /* $namaGambar = [];
        $codePath = strtolower($request->input('code'));

        for ($i = 0; $i < 3; $i++) {
            if ($request->hasFile('images' . $i)) {
                $path = "public/promo_images/" . $codePath;
                $path = str_replace("\\", "", $path);
                $file = $request->file('images' . $i);
                $filename = $file->getClientOriginalName();

                // $image_resize = Image::make($file->getRealPath());
                // $image_resize->resize(720, 720);

                //cek ada folder tidak
                if (!is_dir("sources/promo_images/" . $codePath)) {
                    File::makeDirectory("sources/promo_images/" . $codePath, $mode = 0777, true, true);
                }

                //storing gambar - gambar
                $pathForImage = "sources/promo_images/" . $codePath;
                $file->move($pathForImage, $filename);
                //$image_resize->save(public_path($pathForImage.'/'.$filename));
                $namaGambar[$i] = $filename;
            }
        }

        $data['image'] = json_encode($namaGambar); */

        Promo::create($data);

        return response()->json(["success" => "Promo berhasil dibuat."]);
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
    public function edit(Request $request)
    {
        if($request->has('id')){
            $promos = Promo::find($request->get('id'));
            $products = Product::all();
            return view('admin.update_promo', compact('promos','products'));
        }else{
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
    public function update(Request $request)
    {
        $data = $request->all();
        $promos = Promo::find($request->input('idPromo'));

        //pembentukan array product
        $index = 0;
        $data['arr_product'] = [];
        foreach ($data as $key => $value) {
            $arrKey = explode("_", $key);
            if($arrKey[0] == 'product'){
                if(isset($data['qty_'.$arrKey[1]])){
                    $data['arr_product'][$index] = [];
                    $data['arr_product'][$index]['id'] = $value;
                    $data['arr_product'][$index]['qty'] = $data['qty_'.$arrKey[1]];
                    $index++;
                }
            }
        }
        $promos->product = json_encode($data['arr_product']);

        //restore image
        /* $codePath = strtolower($request->input('code'));
        $arr_image_before = json_decode($promos->image, true);
        $namaGambar = [];
        $namaGambar = array_values($arr_image_before);

        if ($request->hasFile('images0') || $request->hasFile('images1') || $request->hasFile('images2')){

            //store image
            $codePath = strtolower($request->input('code'));

            for ($i = 0; $i < $request->total_images; $i++) {

                if ($request->hasFile('images' . $i)) {
                    if(array_key_exists($i, $arr_image_before)){
                        if(File::exists("sources/promo_images/" . $codePath . "/" . $arr_image_before[$i]))
                        {
                            File::delete("sources/promo_images/" . $codePath . "/" . $arr_image_before[$i]);
                        }
                    }
                    //return response()->json(['success' => $request->dlt_img ]);
                    $path = "public/promo_images/" . $codePath;
                    $path = str_replace("\\", "", $path);
                    $file = $request->file('images' . $i);
                    $filename = $file->getClientOriginalName();

                    // $image_resize = Image::make($file->getRealPath());
                    // $image_resize->resize(720, 720);

                    //storing gambar - gambar
                    $pathForImage = "sources/promo_images/" . $codePath;
                    $file->move($pathForImage, $filename);
                    //$image_resize->save(public_path($pathForImage.'/'.$filename));
                    $namaGambar[$i] = $filename;
                }
                else{
                    if(array_key_exists($i, $arr_image_before)){
                        $namaGambar[$i] = $arr_image_before[$i];
                    }
                }
            }
        }

        if ($request->dlt_img != "") {
            $deletes = explode(",", $request->dlt_img);

            foreach ($deletes as $value) {
                if (array_key_exists($value, $namaGambar)) {
                    if (File::exists("sources/promo_images/" . $codePath . "/" . $namaGambar[$value])) {
                        File::delete("sources/promo_images/" . $codePath . "/" . $namaGambar[$value]);
                    }

                    unset($namaGambar[$value]);
                }

            }
        }

        $namaGambarFix="[";

        for($key=0; $key<3; $key++) {
            if($key==2)
            {
                if(array_key_exists($key, $namaGambar)){
                    $namaGambarFix.='"'.$namaGambar[$key].'"';
                }
                else{
                    $namaGambarFix.='""';
                }
            }else{
                if(array_key_exists($key, $namaGambar)){
                    $namaGambarFix.='"'.$namaGambar[$key].'",';
                }
                else{
                    $namaGambarFix.='"",';
                }
            }

        }
        $namaGambarFix.="]";
        $promos->image = $namaGambarFix; */
        $promos->price = $request->input('price');
        $promos->save();

        return response()->json(['result' => 'Berhasil!']);
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

    public function fetchPromoDropdown()
    {
        $promos = Promo::select(
            "id",
            "code",
            "product",
            DB::raw("FLOOR(price) AS price"),
        )
        ->get();
        foreach ($promos as $key => $promo) {
            $productPromo = json_decode($promo["product"]);
            $arrayProductId = [];

            foreach ($productPromo as $pp) {
                $arrayProductId[] = $pp->id;
            }

            $getProduct = Product::select("code")
            ->whereIn("id", $arrayProductId)
            ->get();

            $arrayProductCode = [];

            foreach ($getProduct as $product) {
                $arrayProductCode[] = $product->code;
            }

            $productCode = implode(", ", $arrayProductCode);

            $promos[$key]->product = $promo["code"]
                . " - ("
                . $productCode
                . ") - Rp. "
                . number_format((int) $promo["price"], 0, null, ",");

            unset($promos[$key]->code, $promos[$key]->price);
        }

        $data = [
            "result" => 1,
            "data" => $promos,
        ];

        return response()->json($data, 200);
    }
}
