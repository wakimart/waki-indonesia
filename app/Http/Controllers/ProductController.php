<?php

namespace App\Http\Controllers;

use App\Product;
use App\CategoryProduct;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $product = Product::find($id);
        $categoryProducts = CategoryProduct::with('product')->get();
        return view('single_product', compact('product', 'categoryProducts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = CategoryProduct::all();
        return view('admin.add_product', compact('categories'));
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

        //arr_image
        $namaGambar = [];
        $codePath = strtolower($request->input('code'));
        for ($i = 0; $i < 3; $i++) {

            if ($request->hasFile('images' . $i)) {
                $path = "public/product_images/" . $codePath;
                $path = str_replace("\\", "", $path);
                $file = $request->file('images' . $i);
                $filename = $file->getClientOriginalName();

                // $image_resize = Image::make($file->getRealPath());
                // $image_resize->resize(720, 720);

                //cek ada folder tidak
                if (!is_dir("sources/product_images/" . $codePath)) {
                    File::makeDirectory("sources/product_images/" . $codePath, $mode = 0777, true, true);
                }

                //storing gambar - gambar
                $pathForImage = "sources/product_images/" . $codePath;
                $file->move($pathForImage, $filename);
                //$image_resize->save(public_path($pathForImage.'/'.$filename));
                $namaGambar[$i] = $filename;
            }
        }
        $data['image'] = json_encode($namaGambar);
        $product = Product::create($data);
        return response()->json(['success' => 'Berhasil']);  
    }

    public function admin_ListProduct(){
        $products = Product::all();
        return view('admin.list_product', compact('products'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        if($request->has('id')){
            $products = Product::find($request->get('id'));
            $categories = CategoryProduct::all();
            return view('admin.update_product', compact('products','categories'));
        }else{
            return response()->json(['result' => 'Gagal!!']);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $products = Product::find($request->input('idProduct'));
        $products->name = $request->input('name');
        $products->price = $request->input('price');
        $products->video = $request->input('video');
        $products->category_id = $request->input('category_id');
        $products->description = $request->description;
        $products->quick_desc = $request->quick_description;


        //restore image
        $codePath = strtolower($request->input('code'));
        $arr_image_before = json_decode($products->image, true);
        $namaGambar = [];
        $namaGambar = array_values($arr_image_before);


        if ($request->hasFile('images0') || $request->hasFile('images1') || $request->hasFile('images2')){
            
            //store image

            for ($i = 0; $i < $request->total_images; $i++) {
               
                if ($request->hasFile('images' . $i)) {
                    if(array_key_exists($i, $arr_image_before)){
                        if(File::exists("sources/product_images/" . $codePath . "/" . $arr_image_before[$i]))
                        {
                            File::delete("sources/product_images/" . $codePath . "/" . $arr_image_before[$i]);
                        }
                    }  
                    //return response()->json(['success' => $request->dlt_img ]);
                    $path = "public/product_images/" . $codePath;
                    $path = str_replace("\\", "", $path);
                    $file = $request->file('images' . $i);
                    $filename = $file->getClientOriginalName();

                    // $image_resize = Image::make($file->getRealPath());
                    // $image_resize->resize(720, 720);

                    //storing gambar - gambar
                    $pathForImage = "sources/product_images/" . $codePath;
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

        // return response()->json(['success' => $request->dlt_img ]);
        if($request->dlt_img!="")
        {
            $deletes = explode(",", $request->dlt_img);
            foreach ($deletes as $value) {
                if(array_key_exists($value, $namaGambar))
                {
                    if(File::exists("sources/product_images/" . $codePath . "/" . $namaGambar[$value]))
                    {
                        File::delete("sources/product_images/" . $codePath . "/" . $namaGambar[$value]);
                    }
                   // return response()->json(['success' => $value ]);
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
        $products->image = $namaGambarFix;
        $products->save();
        return response()->json(['testing' => $products]);


        // return response()->json(['success' => 'Berhasil']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}
