<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Banner;
use App\OurGallery;
use App\CategoryProduct;
use App\Album;
use App\Product;
use App\Version;
use Illuminate\Support\Facades\Mail;

class IndexController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $banners = Banner::all();
        $galleries = OurGallery::all();
        $categoryProducts = CategoryProduct::all();
        $albums = Album::where('active', true)->get();
        return view('index', compact('banners', 'galleries', 'categoryProducts', 'albums'));
    }


    public function fetchProductByCategoryProductId(Request $request){
        $cso = Product::Where('category_id', $request->categoryId)->first();
        return response()->json($cso);
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
    
    public function termNCondition()
    {
        $vartest = "TESTING";
        return view('term_cond', compact('vartest'));
    }

    public function storeVersion(Request $request){
        $data = $request->all();
        $version = Version::create($data);

        $result = ['result'=> 1,
                   'data' => $version
        ];
        return response()->json($result, 200);
    }

    public function listVersion(){
        $version = Version::orderBy('id', 'desc')->first();
        $result = ['result'=> 1,
                   'data' => $version
        ];
        return response()->json($result, 200);
    }

    public function sendContactForm(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|min:4',
            'email' => 'required',
            'subject' => 'required',
            'message' => 'required',
        ]);

        $content="From: ".$request->name." \nEmail: <".$request->email."> \nMessage: ".$request->message;
        $recipient = env("MAIL_CONTACTUS");
        // $mailheader = "From: <".$request->email."> \r\n";
        
        Mail::send([], [], function ($message) use ($recipient, $request, $content) {
            $message->to($recipient)
              ->from($request->email)
              ->subject($request->subject)
              ->setBody($content); // assuming text/plain
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Mail successfully send.'
        ], 200);
    }
}
