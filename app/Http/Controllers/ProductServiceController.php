<?php

namespace App\Http\Controllers;

use App\ProductService;
use App\Product;
use App\Sparepart;
use Illuminate\Http\Request;

class ProductServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $product_services = ProductService::all();
        $countServices = $product_services->count();
        return view('admin.list_taskservice', compact('product_services', 'countServices'));
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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ProductService  $productService
     * @return \Illuminate\Http\Response
     */
    public function show(ProductService $productService)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ProductService  $productService
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {   
        if($request->has('id')){
            $product_services = ProductService::find($request->get('id'));
            $products = Product::all();
            $spareparts = Sparepart::where('active', true)->get();
            return view('admin.update_productservice', compact('product_services','products', 'spareparts'));    
        }
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ProductService  $productService
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductService $productService)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ProductService  $productService
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductService $productService)
    {
        //
    }
}
