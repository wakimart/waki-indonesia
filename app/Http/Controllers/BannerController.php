<?php

namespace App\Http\Controllers;

use App\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    /**
     * Menampilkan semua banner list di Admin
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $banners = Banner::all();
        return view('admin.listbanner', compact('banners'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = Banner::all();

        $hasBanner = false;
        
        for ($i=0; $i < 6; $i++) {

            if($request->input('url_banner'.$i) != null && $request->hasfile('arr_image'.$i)){
                // return response()->json(['success' => [$request->input('url_banner'.$i), $request->hasfile('arr_image'.$i)]]);
                if($bannerImg[$i] != []){
                    if(File::exists("sources/banner/". $bannerImg[$i]->img)){
                        File::delete("sources/banner/" . $bannerImg[$i]->img);
                    }
                }

                $bannerImg[$i] = [];
                $hasBanner = true;

                $file = $request->file('arr_image'.$i);
                $filename = $file->getClientOriginalName();
                $image_resize = Image::make($file->getRealPath());
                $image_resize->resize(720, 720);
                $file->move("sources/banner/", $filename);
                $bannerImg[$i]['img'] = $filename;
                $bannerImg[$i]['url'] = $request->input('url_banner'.$i);
            }
            elseif($request->input('url_banner'.$i) != null && !$request->hasfile('arr_image'.$i)){
                // return response()->json(['success' => $request->input('url_banner1')]);
                $bannerImg[$i]->url = $request->input('url_banner'.$i);
            }
            elseif($request->input('url_banner'.$i) == null && !$request->hasfile('arr_image'.$i) && $bannerImg[$i] != []) {
                // return response()->json(['success' => [$i, $request->input('url_banner'.$i), $request->hasfile('arr_image'.$i), $bannerImg[$i]->img]]);
                // if($i==1){
                //     return response()->json(['success' => [$bannerImg[$i], $bannerImg[$i]->img]]);
                // }
                if(File::exists("sources/banner/". $bannerImg[$i]->img)){
                    File::delete("sources/banner/" . $bannerImg[$i]->img);
                    $bannerImg[$i] = [];
                    // return response()->json(['success' => [$bannerImg[$i]]]);
                }
            }            
        }

        $data['banner_index'] = json_encode($bannerImg, true);
        $data['best_seller'] = json_encode($arr_bestAll, true);
        $data->save();

        return response()->json(['success' => "Berhasil!!!"]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function show(Banner $banner)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function edit(Banner $banner)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Banner $banner)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function destroy(Banner $banner)
    {
        //
    }
}
