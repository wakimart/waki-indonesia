<?php

namespace App\Http\Controllers;

use App\HistoryUpdate;
use App\Prize;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PrizeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $url = $request->all();
        $prize = Prize::where("active", true);
        $countPrize = $prize->count();
        $prize = $prize->paginate(10);

        return view(
            "admin.list_prize",
            compact(
                "countPrize",
                "prize",
                "url",
            )
        )
        ->with('i', (request()->input('page', 1) - 1) * 10 + 1);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view("admin.add_prize");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        Prize::create($request->only("name"));

        return redirect()
            ->route("add_prize")
            ->with("success", "Data prize baru berhasil dimasukkan.");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse
     */
    public function edit(Request $request)
    {
        if (!empty($request->id)) {
            $prize = Prize::find($request->id);

            return view("admin.update_prize", compact("prize"));
        }

        return response()->json(["error" => "Data tidak ditemukan."]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        if (!empty($request->id)) {
            DB::beginTransaction();

            try {
                $prize = Prize::find($request->id);
                $prize->fill($request->only("name"));
                $prize->save();

                $this->changeHistory("Update", $prize);

                DB::commit();

                return redirect()
                    ->route("list_prize")
                    ->with("success", "Data prize berhasil diperbarui.");
            } catch (Exception $e) {
                DB::rollback();

                return response()->json([
                    "error" => $e->getMessage(),
                ]);
            }
        }

        return response()->json(["error" => "Data tidak ditemukan."]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        if (!empty($request->id)) {
            DB::beginTransaction();

            try {
                $prize = Prize::find($request->id);
                $prize->active = false;
                $prize->save();

                $this->changeHistory("Delete", $prize);

                DB::commit();

                return redirect()
                    ->route("list_prize")
                    ->with("success", "Data prize berhasil dihapus.");
            } catch (Exception $e) {
                DB::rollback();

                return response()->json([
                    "error" => $e,
                ]);
            }
        }

        return response()->json(["error" => "Data tidak ditemukan."]);
    }

    private function changeHistory($method, $prize)
    {
        $user = Auth::user();

        $historyPrize["type_menu"] = "Prize";
        $historyPrize["method"] = $method;
        $historyPrize["meta"] = json_encode(
            [
                "user" => $user["id"],
                "createdAt" => date("Y-m-d H:i:s"),
                "dataChange" => $prize->getChanges(),
            ],
            JSON_THROW_ON_ERROR
        );

        $historyPrize["user_id"] = $user["id"];
        $historyPrize["menu_id"] = $prize->id;

        HistoryUpdate::create($historyPrize);
    }

    public function fetchPrize()
    {
        $prizes = Prize::select("id", "name")
            ->where("active", true)
            ->get();

        return response()->json([
            "result" => $prizes->count(),
            "data" => $prizes,
        ]);
    }
}
