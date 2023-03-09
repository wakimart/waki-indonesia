<?php

namespace App\Http\Controllers;

use App\Branch;
use App\TheraphySignIn;
use App\TheraphyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class TheraphyServiceController extends Controller
{
    public function create(Request $request)
    {
        $branches = Branch::Where('active', true)->orderBy("code", 'asc')->get();
    	$meta_default = TheraphyService::$meta_default;
        return view('admin.add_theraphy_service', compact('meta_default', 'branches'));
    }

    public function store(Request $request){
    	$validator = Validator::make($request->all(), [
            'phone' => [
                Rule::unique('theraphy_services')
                	->where('active', 1)
                    ->whereIn('status', ['process', 'success']),
            ],
        ]);
        if($validator->fails()){
			return back()->withErrors($validator->errors())->withInput();
		}
		else{
            $data = $request->all();

            $nowTime = strtotime('now'); 
            $data['code'] = substr($nowTime, 3, 3).'-'.substr($nowTime, -4).'-'.substr($data['phone'], -3);

            $data['meta_condition'] = [];
	    	$meta_default = TheraphyService::$meta_default;
			foreach($meta_default as $idxNya => $listMeta){
				if(isset($data['rdaChoose-'.$idxNya])){
					array_push($data['meta_condition'], [
						$listMeta => [$data['rdaChoose-'.$idxNya], $data['desc-'.$idxNya]]
					]);
				}
			}
			$theraphyService = TheraphyService::create($data);

			$therapySignIn = TheraphySignIn::create(['theraphy_service_id' => $theraphyService['id'], 'therapy_date' => $data['registered_date'], 'user_id' => Auth::user()->id]);
            return redirect()->route("check_theraphy_service", ['code' => $theraphyService['code']])->with('success', 'Data berhasil dimasukkan.');

		}
    }

    public function check(Request $request){
    	$meta_default = TheraphyService::$meta_default;
    	if(isset($request->code)){
			$custTherapy = TheraphyService::where([['code', $request->code], ['active', true]])->first();
	        return view('admin.check_theraphy_service', compact('meta_default', 'custTherapy'));
    	}
        return view('admin.check_theraphy_service', compact('meta_default'));
    }

    public function storeCheckIn(Request $request){
    	$custTherapy = TheraphyService::find($request->id);
    	if($custTherapy){
    		if(count($custTherapy->theraphySignIn->where('therapy_date', date('Y-m-d', strtotime('now')))) < 1){
				$therapySignIn = TheraphySignIn::create(['theraphy_service_id' => $custTherapy['id'], 'therapy_date' => date('Y-m-d', strtotime('now')), 'user_id' => Auth::user()->id]);

                if(count(TheraphyService::find($request->id)->theraphySignIn) == 30){
                    $custTherapy->status = 'success';
                    $custTherapy->save();
                }

	            return redirect()->route("check_theraphy_service", ['code' => $custTherapy['code']])->with('success', 'Data berhasil dimasukkan.');
    		}
	        return redirect()->route("check_theraphy_service", ['code' => $custTherapy['code']])->with('success', 'Data sudah ada.');
    	}
        return redirect()->route("check_theraphy_service");
    }
}
