<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\WakimartMember;
use DB;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Facades\Session;

class WakimartController extends Controller
{

    /** @var Type $var description */
    protected $client = null;


    /**
     * undocumented function summary
     *
     * Undocumented function long description
     *
     * @param Type $var Description
     * @return type
     * @throws conditon
     **/
    public function __construct()
    {
        $this->client = new Client([
            'base_uri'  => 'http://127.0.0.1:81/',
            'headers'   => [
                'api-key' => env('API_KEY')
            ]
        ]);
    }

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
                    if($request->mpc_code){
                        $doesTheMPCCodeAlreadyExist = WakimartMember::where('mpc_code', strtolower($request->mpc_code))->count();
                        if($doesTheMPCCodeAlreadyExist > 0){
                            return response()->json([
                                "status" => "bad request",
                                "message" => "mpc code already exist"
                            ], 400);
                        }else{
                            DB::beginTransaction();
                            try{
                                $newWakimartMember = new WakimartMember();
                                $newWakimartMember->member_id = $request->member_id;
                                $newWakimartMember->mpc_code = $request->mpc_code;
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
                            "message" => "must include mpc code"
                        ], 400);
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

    /**
     * MPC Waki List
     *
     * Undocumented function long description
     *
     * @param Type $var Description
     * @return type
     * @throws conditon
     **/
    public function listMPCWaki(Request $request)
    {
        $request->session()->forget(['success-api-waki', 'error-api-waki']);
        $wakimartMemberData = WakimartMember::orderBy('member_id', 'ASC')->get();
        $memberID = [];
        foreach ( $wakimartMemberData as $getMemberID ) {
            $memberID[] = $getMemberID->member_id;
        }
        $form = array(
            'member_id' => $memberID
        );
        $MPCWakiData = [];
        try {
            $res = $this->client->request('post', 'api/fetch-member', [
                'form_params' => $form
            ]);
            $decoded = json_decode($res->getBody()->getContents());
            $sessionStatus = '';
            $sessionMessage = '';
            if ( $decoded->status == "success" ) {
                foreach ( $decoded->data as $index => $mpc_data ) {
                    $data = [];
                    $data['name'] = $mpc_data->name;
                    $data['phone'] = $mpc_data->phone;
                    $mpcCode = '';
                    if($mpc_data->id == $wakimartMemberData[$index]->member_id){
                        $mpcCode = $wakimartMemberData[$index]->mpc_code;
                    }
                    $data['mpc_code'] = $mpcCode;
                    $MPCWakiData[] = $data;
                }

                // search
                if ( $request->has('search') && !empty($request->search) ) {
                    $MPCWakiData = $this->filterMPCData($MPCWakiData, $request->search);
                }

                $current_page = $request->input("page") ?? 1;

                $MPCWakiData = new Paginator($MPCWakiData, count($MPCWakiData), 10, $current_page, [
                    'path' => $request->url(),
                    'query' => $request->query(),
                ]);
                $sessionStatus = 'success-api-waki';
                $sessionMessage = $decoded->message;
            } else if ( $decoded->status == "error" ) {
                $sessionStatus = 'error-api-waki';
                $sessionMessage = $decoded->message;
            } else {
                $sessionStatus = 'error-api-waki';
                $sessionMessage = $decoded->message;
            }

            Session::put($sessionStatus, $sessionMessage);
            return view('admin.list_mpc_waki', compact('MPCWakiData'));
        } catch (RequestException $e) {
            if (! $e->hasResponse()) {
                Session::put('error-api-waki', "No response from server. Assume the host is offline or server is overloaded.");
                return view('admin.list_mpc_waki', compact('MPCWakiData'));
            }
            else{
                Session::put('error-api-waki', $e->getMessage());
                return view('admin.list_mpc_waki', compact('MPCWakiData'));
            }
        }
    }

    /**
     * filter function for mpc waki data
     *
     * Undocumented function long description
     *
     * @param Type $var Description
     * @return type
     * @throws conditon
     **/
    public function filterMPCData($data, $search)
    {
        // filter process
        $arrayIndex = [];
        foreach ( $data as $index => $val ) {
            if ( stripos($val['name'], $search) !== FALSE || stripos($val['phone'], $search) !== FALSE || stripos($val['mpc_code'], $search) !== FALSE ) {
                $arrayIndex[] = $index;
            }
        }

        // update new data after filter process
        $filter = [];
        foreach ( $arrayIndex as $arrIn ) {
            $filter[] = $data[$arrIn];
        }
        return $filter;
    }

    public function checkMpcWaki(Request $request){
        $wakimartMemberData = WakimartMember::where('mpc_code', $request->mpc_waki)->first();
        $memberID = [];
        if($wakimartMemberData) {
            $memberID[] = $wakimartMemberData->member_id;
        }
        $form = array(
            'member_id' => $memberID
        );
        try {
            $res = $this->client->request('post', 'api/fetch-member', [
                'form_params' => $form
            ]);
            $decoded = json_decode($res->getBody()->getContents());
            $sessionStatus = '';
            $sessionMessage = '';
            if ( $decoded->status == "success" ) {
                $data = [];
                foreach ( $decoded->data as $index => $mpc_data ) {
                    $data['name'] = $mpc_data->name;
                    $data['phone'] = $mpc_data->phone;
                }
                return response()->json([
                    "status" => "success",
                    "message" => "member data has been successfully saved on waki-indonesia",
                    "data_mpc" => $data
                ], 200);
            }
        } catch (Exception $e) {
            return response()->json([
                "status" => "error",
                "message" => $e->getMessage(),
            ], 500);
        }
    }
}
