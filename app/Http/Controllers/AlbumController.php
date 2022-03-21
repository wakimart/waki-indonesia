<?php

namespace App\Http\Controllers;

use App\Album;
use App\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
USE DB;

class AlbumController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $events = Event::where('active', true)->get();
        $albums = Album::all();
        return view('admin.add_album', compact('events', 'albums'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->all();
            if($request->hasFile('arr_photo')){
                $data['arr_photo'] = [];

                $event = Event::find($data['event_id']);
                $string = str_replace(' ', '', $event['name']);
                $codePath = strtolower($string);
                $path = "sources/album/" . $codePath;

                $idxImg = 1;

                if (!is_dir($path)) {
                    File::makeDirectory($path, 0777, true, true);
                }

                foreach ($request->file("arr_photo") as $imgNya) {
                    $fileName = $string . "_" . str_replace([' ', ':'], '', time()). $idxImg . "." . $imgNya->getClientOriginalExtension();

                    $imgNya->move($path, $fileName);
                    array_push($data['arr_photo'], $fileName);
                    $idxImg++;
                }
            }

            $index = 0;
            $data['url_video'] = [];
            for ($i=0; $i < 3; $i++) {
                $title = $request->get('title_' . $i);
                $url = $request->get('url_' . $i);

                if($title != null && $url != null){
                    $data['url_video'][$index] = [];
                    $data['url_video'][$index]['title'] = $title;
                    $data['url_video'][$index]['url'] = $url;

                    $index++;
                }
            }

            $album = Album::create($data);

            DB::commit();
            return redirect()
            ->route("add_album")
            ->with("success", "Album successfully added.");
        } catch (\Exception $ex) {
            DB::rollBack();

            return response()->json([
                "error" => $ex,
                "error message" => $ex->getMessage(),
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function show(Album $album)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        if($request->has('id')){
            $albums = Album::find($request->get('id'));
            $events = Event::where('active', true)->get();

            return view('admin.update_album', compact('albums', 'events'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function updateImageGallery(Request $request)
    {
        DB::beginTransaction();

        try {
            $imageGallery = Album::where('event_id', $request->get('event_id'))->first();
            $imageArray = $imageGallery->arr_photo;

            $oldFile = "sources/portfolio/" . $imageArray[(int)$request->sequence];
            if (File::exists($oldFile)) {
                File::delete($oldFile);
            }

            $string = str_replace(' ', '', $imageGallery->event['name']);
            $fileName = $string . "_" . str_replace([' ', ':'], '', time()). ((int)$request->sequence+1) . "." . $request->file("photo")->getClientOriginalExtension();
            $request->file("photo")->move("sources/album/" . strtolower($string), $fileName);
            $imageArray[(int)$request->sequence] = $fileName;

            $imageGallery->arr_photo = $imageArray;
            $imageGallery->save();

            DB::commit();

            return redirect()
                ->route("edit_album", ['id' => $request->get('event_id')])
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


    public function updateVideoGallery(Request $request){
        DB::beginTransaction();

        try {
            $videoGallery = Album::where('event_id', $request->get('event_id'))->first();
            $videoArray = $videoGallery->url_video;
            $sequence = (int)$request->sequence;

            if(isset($videoArray[$sequence])){

            }
            else{
                $data = [];
                $data['title'] = $request->get('title');
                $data['url'] = $request->get('url');
                array_push($videoArray, $data);
                $videoGallery->url_video = $videoArray;
                $videoGallery->save();
            }
            

            DB::commit();

            return redirect()
                ->route("edit_album", ['id' => $request->get('event_id')])
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function destroy(Album $album)
    {
        //
    }
}
