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
                        "image" => "[]",
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
