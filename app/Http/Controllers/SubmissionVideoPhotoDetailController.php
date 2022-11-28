<?php

namespace App\Http\Controllers;

use App\HistoryUpdate;
use App\SubmissionVideoPhotoDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SubmissionVideoPhotoDetailController extends Controller
{
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->all();
            $user = Auth::user();
            $subVPDetail = new SubmissionVideoPhotoDetail();
            $subVPDetail->submission_video_photo_id = $data['submission_id'];
            $subVPDetail->detail_date = $data["detail_date"];
            $subVPDetail->cso_id = $data["cso"];
            $subVPDetail->mpc_wakimart = $data["mpc_wakimart"] ?? null;
            $subVPDetail->name = $data["name"];
            $subVPDetail->phone = $data["phone"];
            $subVPDetail->address = $data["address"];
            $subVPDetail->url_drive = $data["url_drive"];
            $subVPDetail->souvenir = $data["souvenir"];
            $subVPDetail->save();

            $historyUpdate = [];
            $historyUpdate["type_menu"] = "Submission Video Photo";
            $historyUpdate["method"] = "Update";
            $historyUpdate["meta"] = json_encode([
                "user" => $user["id"],
                "updatedAt" => date("Y-m-d H:i:s"),
                'dataChange'=> ["Add Detail" => $subVPDetail->id]
            ], JSON_THROW_ON_ERROR);

            $historyUpdate['user_id'] = $user['id'];
            $historyUpdate['menu_id'] = $subVPDetail->submission_video_photo_id;
            $createData = HistoryUpdate::create($historyUpdate);

            DB::commit();
            return redirect()->back()->with('success', 'Submission Video & Photo Detail Berhasil Di Tambah');
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json(["errors" => $ex->getMessage()], 500);
        }
    }

    public function update(Request $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->all();
            $user = Auth::user();
            $subVPDetail = SubmissionVideoPhotoDetail::find($data['submission_id']);
            $subVPDetail->detail_date = $data["detail_date"];
            $subVPDetail->cso_id = $data["cso"];
            $subVPDetail->mpc_wakimart = $data["mpc_wakimart"] ?? null;
            $subVPDetail->name = $data["name"];
            $subVPDetail->phone = $data["phone"];
            $subVPDetail->address = $data["address"];
            $subVPDetail->url_drive = $data["url_drive"];
            $subVPDetail->souvenir = $data["souvenir"];
            $subVPDetail->save();

            $historyUpdate = [];
            $historyUpdate["type_menu"] = "Submission Video Photo";
            $historyUpdate["method"] = "Update";
            $historyUpdate["meta"] = json_encode([
                "user" => $user["id"],
                "updatedAt" => date("Y-m-d H:i:s"),
                'dataChange'=> ["Update Detail" => $subVPDetail->getChanges()]
            ], JSON_THROW_ON_ERROR);

            $historyUpdate['user_id'] = $user['id'];
            $historyUpdate['menu_id'] = $subVPDetail->submission_video_photo_id;
            $createData = HistoryUpdate::create($historyUpdate);

            DB::commit();
            return redirect()->back()->with('success', 'Submission Video & Photo Detail Berhasil Di Ubah');
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json(["errors" => $ex->getMessage()], 500);
        }
    }

    public function destroy(Request $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->all();
            $user = Auth::user();
            $subVPDetailOld = SubmissionVideoPhotoDetail::find($data['id']);
            $subVPDetail = SubmissionVideoPhotoDetail::find($data['id']);
            $subVPDetail->delete();

            $historyUpdate = [];
            $historyUpdate["type_menu"] = "Submission Video Photo";
            $historyUpdate["method"] = "Update";
            $historyUpdate["meta"] = json_encode([
                "user" => $user["id"],
                "updatedAt" => date("Y-m-d H:i:s"),
                'dataChange'=> ["Deleted Detail" => $subVPDetailOld]
            ], JSON_THROW_ON_ERROR);

            $historyUpdate['user_id'] = $user['id'];
            $historyUpdate['menu_id'] = $subVPDetail->submission_video_photo_id;
            $createData = HistoryUpdate::create($historyUpdate);

            DB::commit();
            return redirect()->back()->with('success', 'Submission Video & Photo Detail Berhasil Di Hapus');
        }catch (\Exception $ex) {
            DB::rollback();
            return response()->json(['error' =>  $ex->getMessage(), 500]);
        }
    }

    public function updateStatus(Request $request)
    {
        try {
            $user = Auth::user();
            $subVPDetail = SubmissionVideoPhotoDetail::find($request->id);
            $subVPDetail->status = $request->status;
            $subVPDetail->acc_description = $request->has('acc_description') ? $request->acc_description : null;
            $subVPDetail->save();

            $historyUpdate = [];
            $historyUpdate["type_menu"] = "Submission Video Photo";
            $historyUpdate["method"] = "Update";
            $historyUpdate["meta"] = json_encode([
                "user" => $user["id"],
                "updatedAt" => date("Y-m-d H:i:s"),
                'dataChange'=> ["Status Detail" => $subVPDetail->status]
            ], JSON_THROW_ON_ERROR);

            $historyUpdate['user_id'] = $user['id'];
            $historyUpdate['menu_id'] = $subVPDetail->submission_video_photo_id;
            $createData = HistoryUpdate::create($historyUpdate);

            DB::commit();

            return redirect()
                ->route("detail_submission_video_photo", ["id" => $subVPDetail->submission_video_photo_id])
                ->with('success', 'Status Detail telah diperbarui.');
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                "error" => $e,
                "error message" => $e->getMessage(),
            ], 500);
        }
    }
}
