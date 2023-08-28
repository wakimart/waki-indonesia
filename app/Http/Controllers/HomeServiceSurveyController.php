<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\HomeServiceSurvey;
use App\HomeService;

class HomeServiceSurveyController extends Controller
{
    public function store(Request $request){
    	$request = new \Illuminate\Http\Request();
		$request->replace([
			'home_service_id' => 95690,
			'result_quest_1' => 3,
			'result_quest_2' => 4,
			'result_quest_3' => 5,
			'result_quest_4' => 2,
			'online_signature' => 'Sdvsdefaesedfvef',
		]);

        DB::beginTransaction();
        try {
        	$messages = array(
                'home_service_id.required' => 'Home Lapangan tidak ditemukan.',
                'home_service_id.unique' => 'Survey telah ada',
            );
	        $validator = Validator::make($request->all(), [
	            'home_service_id' => 'required|unique:home_service_surveys,home_service_id'
	        ], $messages);

	        if ($validator->fails()) {
	            $arr_Errors = $validator->errors()->all();
	            $arr_Keys = $validator->errors()->keys();
	            $arr_Hasil = [];
	            for ($i = 0; $i < count($arr_Keys); $i++) {
	                $arr_Hasil[$arr_Keys[$i]] = $arr_Errors[$i];
	            }
	            return response()->json(['errors' => $arr_Hasil]);
	        
	        }else{
		    	$data = $request->all();
		    	$HomeServiceSurveyNya = HomeServiceSurvey::create($data);
	            DB::commit();

	            return response()->json(['success' => $HomeServiceSurveyNya]);
	        }
        
        } catch (Exception $ex) {
            DB::rollback();
            return response()->json(['errors' => $ex->getMessage()]);
        }
    }

    public function detail(HomeServiceSurvey $id){
    	if($id != null){
	        return response()->json(['success' => $id]);
    	}
    	else {
            return response()->json(['errors' => 'Survey tidak ditemukan']);
    	}
    }
}
