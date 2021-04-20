<?php

namespace App\Http\Controllers;

use App\CategoryProduct;
use App\Product;
use Illuminate\Http\Request;

class CategoryProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $categoryProducts = CategoryProduct::with('product')->get(); //->where('id','=', '2')
        $product = null;
        if($id != null){
            $product = Product::where([['category_id', '=', $id], ['active', true]])->get();
            // dd($product);
        }
        return view('product_category', compact('categoryProducts','product'));
    }

    public function admin_ListCategoryProduct(){
        $categories = CategoryProduct::all();
        return view('admin.list_categoryproduct', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.add_categoryproduct');
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
        $category = CategoryProduct::create($data);
        return response()->json(['success' => 'Berhasil']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CategoryProduct  $categoryProduct
     * @return \Illuminate\Http\Response
     */
    public function show(CategoryProduct $categoryProduct)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CategoryProduct  $categoryProduct
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        if($request->has('id')){
            $categories = CategoryProduct::find($request->get('id'));
            return view('admin.update_categoryproduct', compact('categories'));
        }else{
            return response()->json(['result' => 'Gagal!!']);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CategoryProduct  $categoryProduct
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $categories = CategoryProduct::find($request->input('idCategory'));
        $categories->name = $request->input('name');
        $categories->save();

        return response()->json(['success' => 'Berhasil']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CategoryProduct  $categoryProduct
     * @return \Illuminate\Http\Response
     */
    public function destroy(CategoryProduct $categoryProduct)
    {
        //
    }
}
