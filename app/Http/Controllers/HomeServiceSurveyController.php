<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\HomeServiceSurvey;
use App\HomeService;

class HomeServiceSurveyController extends Controller
{
    public function store(Request $request){
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

		    	//for signature
		    	$filename = $data['home_service_id'] . "-signature.png";
	            $data_uri = explode(',', $data['online_signature']);
	            $encoded_image = $data_uri[1];
	            $decoded_image = base64_decode($encoded_image);
	            if (!is_dir("sources/home_service_surveys")) {
	                File::makeDirectory("sources/home_service_surveys", 0777, true, true);
	            }
	            file_put_contents('sources/home_service_surveys/' . $filename, $decoded_image);
	            $data['online_signature'] = $filename;

		    	$HomeServiceSurveyNya = HomeServiceSurvey::create($data);
	            DB::commit();

                return redirect()->back()->with("success", "Surveys has been uploaded");
	        }

        } catch (Exception $ex) {
            DB::rollback();
            return redirect()->back()->with("errors", $ex->getMessage());
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

    public function index(Request $request)
    {
        $questHSSurvey = HomeServiceSurvey::$allQuest;
        return view("admin.result_homeservice_survey", compact( "questHSSurvey" ));
    }

}
