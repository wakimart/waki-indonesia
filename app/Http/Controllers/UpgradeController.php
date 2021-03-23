<?php

namespace App\Http\Controllers;

use App\HistoryUpdate;
use App\Upgrade;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UpgradeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexNew(Request $request)
    {
        $url = $request->all();
        $upgrades = Upgrade::where([['active', true], ['status', 'new']])->paginate(10);
        return view('admin.list_upgrade_new', compact('upgrades', 'url'));
    }

    public function list(Request $request)
    {
        $url = $request->all();
        $upgrades = Upgrade::where([['active', true], ['status', '!=', 'new']])->paginate(10);
        return view('admin.list_upgrade', compact('upgrades', 'url'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $upgrade = Upgrade::find($id);
        return view('admin.add_upgrade', compact('upgrade'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $upgrade = Upgrade::find($data['upgrade_id']);
        $upgrade['task'] = $data['task'];
        $upgrade['due_date'] = $data['due_date'];
        $upgrade['status'] = "process";
        $tempHistoryStatus = [];
        if($upgrade['history_status'] != null){
            $tempHistoryStatus = $upgrade['history_status'];
        }
        array_push($tempHistoryStatus, ['user_id' => Auth::user()['id'], 'status' => $upgrade['status'], 'updated_at' => date("Y-m-d H:i:s")]);
        $upgrade['history_status'] = $tempHistoryStatus;
        $upgrade->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Upgrade  $upgrade
     * @return \Illuminate\Http\Response
     */
    public function detail($id)
    {
        $upgrade = Upgrade::find($id);
        return view('admin.detail_upgrade', compact('upgrade'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function edit(Request $request)
    {
        if (!empty($request->id)) {
            $upgrade = Upgrade::find($request->id);

            return view('admin.update_upgrade', compact('upgrade'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function update(Request $request)
    {
        if (!empty($request->id)) {
            DB::beginTransaction();

            try {
                $upgrade = Upgrade::find($request->id);
                $upgrade = $upgrade->fill($request->only("due_date", "task"));
                $upgrade->save();

                $user = Auth::user();
                $historyUpdateUpgrade["type_menu"] = "Upgrade";
                $historyUpdateUpgrade["method"] = "Update";
                $historyUpdateUpgrade["meta"] = json_encode(
                    [
                        "user" => $user["id"],
                        "createdAt" => date("Y-m-d H:i:s"),
                        "dataChange" => $upgrade->getChanges(),
                    ]
                );
                $historyUpdateUpgrade["user_id"] = $user["id"];
                $historyUpdateUpgrade["menu_id"] = $request->id;
                HistoryUpdate::create($historyUpdateUpgrade);

                DB::commit();

                return redirect()->route("detail_upgrade_form", ["id" => $upgrade->id]);
            } catch (\Exception $e) {
                DB::rollback();

                return response()->json([
                    "error" => $e,
                ]);
            }
        }
    }

    public function updateStatus(Request $request)
    {
        if (!empty($request->id)) {
            DB::beginTransaction();

            try {
                $upgrade = Upgrade::find($request->id);

                // TODO: Ganti "IF" bagian ini jika bagian lain yang dibutuhkan sudah selesai
                if (
                    $request->status === "display"
                    || $request->status === "ready"
                ) {
                    $request->status = "completed";
                }

                // Memperbarui status
                $upgrade->status = $request->status;

                $user = Auth::user();
                // Memperbarui history_status
                $historyStatus = $upgrade->history_status;
                $historyStatus[] = [
                    "user_id" => $user["id"],
                    "status" => $request->status,
                    "updated_at" => date("Y-m-d H:i:s"),
                ];
                $upgrade->history_status = $historyStatus;
                $upgrade->save();

                // Menambahkan riwayat pembaruan ke tabel history_updates
                $historyUpdateUpgrade["type_menu"] = "Upgrade";
                $historyUpdateUpgrade["method"] = "Update";
                $historyUpdateUpgrade["meta"] = json_encode(
                    [
                        "user" => $user["id"],
                        "createdAt" => date("Y-m-d H:i:s"),
                        "dataChange" => $upgrade->getChanges(),
                    ]
                );
                $historyUpdateUpgrade["user_id"] = $user["id"];
                $historyUpdateUpgrade["menu_id"] = $request->id;
                HistoryUpdate::create($historyUpdateUpgrade);

                DB::commit();

                return redirect()->route("detail_upgrade_form", ["id" => $upgrade->id]);
            } catch (\Exception $e) {
                DB::rollback();

                return response()->json([
                    "error" => $e,
                ]);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $upgrade = Upgrade::find($id);
            $upgrade["active"] = false;
            $upgrade->save();

            $user = Auth::user();
            $historyDeleteUpgrade["type_menu"] = "Upgrade";
            $historyDeleteUpgrade["method"] = "delete";
            $historyDeleteUpgrade["meta"] = json_encode(
                [
                    "user" => $user["id"],
                    "createdAt" => date("Y-m-d H:i:s"),
                    "dataChange" => $upgrade->getChanges(),
                ]
            );
            $historyDeleteUpgrade["user_id"] = $user['id'];
            $historyDeleteUpgrade["menu_id"] = $id;

            HistoryUpdate::create($historyDeleteUpgrade);

            DB::commit();

            return redirect()->route("list_new_upgrade_form")->with(
                "success",
                "Data berhasil dihapus!"
            );
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                "error" => $e,
            ]);
        }
    }
}
