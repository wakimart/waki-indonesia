<?php

namespace App\Http\Controllers;

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
        $userGeolocations = "";
        if ($request->has("date")) {
            $userGeolocations = UserGeolocation::select(
                    "user_geolocations.user_id AS user_id",
                    "csos.code AS code",
                    "csos.name AS name",
                )
                ->whereBetween(
                    "user_geolocations.date",
                    [
                        $request->date . " 00:00:00",
                        $request->date . " 23:59:59"
                    ]
                )
                ->leftJoin(
                    "users",
                    "user_geolocations.user_id",
                    "=",
                    "users.id"
                )
                ->leftJoin(
                    "csos",
                    "users.cso_id",
                    "=",
                    "csos.id"
                )
                ->get();
        }

        return view(
            "admin.detail_user_geolocation",
            compact("userGeolocations")
        );
    }

    public function fetchGeolocationData(Request $request)
    {
        $userGeolocation = UserGeolocation::select("user_id", "date", "filename")
            ->where("user_id", $request->user_id)
            ->whereDate("date", "=", $request->date)
            ->first();

        $path = "sources/geolocation/" . date("Y-m-d") . "/json/"
            . $userGeolocation->filename
            . ".json";

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

                $filePath = "sources/geolocation/" . date("Y-m-d") . "/img/";

                if (!is_dir($filePath)) {
                    File::makeDirectory($filePath, 0777, true, true);
                }

                $fileName = $request->user_id
                    . "_"
                    . time()
                    . "_start."
                    . $imageFile->getClientOriginalExtension();
                $imageFile->move($filePath, $fileName);

                $presenseImage[] = $fileName;
                $currentDate = date("Y-m-d");
                UserGeolocation::create([
                    "user_id" => $request->user_id,
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
            "file" => "required|file",
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

                $filePath = "sources/geolocation/" . date("Y-m-d") . "/img/";

                if (!is_dir($filePath)) {
                    File::makeDirectory($filePath, 0777, true, true);
                }

                $fileName = $request->user_id
                    . "_"
                    . time()
                    . "_end."
                    . $imageFile->getClientOriginalExtension();
                $imageFile->move($filePath, $fileName);

                $endImage = $fileName;
            }

            // Save JSON
            $fileName = Str::random(16);
            $filePath = "sources/geolocation/" . date("Y-m-d") . "/json/";
            if (!File::exists($filePath)) {
                File::makeDirectory($filePath, 0777, true, true);
            }
            $file = $request->file("file");
            $file->move($filePath, $fileName . ".json");

            // Query Geolocation
            $currentDateForQuery = date("Y-m-d");
            $userGeolocation = UserGeolocation::where("user_id", $request->user_id)
                ->whereBetween(
                    "date",
                    [
                        $currentDateForQuery . " 00:00:00",
                        $currentDateForQuery . " 23:59:59"
                    ]
                )
                ->first();

            // Save To Database
            $presenseImage = $userGeolocation->presence_image;
            array_push($presenseImage, $endImage);
            $userGeolocation->presence_image = $presenseImage;
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

    public function fetchPresenceImage(Request $request)
    {
        try {
            $userGeolocation = UserGeolocation::select("presence_image")
                ->where("user_id", $request->user_id)
                ->whereDate("date", $request->date)
                ->first();

            return response()->json([
                "data" => $userGeolocation->presence_image,
            ]);
        } catch (Exception $e) {
            return response()->json([
                "result" => 0,
                "data" => $e->getMessage(),
            ], 500);
        }
    }

    public function fetchStatusPresence(Request $request)
    {
        try {
            $userGeolocation = UserGeolocation::select("presence_image")
                ->where("user_id", $request->user_id)
                ->whereDate("date", $request->date)
                ->first();

            if (!empty($userGeolocation->presence_image)) {
                return response()->json([
                    "result" => 0,
                    "data" => "Anda sudah absen pada hari ini.",
                ]);
            } else {
                return response()->json([
                    "result" => 1,
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                "result" => 0,
                "data" => $e->getMessage(),
            ], 500);
        }
    }
}
