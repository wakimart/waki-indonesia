<?php

namespace App\Http\Controllers;

use App\CategoryProduct;
use App\HistoryUpdate;
use App\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

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
        $categoryProducts = CategoryProduct::all();
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

        // arr_image
        $namaGambar = [];
        $codePath = strtolower($request->input('code'));

        for ($i = 0; $i < 3; $i++) {
            if ($request->hasFile('images' . $i)) {
                $path = "public/product_images/" . $codePath;
                $path = str_replace("\\", "", $path);
                $file = $request->file('images' . $i);
                $filename = $file->getClientOriginalName();

                // Cek ada folder tidak
                if (!is_dir("sources/product_images/" . $codePath)) {
                    File::makeDirectory("sources/product_images/" . $codePath, 0777, true, true);
                }

                // Storing gambar-gambar
                $pathForImage = "sources/product_images/" . $codePath;
                $file->move($pathForImage, $filename);
                $namaGambar[$i] = $filename;
            }
        }

        $data['image'] = json_encode($namaGambar);
        Product::create($data);

        return response()->json(['success' => 'Berhasil']);
    }

    public function admin_ListProduct(Request $request)
    {
        $url = $request->all();

        $products = Product::where('active', true)->get();

        if($request->has('search')){
            $products = Product::where( 'name', 'LIKE', '%'.$request->search.'%' )
                                    ->orWhere( 'code', 'LIKE', '%'.$request->search.'%' );
        }

        $countProduct = $products->count();
        $products = $products->paginate(10);

        return view(
            "admin.list_product",
            compact(
                "countProduct",
                "products",
                "url"
            )
        )
        ->with("i", (request()->input("page", 1) - 1) * 10 + 1);;
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        if ($request->has('id')) {
            $products = Product::find($request->get('id'));
            $categories = CategoryProduct::all();
            return view('admin.update_product', compact('products','categories'));
        } else {
            return response()->json(['result' => 'Gagal!!']);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $products = Product::find($request->input('idProduct'));
        $products->active = $request->input("active");
        $products->name = $request->input('name');
        $products->price = $request->input('price');
        $products->video = $request->input('video');
        $products->category_id = $request->input('category_id');
        $products->description = $request->description;
        $products->quick_desc = $request->quick_description;

        // Restore image
        $codePath = strtolower($request->input('code'));
        $arr_image_before = json_decode($products->image, true);
        $namaGambar = [];
        $namaGambar = array_values($arr_image_before);

        if ($request->hasFile('images0') || $request->hasFile('images1') || $request->hasFile('images2')){
            // Store image
            for ($i = 0; $i < $request->total_images; $i++) {
                if ($request->hasFile('images' . $i)) {
                    if (array_key_exists($i, $arr_image_before)) {
                        if (File::exists("sources/product_images/" . $codePath . "/" . $arr_image_before[$i])) {
                            File::delete("sources/product_images/" . $codePath . "/" . $arr_image_before[$i]);
                        }
                    }

                    $path = "public/product_images/" . $codePath;
                    $path = str_replace("\\", "", $path);
                    $file = $request->file('images' . $i);
                    $filename = $file->getClientOriginalName();

                    // Storing gambar-gambar
                    $pathForImage = "sources/product_images/" . $codePath;
                    $file->move($pathForImage, $filename);
                    $namaGambar[$i] = $filename;
                } else {
                    if (array_key_exists($i, $arr_image_before)) {
                        $namaGambar[$i] = $arr_image_before[$i];
                    }
                }
            }
        }

        if ($request->dlt_img != "") {
            $deletes = explode(",", $request->dlt_img);
            foreach ($deletes as $value) {
                if (array_key_exists($value, $namaGambar)) {
                    if (File::exists("sources/product_images/" . $codePath . "/" . $namaGambar[$value])) {
                        File::delete("sources/product_images/" . $codePath . "/" . $namaGambar[$value]);
                    }
                    unset($namaGambar[$value]);
                }
            }
        }

        $namaGambarFix = "[";

        for ($key = 0; $key < 3; $key++) {
            if ($key == 2) {
                if (array_key_exists($key, $namaGambar)) {
                    $namaGambarFix .= '"' . $namaGambar[$key] . '"';
                } else {
                    $namaGambarFix .= '""';
                }
            } else {
                if (array_key_exists($key, $namaGambar)) {
                    $namaGambarFix .= '"' . $namaGambar[$key] . '",';
                } else {
                    $namaGambarFix .= '"",';
                }
            }
        }

        $namaGambarFix .= "]";
        $products->image = $namaGambarFix;
        $products->save();
        return response()->json(['testing' => $products]);
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

        if (!empty($request->id)) {
            try {
                $product = Product::where("id", $request->id)->first();
                $product->delete();

                $user = Auth::user();
                $historyDeleteProduct["type_menu"] = "Product";
                $historyDeleteProduct["method"] = "Delete";
                $historyDeleteProduct["meta"] = json_encode(
                    [
                        "user" => $user["id"],
                        "createdAt" => date("Y-m-d H:i:s"),
                        "dataChange" => $product->getChanges(),
                    ],
                    JSON_THROW_ON_ERROR
                );

                $historyDeleteProduct["user_id"] = $user["id"];
                $historyDeleteProduct["menu_id"] = $request->id;
                HistoryUpdate::create($historyDeleteProduct);

                DB::commit();

                return redirect()
                    ->route("list_product")
                    ->with("success", "Data berhasil dihapus!");
            } catch (Exception $e) {
                DB::rollback();

                return response()->json([
                    "error" => $e,
                ], 500);
            }
        }

        return response()->json(["result" => "Data tidak ditemukan."], 400);
    }
}
