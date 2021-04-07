<?php

namespace App\Http\Controllers;

use App\Reference;
use Exception;
use Illuminate\Http\Request;

class ReferenceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Menyimpan request ke dalam variabel $url untuk pagination
        $url = $request->all();

        // Query dari tabel references, dan menampilkan 10 data per halaman
        $references = Reference::all()->paginate(10);

        return view("admin.list_reference", compact("references", "url"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listApi(Request $request)
    {
        try {
            // Menyimpan request ke dalam variabel $url untuk pagination
            $url = $request->all();

            // Query dari tabel references, dan menampilkan 10 data per halaman
            $references = Reference::select(
                "references.name AS name",
                "references.age AS age",
                "references.phone AS phone",
                "raja_ongkir__cities.province AS province",
                "raja_ongkir__cities.type AS type",
                "raja_ongkir__cities.city_name AS city_name",
                "souvenirs.name AS souvenir",
                "references.link_hs AS link_hs",
                "references.status AS status",
            )
            ->leftJoin(
                "raja_ongkir__cities",
                "raja_ongkir__cities.city_id",
                "=",
                "references.city"
            )
            ->leftJoin(
                "souvenirs",
                "souvenirs.id",
                "=",
                "references.souvenir_id"
            )
            ->paginate(10);

            $i = 0;
            foreach ($references as $refItem) {
                $references[$i]->city = $refItem->type . " " . $refItem->city_name;

                unset(
                    $references[$i]->type,
                    $references[$i]->city_name,
                );

                $i++;
            }

            $data = [
                "result" => 1,
                "data" => $references,
                "url" => $url,
            ];

            return response()->json($data, 200);
        } catch (Exception $e) {
            $data = [
                "result" => 0,
                "data" => $e->getMessage(),
            ];

            return response()->json($data, 401);
        }
    }
}
