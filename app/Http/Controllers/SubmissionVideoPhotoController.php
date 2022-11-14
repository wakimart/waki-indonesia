<?php

namespace App\Http\Controllers;

use App\Branch;
use App\Cso;
use App\Souvenir;
use App\SubmissionVideoPhoto;
use App\SubmissionVideoPhotoDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SubmissionVideoPhotoController extends Controller
{
    public function index(Request $request)
    {
        $url = $request->all();

        $submissionVPs = SubmissionVideoPhoto::where('active', true);
        
        if (Auth::user()->roles[0]->slug === "cso") {
            $submissionVPs->whereHas('submissionVideoPhotoDetails', function($q) {
                $q->where('cso_id', Auth::user()->cso['id']);
            });
        } else if (Auth::user()->roles[0]->slug === "branch") {
            // Jika user adalah BRANCH atau AREA MANAGER
            // Inisialisasi variabel $arrBranches untuk menyimpan hasil query
            $arrBranches = [];

            // Menyimpan BRANCH yang dipegang oleh user ke dalam $arrBranches
            foreach (Auth::user()->listBranches() as $value) {
                $arrBranches[] = $value['id'];
            }

            $submissionVPs::whereIn("branch_id", $arrBranches);
        }

        if ($request->has('filter_date')) {
            $submissionVPs->whereDate('submission_date', $request->filter_date);
        }

        if ($request->has('filter_branch')) {
            $submissionVPs->where("branch_id", $request->filter_branch);
        }

        if ($request->has('filter_cso')) {
            $submissionVPs->whereHas('submissionVideoPhotoDetails', function($q) use ($request) {
                $q->where('cso_id', $request->filter_cso);
            });
        }
        
        $branches = Branch::select("id", "code", "name")
            ->where("active", true)
            ->orderBy("code", 'asc')
            ->get();
        $csos = Cso::where('active', true)->get();
        $submissionVPs = $submissionVPs->orderBy('id', "desc")->paginate(10);

        return view(
            "admin.list_submission_video_photo",
            compact(
                "submissionVPs",
                "branches",
                "csos",
                "url",
            )
        )
        ->with("i", (request()->input("page", 1) - 1) * 10 + 1);
    }

    public function create(Request $request)
    {
        $branches = Branch::where('active', true)->orderBy('code', 'asc')->get();
        $csos = Cso::where('active', true)->get();
        $souvenirs = Souvenir::select("id", "name")
            ->where("active", true)
            ->get();

        return view(
            "admin.add_submission_video_photo",
            compact(
                'branches',
                'csos',
                'souvenirs',
            )
        );
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'branch_id' => [
                'required', 
                Rule::unique('submission_video_photos')
                    ->where('active', true)
                    ->where('branch_id', $request->branch_id)
                    ->where('status', 'pending')
            ],
        ]);
        if($validator->fails()){
            $arr_Errors = $validator->errors()->all();
            $arr_Keys = $validator->errors()->keys();
            $arr_Hasil = [];
            for ($i = 0; $i < count($arr_Keys); $i++) {
                $arr_Hasil[$arr_Keys[$i]] = $arr_Errors[$i];
            }
            return response()->json(['errors' => $arr_Hasil]);
        }
        
        $data = $request->all();
        DB::beginTransaction();
        try {
            $data['status'] = "pending";
            $submissionVP = SubmissionVideoPhoto::create($data);

            $user_id = Auth::user()["id"];
            foreach($data['member_details'] as $i) {
                if (!empty($data["name_ref_$i"])) {
                    $subVPDetail = new SubmissionVideoPhotoDetail();
                    $subVPDetail->submission_video_photo_id = $submissionVP->id;
                    $subVPDetail->detail_date = $data["detail_date_ref_$i"];
                    $subVPDetail->cso_id = $data["cso_ref_$i"];
                    $subVPDetail->mpc_wakimart = $data["mpc_wakimart_ref_$i"] ?? null;
                    $subVPDetail->name = $data["name_ref_$i"];
                    $subVPDetail->phone = $data["phone_ref_$i"];
                    $subVPDetail->address = $data["address_ref_$i"];
                    $subVPDetail->url_drive = $data["url_drive_ref_$i"];
                    $subVPDetail->souvenir = $data["souvenir_$i"];
                    $subVPDetail->save();
                }
            }

            DB::commit();
            return redirect()->route('detail_submission_video_photo', ['id' => $submissionVP->id]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                "error" => $e,
                "error message" => $e->getMessage(),
            ], 400);
        }
    }

    public function show(Request $request)
    {
        return 'aw';
    }

    public function checkBrancSubmission(Request $request)
    {
        if ($request->branch) {
            $submissionVP = SubmissionVideoPhoto::where('branch_id', $request->branch)
                ->where('status', 'pending')
                ->first();
            if (!$submissionVP) {
                return response()->json([
                    "status" => "success",
                ], 200);
            } else {
                return response()->json([
                    "exists" => "The Last Submission Video Photo status still pending."
                ], 200); 
            }
        } else {
            return response()->json([
                "status" => "error",
                "data" => "Error Request Parameter",
            ], 200); 
        }
    }
}
