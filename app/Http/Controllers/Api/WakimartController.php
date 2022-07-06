<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\WakimartMember;
use DB;

class WakimartController extends Controller
{
    /**
     * store wakimart member.
     *
     * @return \Illuminate\Http\Response
     */
    public function storeMember(Request $request)
    {
        $apiKey = $request->header('api-key');
        if($apiKey == env('API_KEY')){
            if($request->member_id){
                $is_member_already_exist = WakimartMember::where('member_id', $request->member_id)->count();
                if($is_member_already_exist > 0){
                    return response()->json([
                        "status" => "bad request",
                        "message" => "member already exist"
                    ], 400);
                }else{
                    DB::beginTransaction();
                    try{
                        $newWakimartMember = new WakimartMember();
                        $newWakimartMember->member_id = $request->member_id;
                        $newWakimartMember->save();
                        DB::commit();
                        return response()->json([
                            "status" => "success",
                            "message" => "member data has been successfully saved on waki-indonesia"
                        ], 200);
                    }catch (\Exception $ex) {
                        DB::rollback();
                        return response()->json([
                            "status" => "error",
                            "message" => $ex->getMessage()
                        ], 500);
                    }
                }
            }else{
                return response()->json([
                    "status" => "bad request",
                    "message" => "must include member id"
                ], 400);
            }
        }else{
            return response()->json([
                "status" => "unauthenticated",
                "message" => "you don't have access"
            ], 401);
        }
    }
}
