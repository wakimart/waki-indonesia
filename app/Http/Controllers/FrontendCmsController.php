<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Banner;
use App\OurGallery;
use DB;
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
                        "image" => "[]",
                    );
            $banners = Banner::create($data);
        }

        $galleries = OurGallery::all()->first();
        if($galleries == null){
            $data = array(
                        "id" => "1",
                        "photo" => "[]",
                        "url_youtube" => "[]",
                    );
            $galleries = OurGallery::create($data);
        }

        return view('admin.frontendcms', compact('banners', 'galleries'));
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
        
        //restore image
        $arr_image_before = json_decode($data['image']);
        $namaGambar = [];
        $namaGambar = array_values($arr_image_before);

        if ($request->hasFile('images0') || $request->hasFile('images1') || $request->hasFile('images2') || $request->hasFile('images3') || $request->hasFile('images4') || $request->hasFile('images5')){
            
            //store image

            for ($i = 0; $i < $request->total_images; $i++) {   
                if ($request->hasFile('images' . $i)) {
                    if(array_key_exists($i, $arr_image_before)){
                        if(File::exists("sources/banners/" . $arr_image_before[$i]->img))
                        {
                            File::delete("sources/banners/" . $arr_image_before[$i]->img);
                        }
                    }  
                    //return response()->json(['success' => $request->dlt_img ]);
                    // $path = "public/banners/" . $codePath;
                    // $path = str_replace("\\", "", $path);
                    $file = $request->file('images' . $i);
                    $filename = $file->getClientOriginalName();

                    // $image_resize = Image::make($file->getRealPath());
                    // $image_resize->resize(720, 720);

                    //storing gambar - gambar
                    //$pathForImage = "sources/banners/" . $codePath;
                    $file->move("sources/banners/", $filename);
                    //$image_resize->save(public_path($pathForImage.'/'.$filename));

                    if(array_key_exists($i, $arr_image_before)){
                        $arr_image_before[$i]->img = $filename;
                        $arr_image_before[$i]->url = $request->input('url_banner'.$i);
                        //return response()->json(['test' => "WOY"]);
                    }else{
                        $json =array(
                            "img" => $filename,
                            "url" => $request->input('url_banner'.$i),
                        );
                        array_push($arr_image_before, $json);    
                    }                    
                }

                else{
                    if(array_key_exists($i, $arr_image_before)){
                        $arr_image_before[$i] = $namaGambar[$i];
                    }
                }
            }


            if($request->dlt_img!="")
            {
                $deletes = explode(",", $request->dlt_img);
                foreach ($deletes as $key =>  $value) {
                    if(array_key_exists($value, $namaGambar))
                    {
                        if(File::exists("sources/banners/" . $namaGambar[$value]))
                        {
                            File::delete("sources/banners/" . $namaGambar[$value]);
                        }
                       // return response()->json(['success' => $value ]);
                        //unset($namaGambar[$value]);
                        unset($arr_image_before[$value]);
                    }
                   
                }
            }  
        }

        if($request->dlt_img!="")
        {
            $deletes = explode(",", $request->dlt_img);
            foreach ($deletes as $key =>  $value) {
                if(array_key_exists($value, $namaGambar))
                {
                    if(File::exists("sources/banners/" . $namaGambar[$value]->img))
                    {
                        File::delete("sources/banners/" . $namaGambar[$value]->img);
                    }
                   // return response()->json(['success' => $value ]);
                    unset($arr_image_before[$value]);
                }
               
            }
        }

        $data['image'] = json_encode($arr_image_before);
        $data->save();

        //restore photos
        $galleries = OurGallery::all()[0];
        $arr_photo_before = json_decode($galleries['photo']);
        $namaPhoto = [];
        $namaPhoto = array_values($arr_photo_before);


        for ($i = 0; $i < $request->total_photos; $i++) { 
            if ($request->hasFile('photos' . $i)) {
                if(array_key_exists($i, $arr_photo_before)){
                    if(File::exists("sources/portfolio/" . $arr_photo_before[$i]->img))
                    {
                        File::delete("sources/portfolio/" . $arr_photo_before[$i]->img);
                    }
                }
                //return response()->json(['success' => $request->dlt_img ]);
                // $path = "public/portfolio/" . $codePath;
                // $path = str_replace("\\", "", $path);
                $file = $request->file('photos' . $i);
                $filename = $file->getClientOriginalName();

                // $image_resize = Image::make($file->getRealPath());
                // $image_resize->resize(720, 720);

                //storing gambar - gambar
                //$pathForImage = "sources/portfolio/" . $codePath;
                $file->move("sources/portfolio/", $filename);
                //$image_resize->save(public_path($pathForImage.'/'.$filename));

                if(array_key_exists($i, $arr_photo_before)){
                    $arr_photo_before[$i] = $namaPhoto[$i];
                }else{
                    array_push($arr_photo_before, $filename);    
                }
            }

            else{
                if(array_key_exists($i, $arr_photo_before)){
                    $arr_photo_before[$i] = $namaPhoto[$i];
                }
            }
        }

        if($request->dlt_photos!="")
        {
            $deletes = explode(",", $request->dlt_photos);
            foreach ($deletes as $value) {
                if(array_key_exists($value, $namaPhoto))
                {
                    if(File::exists("sources/portfolio/" . $namaPhoto[$value]))
                    {
                        File::delete("sources/portfolio/" . $namaPhoto[$value]);
                    }
                   // return response()->json(['success' => $value ]);
                    unset($namaPhoto[$value]);
                }
               
            }
        }

        $arr_vid_before = json_decode($galleries['url_youtube']);
        $namaVideo = [];
        $namaVideo = array_push($namaVideo, $arr_vid_before);

        for ($v=0; $v < $request->total_videos; $v++) {

            if($request->input('video_' . $v) != null){
                
                if(array_key_exists($v, $arr_vid_before)){
                    $arr_vid_before[$v]->title = $request->input('title_'.$v);
                    $arr_vid_before[$v]->url = $request->input('video_'.$v);
                }else{
                    $json_vid =array(
                        "title" => $request->input('title_'.$v),
                        "url" => $request->input('video_'.$v),
                    );
                    array_push($arr_vid_before, $json_vid);    
                }
            }
        }        
        //return response()->json(['test' => json_encode($arr_vid_before)]);

        DB::table('our_galleries')
            ->where('id', 1)
            ->update([
                'photo' => json_encode($arr_photo_before),
                'url_youtube' => json_encode($arr_vid_before)
            ]);
        
        return response()->json(['success' => 'Berhasil!']);        
        
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
