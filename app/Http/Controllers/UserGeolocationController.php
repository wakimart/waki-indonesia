<?php

namespace App\Http\Controllers;

use App\Cso;
use App\User;
use App\UserGeolocation;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserGeolocationController extends Controller
{
    public function show(Request $request)
    {
        $csos = Cso::select("id", "code", "name")->where("active", true)->get();

        return view("admin.detail_user_geolocation", compact("csos"));
    }

    public function fetchGeolocationData(Request $request)
    {
        // $user = User::select("id")->where("cso_id", $request->cso_id)->first();
        // $userGeolocation = UserGeolocation::select("cso_id", "date", "filename")
        //     ->where("user_id", $user->id)
        //     ->whereDate("date", "=", $request->date)
        //     ->first();

        // $path = storage_path()
        //     . "/geolocation/"
        //     . $userGeolocation->user_id
        //     . "/"
        //     . $userGeolocation->date
        //     . "/"
        //     . $userGeolocation->filename
        //     . ".json";
        $path = storage_path() . "/geolocation/test/1/geolocation2.json";
        $json = json_decode(file_get_contents($path), true);

        return response()->json($json);
    }

    public function addStartImageApi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "image" => "required|file",
        ]);

        if ($validator->fails()) {
            return response()->json([
                "result" => 0,
                "data" => $validator->errors(),
            ], 400);
        }

        DB::beginTransaction();

        try {
            if ($request->hasFile("image")) {
                $imageFile = $request->file("image");

                $filePath = "sources/geolocation/"
                    . $request->user_id
                    . "/"
                    . date("Y-m-d")
                    . "_start."
                    . $imageFile->getClientOriginalExtension();
                $fileName = time() . "_" . $request->user_id;
                $imageFile->move($filePath, $fileName);

                $presenseImage[] = $fileName;
                $currentDate = date("Y-m-d H:i:s");
                UserGeolocation::create([
                    "presence_image" => $presenseImage,
                    "date" => $currentDate,
                ]);

                DB::commit();

                return response()->json(["result" => 1]);
            }
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                "result" => 0,
                "data" => $e->getMessage(),
            ], 500);
        }
    }

    public function addApi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "image" => "required|file",
            "file" => "required|file|mimetypes:application/json",
        ]);

        if ($validator->fails()) {
            return response()->json([
                "result" => 0,
                "data" => $validator->errors(),
            ], 400);
        }

        DB::beginTransaction();

        try {
            // Save Image
            $endImage = "";
            if ($request->hasFile("image")) {
                $imageFile = $request->file("image");

                $filePath = "sources/geolocation/"
                    . $request->user_id
                    . "/"
                    . date("Y-m-d")
                    . "_end."
                    . $imageFile->getClientOriginalExtension();
                $fileName = time() . "_" . $request->user_id;
                $imageFile->move($filePath, $fileName);

                $endImage = $fileName;
                $currentDate = date("Y-m-d H:i:s");
            }

            // Save JSON
            $currentDate = date("Y-m-d H:i:s");
            $fileName = Str::random(16);
            $filePath = storage_path() . "/geolocation/" . $request->user_id . "/" . $currentDate;
            if (!File::exists($filePath)) {
                File::makeDirectory($filePath);
            }
            $file = $request->file("file");
            $file->move($filePath, $fileName . ".json");

            // Query Geolocation
            $currentDateForQuery = date("Y-m-d");
            $userGeolocation = UserGeolocation::where("user_id", $request->id)
                ->whereBetween(
                    "date",
                    [
                        $currentDateForQuery . " 00:00:00",
                        $currentDateForQuery . " 23:59:59"
                    ]
                )
                ->first();

            // Save To Database
            $presenseImage = $userGeolocation->presense_image;
            $presenseImage[] = $endImage;
            $userGeolocation->presense_image = $presenseImage;
            $userGeolocation->filename = $fileName;
            $userGeolocation->save();

            DB::commit();

            return response()->json(["result" => 1]);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                "result" => 0,
                "data" => $e->getMessage(),
            ], 500);
        }
    }
}
