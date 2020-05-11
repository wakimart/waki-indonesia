<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Banner;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class FrontendCmsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $banners = Banner::all()->first();
        //dd($banners);

        if($banners == null){
            $data = array(
                        "id" => "1",
                        "image" => "[[],[],[],[],[],[]]",
                    );
            $banners = Banner::create($data);
        }
        return view('admin.frontendcms', compact('banners'));
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
    public function update(Request $request)
    {
        $data = Banner::all()[0];
        $bannerImg = json_decode($data['image']);
        $hasBanner = false;


        for ($i=0; $i < $request->total_images; $i++) {
            if($request->input('url_banner'.$i) != null && $request->hasfile('arr_image'.$i)){
                // return response()->json(['success' => [$request->input('url_banner'.$i), $request->hasfile('arr_image'.$i)]]);
                if($bannerImg[$i] != []){
                    if(File::exists("sources/banners/". $bannerImg[$i]->img)){
                        
                        File::delete("sources/banners/" . $bannerImg[$i]->img);
                    }
                }

                $bannerImg[$i] = [];
                $hasBanner = true;


                $file = $request->file('arr_image'.$i);
                $filename = $file->getClientOriginalName();
                // $image_resize = Image::make($file->getRealPath());
                // $image_resize->resize(720, 720);
                $file->move("sources/banners/", $filename);
                $bannerImg[$i]['img'] = $filename;
                $bannerImg[$i]['url'] = $request->input('url_banner'.$i);
            }
            elseif($request->input('url_banner'.$i) != null && !$request->hasfile('arr_image'.$i)){
                // return response()->json(['success' => $request->input('url_banner1')]);
                //return response()->json(['testing' => "hey4"]);
                $bannerImg[$i]->url = $request->input('url_banner'.$i);
            }
            elseif($request->input('url_banner'.$i) == null && !$request->hasfile('arr_image'.$i) && $bannerImg[$i] != []) {
                // return response()->json(['success' => [$i, $request->input('url_banner'.$i), $request->hasfile('arr_image'.$i), $bannerImg[$i]->img]]);
                // if($i==1){
                //     return response()->json(['success' => [$bannerImg[$i], $bannerImg[$i]->img]]);
                // }
                
                if(File::exists("sources/banners/". $bannerImg[$i]->img)){
                    File::delete("sources/banners/" . $bannerImg[$i]->img);
                    $bannerImg[$i] = [];
                    // return response()->json(['success' => [$bannerImg[$i]]]);
                }
            }            
        }
        return response()->json(['testing' => $bannerImg]);
        $data['image'] = json_encode($bannerImg);
        //$data->save();
        return response()->json(['success' => "Berhasil!!!"]);
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
