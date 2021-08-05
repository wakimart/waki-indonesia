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
        $branches = "";
        if ($request->filled("date")) {
            $branches = UserGeolocation::select(
                    "branches.id AS id",
                    "branches.code AS code",
                    "branches.name AS name",
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
                ->leftJoin(
                    "branches",
                    "csos.branch_id",
                    "=",
                    "branches.id"
                )
                ->whereDate("user_geolocations.date", $request->date)
                ->where("csos.active", true)
                ->where("branches.active", true)
                ->groupBy("branches.id", "branches.code", "branches.name")
                ->get();
        }

        $userGeolocations = "";
        if ($request->filled("branch_id")) {
            $userGeolocations = UserGeolocation::select(
                    "users.id AS user_id",
                    "csos.code AS cso_code",
                    "csos.name AS name",
                    "user_geolocations.presence_image AS presence_image",
                    "user_geolocations.filename AS filename",
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
                ->leftJoin(
                    "branches",
                    "csos.branch_id",
                    "=",
                    "branches.id"
                )
                ->whereNotNull("user_geolocations.filename")
                ->whereDate("user_geolocations.date", $request->date)
                ->where("csos.active", true)
                ->where("branches.active", true)
                ->where("branches.id", $request->branch_id)
                ->get();
        }

        return view(
            "admin.detail_user_geolocation",
            compact(
                "branches",
                "userGeolocations",
            )
        );
    }

    public function fetchGeolocationData(Request $request)
    {
        try {
            $userGeolocation = UserGeolocation::select(
                    "user_id",
                    "date",
                    "filename",
                )
                ->where("user_id", $request->user_id)
                ->whereDate("date", "=", $request->date)
                ->first();

            $dateNya = date_create($request->date);

            $path = "sources/geolocation/"
                . date_format($dateNya, "Y-m-d")
                . "/json/"
                . $userGeolocation->filename
                . ".json";

            $json = json_decode(file_get_contents($path), true);

            return response()->json($json);
        } catch (Exception $e) {
            return response()->json([
                "result" => 0,
                "data" => $e->getMessage(),
            ], 500);
        }
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
            // Check absen
            $CheckAbsent = UserGeolocation::where("user_id", $request->user_id)
                ->whereDate("date", date("Y-m-d"))
                ->first();

            if (!empty($CheckAbsent)) {
                return response()->json([
                    "result" => 0,
                    "data" => "Anda Sudah Absen Pada Hari Ini WOIII!",
                ], 500);
            }

            $CheckAbsent = UserGeolocation::where("device_id", $request->device_id)
                ->whereDate("date", date("Y-m-d"))
                ->first();

            if (!empty($CheckAbsent)) {
                return response()->json([
                    "result" => 0,
                    "data" => "1 Handphone Hanya Diperbolehkan 1 Absen!",
                ], 500);
            }

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
                    "device_id" => $request->device_id,
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

    public function addApi_2(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "image" => "required|file",
            "geo_file" => "required",
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
            File::put($filePath.$fileName.".json", $request->geo_file);


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
            $userGeolocation = UserGeolocation::where("user_id", $request->user_id)
                ->whereDate("date", date("Y-m-d"))
                ->first();

            if (empty($userGeolocation)) {
                return response()->json([
                    "result" => 1,
                    "status" => "check_in",
                ]);
            } else {
                if (empty($userGeolocation->filename)) {
                    return response()->json([
                        "result" => 1,
                        "status" => "check_out",
                    ]);
                } else {
                     return response()->json([
                        "result" => 0,
                        "status" => "check_in",
                        "data" => "Anda sudah absen pada hari ini.",
                    ]);
                }
            }
        } catch (Exception $e) {
            return response()->json([
                "result" => 0,
                "data" => $e->getMessage(),
            ], 500);
        }
    }
}
