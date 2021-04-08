<?php

namespace App\Http\Controllers;

use App\HistoryUpdate;
use App\Sparepart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SparepartController extends Controller
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

        $sparepart = Sparepart::where("active", true);
        $countSparepart = $sparepart->count();
        $sparepart = $sparepart->paginate(10);

        return view(
            "admin.list_sparepart",
            compact(
                "countSparepart",
                "sparepart",
                "url"
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
        return view("admin.add_sparepart");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        /**
         * Mengecek inputan dengan validator
         * ! Jika name.max di database berubah, jangan lupa ubah maxlength input di add_sparepart.blade.php
         */
        $validator = Validator::make(
            $request->all(),
            [
                "name" => ["required", "string", "max:191"],
                "price" => ["required", "numeric", "digits_between:1,20"],
            ]
        );

        // Jika validator gagal, maka akan kembali ke halaman create
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput($request->all());
        }

        Sparepart::create($request->all());

        return redirect()->route("list_sparepart");
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
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
            $sparepart = Sparepart::find($request->id);

            return view("admin.update_sparepart", compact("sparepart"));
        }

        return response()->json(["result" => "Data tidak ditemukan."]);
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
            /**
             * Mengecek inputan dengan validator
             * ! Jika name.max di database berubah, jangan lupa ubah maxlength input di updatesparepart.blade.php
             */
            $validator = Validator::make(
                $request->all(),
                [
                    "name" => ["required", "string", "max:191"],
                    "price" => ["required", "numeric", "digits_between:1,20"],
                ]
            );

            // Jika validator gagal, maka akan kembali ke halaman edit
            if ($validator->fails()) {
                return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput($request->all());
            }

            DB::beginTransaction();

            try {
                $sparepart = Sparepart::find($request->id);
                $sparepart->fill($request->only("name", "price"));
                $sparepart->save();

                $user = Auth::user();
                $historyUpdateSparepart["type_menu"] = "Sparepart";
                $historyUpdateSparepart["method"] = "Update";
                $historyUpdateSparepart["meta"] = json_encode(
                    [
                        "user" => $user["id"],
                        "createdAt" => date("Y-m-d H:i:s"),
                        "dataChange" => $sparepart->getChanges(),
                    ],
                    JSON_THROW_ON_ERROR
                );

                $historyUpdateSparepart["user_id"] = $user["id"];
                $historyUpdateSparepart["menu_id"] = $request->id;
                HistoryUpdate::create($historyUpdateSparepart);

                DB::commit();

                return redirect()->route("list_sparepart")
                    ->with("success", "Data berhasil diperbarui!");
            } catch (\Exception $e) {
                DB::rollback();

                return response()->json([
                    "error" => $e,
                ]);
            }
        }

        return response()->json(["result" => "Data tidak ditemukan."]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        DB::beginTransaction();

        if (!empty($request->id)) {
            try {
                $sparepart = Sparepart::find($request->id);
                $sparepart->active = false;
                $sparepart->save();

                $user = Auth::user();
                $historyDeleteSparepart["type_menu"] = "Sparepart";
                $historyDeleteSparepart["method"] = "Delete";
                $historyDeleteSparepart["meta"] = json_encode(
                    [
                        "user" => $user["id"],
                        "createdAt" => date("Y-m-d H:i:s"),
                        "dataChange" => $sparepart->getChanges(),
                    ],
                    JSON_THROW_ON_ERROR
                );

                $historyDeleteSparepart["user_id"] = $user["id"];
                $historyDeleteSparepart["menu_id"] = $request->id;
                HistoryUpdate::create($historyDeleteSparepart);

                DB::commit();

                return redirect()->route("list_sparepart")
                    ->with("success", "Data berhasil dihapus!");
            } catch (\Exception $e) {
                DB::rollback();

                return response()->json([
                    "error" => $e,
                ]);
            }
        }

        return response()->json(["result" => "Data tidak ditemukan."]);
    }
}
