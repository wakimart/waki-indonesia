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
}
