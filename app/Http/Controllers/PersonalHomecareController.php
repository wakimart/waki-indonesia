<?php

namespace App\Http\Controllers;

use App\PersonalHomecare;
use Exception;
use App\Branch;
use App\Cso;
use App\RajaOngkir_Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PersonalHomecareController extends Controller
{
    public function index()
    {
        $personalhomecares = PersonalHomecare::where('active', true)
            ->paginate(10);

        return view("admin.list_all_personalhomecare", compact("personalhomecares"))
            ->with('i', (request()->input('page', 1) - 1) * 10);
    }

    public function create()
    {
        $branches = Branch::select("id", "code", "name")
            ->where("active", true)
            ->orderBy("code")
            ->get();

        $csos = Cso::select("id", "code", "name")
            ->where("active", true)
            ->get();

        $provinces = RajaOngkir_Province::select(
                "province_id AS id",
                "province AS name",
            )
            ->get();

        // TODO: PERSONAL HOMECARE PRODUCTS

        return view("admin.add_personal_homecare", compact(
            "branches",
            "csos",
            "provinces",
        ));
    }

    public function store(Request $request)
    {
        dd($request->all());
    }

    public function edit(Request $request)
    {
        if (empty($request->id)) {
            return redirect()
                ->route("list_all_phc")
                ->with("danger", "Data not found.");
        }

        $branches = Branch::select("id", "code", "name")
            ->where("active", true)
            ->get();

        $csos = Cso::select("id", "code", "name")
            ->where("active", true)
            ->get();

        $provinces = RajaOngkir_Province::select(
                "province_id AS id",
                "province AS name",
            )
            ->get();

        $personalhomecare = PersonalHomecare::where("id", $request->id)->first();

        return view("admin.update_personal_homecare", compact(
            "personalhomecare", 
            "branches",
            "csos",
            "provinces",
        ));
    }

    public function detail(Request $request){
        $personalhomecare = PersonalHomecare::where("id", $request->id)->first();
        
        return view('admin.detail_personal_homecare', compact(
            'personalhomecare',
        ));
    }
}
