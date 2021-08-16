<?php

namespace App\Http\Controllers;

use App\Branch;
use App\Cso;
use App\RajaOngkir_Province;
use Illuminate\Http\Request;

class PersonalHomecareController extends Controller
{
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
}
