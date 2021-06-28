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
        $path = storage_path() . "/geolocation/test/1/geolocation.json";
        $json = json_decode(file_get_contents($path), true);

        return response()->json($json);
    }

    public function addApi(Request $request)
    {
        $validator = Validator::make($request->all(), [
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
            $currentDate = date("Y-m-d H:i:s");
            $fileName = Str::random(16);
            $filePath = storage_path() . "/geolocation/" . $request->user_id . "/" . $currentDate;
            if (!File::exists($filePath)) {
                File::makeDirectory($filePath);
            }

            $file = $request->file("file");
            $file->move($filePath, $fileName . ".json");

            UserGeolocation::create([
                "user_id" => $request->user_id,
                "date" => $currentDate,
                "filename" => $fileName,
            ]);

            DB::commit();

            $this->filter($filePath . "/" . $fileName, $request->user_id, $currentDate, $fileName);

            return response()->json(["result" => 1]);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                "result" => 0,
                "data" => $e->getMessage(),
            ], 500);
        }
    }

    public function filter($fileLocation, $userId, $date, $fileName)
    {
        $minAccuracy = 1;
        $meterPerSecond = 5;
        $lastTimeStamp = 0;
        $lastLat = floatval(0);
        $lastLng = floatval(0);
        $variance = -1;
        $json = json_decode(file_get_contents($fileLocation), true);
        $result = [];

        foreach ($json as $value) {
            $accuracy = $value->accuracy;
            if ($accuracy < $minAccuracy) {
                $accuracy = $minAccuracy;
            }

            if ($variance < 0) {
                $lastTimeStamp = $value->timestamp;
                $lastLat = $value->lat;
                $lastLng = $value->lng;
                $variance = $accuracy * $accuracy;
                $result[] = [
                    "lat" => $value->lat,
                    "lng" => $value->lng,
                    "timestamp" => $value->timestamp,
                ];
            } else {
                $timestamp2 = $value->timestamp - $lastTimeStamp;

                if ($lastTimeStamp > 0) {
                    $variance += $timestamp2 * $meterPerSecond * $meterPerSecond / 1000;
                    $lastTimeStamp = $value->timestamp;
                }

                $k = $variance / ($variance + ($accuracy * $accuracy));
                $result[] = [
                    "lat" => $value->lat + ($k * ($value->lat - $lastLat)),
                    "lng" => $value->lng + ($k * ($value->lng - $lastLng)),
                    "timestamp" => $value->timestamp,
                ];
            }
        }

        file_put_contents(
            storage_path() . "/geolocation/" . $userId . "/" . $date . "/" . $fileName . "_filtered.json",
            json_encode($result)
        );
    }
}
