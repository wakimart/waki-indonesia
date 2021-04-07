<?php

namespace App\Http\Controllers;

use App\HistoryUpdate;
use App\Souvenir;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SouvenirController extends Controller
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
        $souvenir = Souvenir::where("active", true);
        $countSouvenir = $souvenir->count();
        $souvenir = $souvenir->paginate(10);

        return view(
            "admin.list_souvenir",
            compact(
                "countSouvenir",
                "souvenir",
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
        return view("admin.add_souvenir");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                "name" => ["required", "string", "max:191"],
            ]
        );

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator);
        }

        Souvenir::create($request->only("name"));

        return redirect()
            ->route("add_souvenir")
            ->with("success", "Data souvenir baru berhasil dimasukkan.");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
            $souvenir = Souvenir::find($request->id);

            return view("admin.update_souvenir", compact("souvenir"));
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
            $validator = Validator::make(
                $request->all(),
                [
                    "name" => ["required", "string", "max:191"],
                ]
            );

            if ($validator->fails()) {
                return redirect()
                    ->back()
                    ->withErrors($validator);
            }

            DB::beginTransaction();

            try {
                $souvenir = Souvenir::find($request->id);
                $souvenir->fill($request->only("name"));
                $souvenir->save();

                $user = Auth::user();
                $historyUpdateSouvenir["type_menu"] = "Souvenir";
                $historyUpdateSouvenir["method"] = "Update";
                $historyUpdateSouvenir["meta"] = json_encode(
                    [
                        "user" => $user["id"],
                        "createdAt" => date("Y-m-d H:i:s"),
                        "dataChange" => $souvenir->getChanges(),
                    ],
                    JSON_THROW_ON_ERROR
                );

                $historyUpdateSouvenir["user_id"] = $user["id"];
                $historyUpdateSouvenir["menu_id"] = $request->id;

                HistoryUpdate::create($historyUpdateSouvenir);

                DB::commit();

                return redirect()
                    ->route("list_souvenir")
                    ->with("success", "Data souvenir berhasil diperbarui");
            } catch (Exception $e) {
                DB::rollback();

                return response()->json([
                    "error" => $e,
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
                $souvenir = Souvenir::find($request->id);
                $souvenir->active = false;
                $souvenir->save();

                $user = Auth::user();

                $historyUpdateSouvenir["type_menu"] = "Souvenir";
                $historyUpdateSouvenir["method"] = "Update";
                $historyUpdateSouvenir["meta"] = json_encode(
                    [
                        "user" => $user["id"],
                        "createdAt" => date("Y-m-d H:i:s"),
                        "dataChange" => $souvenir->getChanges(),
                    ],
                    JSON_THROW_ON_ERROR
                );

                $historyUpdateSouvenir["user_id"] = $user["id"];
                $historyUpdateSouvenir["menu_id"] = $request->id;

                DB::commit();

                return redirect()
                    ->route("list_souvenir")
                    ->with("success", "Data souvenir berhasil dihapus.");
            } catch (Exception $e) {
                DB::rollback();

                return response()->json([
                    "error" => $e,
                ]);
            }
        }

        return response()->json(["error" => "Data tidak ditemukan."]);
    }
}
