<?php

namespace App\Http\Controllers;

use App\Branch;
use App\TheraphySignIn;
use App\TherapyLocation;
use App\TheraphyService;
use App\TherapyServiceSouvenir;
use App\Souvenir;
use App\HistoryUpdate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use DB;

class TheraphyServiceController extends Controller
{
    public function create(Request $request)
    {
        $branches = Branch::Where('active', true)->orderBy("code", 'asc')->get();
    	$meta_default = TheraphyService::$meta_default;
		$therapy_locations = TherapyLocation::where('status', true)->get();
        return view('admin.add_theraphy_service', compact('meta_default', 'branches', 'therapy_locations'));
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
            $branchData = Branch::find($data['branch_id']);
            $data['code'] = $branchData['code'].'-'.substr($data['phone'], -3).'-';
            $tempNya = TheraphyService::where('code', 'LIKE', '%'.$data['code'].'%')->count();
            $data['code'] .= str_pad($tempNya, 2, "0", STR_PAD_LEFT);

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
            return redirect()->route("detail_theraphy_service", ['id' => $theraphyService['id']])->with('success', 'Data berhasil dimasukkan.');

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
            $signInDate = $request->signInDate ?? date('Y-m-d', strtotime('now'));
    		if(count($custTherapy->theraphySignIn->where('therapy_date', $signInDate)) < 1){
				$therapySignIn = TheraphySignIn::create(['theraphy_service_id' => $custTherapy['id'], 'therapy_date' => $signInDate, 'user_id' => Auth::user()->id]);

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

	public function list(Request $request){
		$url = $request->all();
        $branches = Branch::Where('active', true)->orderBy("code", 'asc')->get();
        $theraphyServices = TheraphyService::where('active', true)->orderBy('created_at', 'desc');

        if(Auth::user()->cso){
            $theraphyServices = $theraphyServices->where('branch_id', Auth::user()->cso->branch->id);
        }

        if ($request->has('filter_branch')) {
            $theraphyServices = $theraphyServices->where('branch_id', $request->filter_branch);
        }

        if ($request->has('search')) {
            $theraphyServices = $theraphyServices->where(function($query) use ($request) {
                $query->where('name','LIKE', '%'.$request->search.'%')
                    ->orWhere('code','LIKE', '%'.$request->search.'%')
                    ->orWhere('phone','LIKE', '%'.$request->search.'%');
            });
        }

        $countTheraphyService = count($theraphyServices->get());

        $theraphyServices = $theraphyServices->paginate(10);

        return view('admin.list_theraphy_service', compact('theraphyServices', 'countTheraphyService', 'branches', 'url'));
	}

    public function detail($id){
        $theraphyService = TheraphyService::find($id);
    	$meta_default = TheraphyService::$meta_default;
        $souvenirs = Souvenir::where('active', true)->get();
        return view('admin.detail_theraphy_service', compact('theraphyService', 'meta_default', 'souvenirs'));
    }

    public function addTherapyServiceSouvenir(Request $request){
        DB::beginTransaction();
        try {
            $theraphyServiceSouvenir = new TherapyServiceSouvenir();
            $theraphyServiceSouvenir->therapy_service_id = $request->therapy_service_id;
            $theraphyServiceSouvenir->souvenir_id = $request->souvenir_id;
            $theraphyServiceSouvenir->user_id = $request->user_id;
            $theraphyServiceSouvenir->save();

            DB::commit();
            return redirect()->route("detail_theraphy_service", $request->therapy_service_id)->with('success', 'Data berhasil ditambahkan.');
        } catch (\Exception $ex) {
            DB::rollback();
            return redirect()->route("detail_theraphy_service", $request->therapy_service_id)->with("error", $ex);
        }
    }

    public function edit($id){
        $theraphyService = TheraphyService::find($id);
        $branches = Branch::Where('active', true)->orderBy("code", 'asc')->get();
    	$meta_default = TheraphyService::$meta_default;
		$therapy_locations = TherapyLocation::all();
        return view('admin.update_theraphy_service', compact('theraphyService', 'meta_default', 'branches', 'therapy_locations'));
    }

    public function update(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'phone' => [
                Rule::unique('theraphy_services')
                	->where('active', 1)
                    ->whereIn('status', ['process', 'success'])
                    ->ignore($id, 'id'),
            ],
        ]);
        if($validator->fails()){
			return back()->withErrors($validator->errors())->withInput();
		}else{
            DB::beginTransaction();
            try {
                $data = $request->all();

                $data['meta_condition'] = [];
                $meta_default = TheraphyService::$meta_default;
                foreach($meta_default as $idxNya => $listMeta){
                    if(isset($data['rdaChoose-'.$idxNya])){
                        array_push($data['meta_condition'], [
                            $listMeta => [$data['rdaChoose-'.$idxNya], $data['desc-'.$idxNya]]
                        ]);
                    }
                }
                $theraphyService = TheraphyService::find($id);
                $theraphyService->update($data);

                DB::commit();
                return redirect()->route("check_theraphy_service", ['code' => $theraphyService['code']])->with('success', 'Data berhasil diperbaharui.');
            } catch (\Exception $ex) {
                DB::rollback();
                return redirect()->route("edit_theraphy_service", $id)->with("error", $ex);
            }
		}
    }

    public function updateStatus(Request $request, $id){
        DB::beginTransaction();
        try {
            $theraphyService = TheraphyService::find($id);
            $theraphyService->status = $request->status;
            $theraphyService->update();

            DB::commit();
            return redirect()->route("detail_theraphy_service", $id)->with('success', 'Data berhasil diperbaharui.');
        } catch (\Exception $ex) {
            DB::rollback();
            return redirect()->route("detail_theraphy_service", $id)->with("error", $ex);
        }
    }

    public function destroy($id){
        DB::beginTransaction();

        if (!empty($id)) {
            try {
                $theraphyService = TheraphyService::find($id);
                $theraphyService->active = false;
                $theraphyService->update();

                DB::commit();

                return redirect()
                    ->route("list_theraphy_service")
                    ->with("success", "Successfully deleted!");
            } catch (Exception $e) {
                DB::rollback();
                return redirect()->route("list_theraphy_service")->with("error", "Something wrong, please contact IT");
            }
        }

        return redirect()->route("list_theraphy_service")->with("error", "Data not found, please contact IT");
    }

    public function createTherapyLocation(){
        return view('admin.add_therapy_location');
    }

    public function storeTherapyLocation(Request $request){
        $validator = Validator::make(
            $request->all(),
            [
                "name" => ["required", "string"],
            ]
        );

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator);
        }

        TherapyLocation::create($request->only('name'));

        return redirect()
            ->route("add_therapy_location")
            ->with("success", "Data Therapy Location berhasil ditambahkan.");
    }

    public function listTherapyLocation(Request $request){
        $url = $request->all();
        $therapyLocations = TherapyLocation::where("status", true);
        $countTherapyLocations = $therapyLocations->count();
        $therapyLocations = $therapyLocations->paginate(10);

        return view(
            "admin.list_therapy_location",
            compact(
                "countTherapyLocations",
                "therapyLocations",
                "url",
            )
        )
        ->with('i', (request()->input('page', 1) - 1) * 10 + 1);
    }

    public function editTherapyLocation($id){
        $therapyLocation = TherapyLocation::find($id);
        return view('admin.update_therapy_location', compact('therapyLocation'));
    }

    public function updateTherapyLocation(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);
        if($validator->fails()){
			return back()->withErrors($validator->errors())->withInput();
		}else{
            DB::beginTransaction();
            try {
                $therapyLocation = TherapyLocation::find($id);
                $therapyLocation->name = $request->name;
                $therapyLocation->save();

                $user = Auth::user();
                $historyUpdateTherapyLocation["type_menu"] = "Therapy Location";
                $historyUpdateTherapyLocation["method"] = "Update";
                $historyUpdateTherapyLocation["meta"] = json_encode(
                    [
                        "user" => $user["id"],
                        "createdAt" => date("Y-m-d H:i:s"),
                        "dataChange" => $therapyLocation->getChanges(),
                    ],
                    JSON_THROW_ON_ERROR
                );

                $historyUpdateTherapyLocation["user_id"] = $user["id"];
                $historyUpdateTherapyLocation["menu_id"] = $id;

                HistoryUpdate::create($historyUpdateTherapyLocation);

                DB::commit();

                return redirect()->route("edit_therapy_location", $id)->with('success', 'Data berhasil diperbaharui.');
            } catch (\Exception $ex) {
                DB::rollback();
                return redirect()->route("edit_therapy_location", $id)->with("error", $ex);
            }
		}
    }

    public function destroyTherapyLocation($id){
        DB::beginTransaction();

        if (!empty($id)) {
            try {
                $theraphyLocation = TherapyLocation::find($id);
                $theraphyLocation->status = false;
                $theraphyLocation->update();

                DB::commit();

                return redirect()
                    ->route("list_therapy_location")
                    ->with("success", "Successfully deleted!");
            } catch (Exception $e) {
                DB::rollback();
                return redirect()->route("list_therapy_location")->with("error", "Something wrong, please contact IT");
            }
        }

        return redirect()->route("list_therapy_location")->with("error", "Data not found, please contact IT");
    }
}
