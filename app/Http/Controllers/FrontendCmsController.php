<?php

namespace App\Http\Controllers;

use App\Banner;
use App\OurGallery;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class FrontendCmsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $banners = Banner::first();

        if ($banners === null) {
            $data = [
                "id" => "1",
                "image" => "[]",
            ];
            $banners = Banner::create($data);
        }

        $galleries = OurGallery::first();
        if ($galleries === null) {
            $data = [
                "id" => "1",
                "photo" => "[]",
                "url_youtube" => "[]",
            ];
            $galleries = OurGallery::create($data);
        }

        return view('admin.frontendcms_new', compact('banners', 'galleries'));
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

    public function storeImageGallery(Request $request)
    {
        DB::beginTransaction();

        try {
            $imageGallery = OurGallery::where("id", 1)->first();
            $imageArray = json_decode($imageGallery->photo, JSON_THROW_ON_ERROR);

            $fileName = $request->file("photo")->getClientOriginalName();
            $request->file("photo")->move("sources/portfolio/", $fileName);
            $imageArray[] = $fileName;

            $imageGallery->photo = json_encode($imageArray, JSON_THROW_ON_ERROR);
            $imageGallery->save();

            DB::commit();

            return redirect()
                ->route("index_frontendcms")
                ->with("success", "Image successfully added.");
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                "error" => $e,
                "error message" => $e->getMessage(),
                "error line" => $e->getLine(),
                "error file" => $e->getFile(),
            ]);
        }
    }

    public function storeVideoGallery(Request $request)
    {
        DB::beginTransaction();

        try {
            $videoGallery = OurGallery::where("id", 1)->first();
            $videoArray = json_decode($videoGallery->url_youtube, JSON_THROW_ON_ERROR);
            $videoArray[] = [
                "title" => $request->title,
                "url" => $request->url,
            ];

            $videoGallery->url_youtube = json_encode($videoArray, JSON_THROW_ON_ERROR);
            $videoGallery->save();

            DB::commit();

            return redirect()
                ->route("index_frontendcms")
                ->with("success", "Video successfully added.");
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                "error" => $e,
                "error message" => $e->getMessage(),
                "error line" => $e->getLine(),
                "error file" => $e->getFile(),
            ]);
        }
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

                    $file = $request->file('images' . $i);
                    $filename = $file->getClientOriginalName();
                    $file->move("sources/banners/", $filename);

                    if(array_key_exists($i, $arr_image_before)){
                        $arr_image_before[$i]->img = $filename;
                        $arr_image_before[$i]->url = $request->input('url_banner'.$i);
                    }else{
                        $json =array(
                            "img" => $filename,
                            "url" => $request->input('url_banner'.$i),
                        );
                        array_push($arr_image_before, $json);
                    }
                } else {
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

                $file = $request->file('photos' . $i);
                $filename = $file->getClientOriginalName();
                $file->move("sources/portfolio/", $filename);

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

        DB::table('our_galleries')
            ->where('id', 1)
            ->update([
                'photo' => json_encode($arr_photo_before),
                'url_youtube' => json_encode($arr_vid_before)
            ]);

        return response()->json(['success' => 'Berhasil!']);
    }

    public function updateImageGallery(Request $request)
    {
        DB::beginTransaction();

        try {
            $imageGallery = OurGallery::where("id", 1)->first();
            dd($request->all());
            $imageArray = json_decode($imageGallery->photo, JSON_THROW_ON_ERROR);

            $oldFile = "sources/portfolio/" . $imageArray[(int)$request->sequence];
            if (File::exists($oldFile)) {
                File::delete($oldFile);
            }

            $fileName = $request->file("photo")->getClientOriginalName();
            $request->file("photo")->move("sources/portfolio/", $fileName);
            $imageArray[(int)$request->sequence] = $fileName;

            $imageGallery->photo = json_encode($imageArray, JSON_THROW_ON_ERROR);
            $imageGallery->save();

            DB::commit();

            return redirect()
                ->route("edit_album", ['id' => 1])
                ->with("success", "Image #" . ((int)$request->sequence + 1) . " successfully updated.");
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                "error" => $e,
                "error message" => $e->getMessage(),
                "error line" => $e->getLine(),
                "error file" => $e->getFile(),
            ]);
        }
    }

    public function updateVideoGallery(Request $request)
    {
        DB::beginTransaction();

        try {
            $videoGallery = OurGallery::where("id", 1)->first();
            $videoArray = json_decode($videoGallery->url_youtube, JSON_THROW_ON_ERROR);
            $videoArray[(int)$request->sequence] = [
                "title" => $request->title,
                "url" => $request->url,
            ];

            $videoGallery->url_youtube = json_encode($videoArray, JSON_THROW_ON_ERROR);
            $videoGallery->save();

            DB::commit();

            return redirect()
                ->route("index_frontendcms")
                ->with("success", "Video #" . ((int)$request->sequence + 1) . " successfully updated.");
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                "error" => $e,
                "error message" => $e->getMessage(),
                "error line" => $e->getLine(),
                "error file" => $e->getFile(),
            ]);
        }
    }

    public function destroyImage(Request $request)
    {
        DB::beginTransaction();

        try {
            $imageGallery = OurGallery::where("id", 1)->first();
            $imageArray = json_decode($imageGallery->photo, JSON_THROW_ON_ERROR);

            $oldFile = "sources/portfolio" . $imageArray[(int)$request->sequence];
            if (File::exists($oldFile)) {
                File::delete($oldFile);
            }

            array_splice($imageArray, (int)$request->sequence, 1);

            $imageGallery->photo = json_encode($imageArray, JSON_THROW_ON_ERROR);
            $imageGallery->save();

            DB::commit();

            return redirect()
                ->route("index_frontendcms")
                ->with("success", "Image #" . ((int)$request->sequence + 1) . " successfully deleted.");
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                "error" => $e,
                "error message" => $e->getMessage(),
                "error line" => $e->getLine(),
                "error file" => $e->getFile(),
            ]);
        }
    }

    public function destroyVideo(Request $request)
    {
        DB::beginTransaction();

        try {
            $videoGallery = OurGallery::where("id", 1)->first();
            $videoArray = json_decode($videoGallery->url_youtube, JSON_THROW_ON_ERROR);

            array_splice($videoArray, (int)$request->sequence, 1);

            $videoGallery->url_youtube = json_encode($videoArray, JSON_THROW_ON_ERROR);
            $videoGallery->save();

            DB::commit();

            return redirect()
                ->route("index_frontendcms")
                ->with("success", "Video #" . ((int)$request->sequence + 1) . " successfully deleted.");
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                "error" => $e,
                "error message" => $e->getMessage(),
                "error line" => $e->getLine(),
                "error file" => $e->getFile(),
            ]);
        }
    }
}
