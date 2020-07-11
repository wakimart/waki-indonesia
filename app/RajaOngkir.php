<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class RajaOngkir extends Model
{
    static public function FetchProvince()
    {
        $data = [];
        $data['rajaongkir'] = [];
    	$data['rajaongkir']['results'] = RajaOngkir_Province::all();
    	return $data;
    }

    static public function FetchCity($province){
        $data = [];
        $data['rajaongkir'] = [];
    	$data['rajaongkir']['results'] = RajaOngkir_City::where('province_id', $province)->get();
    	return $data;
    }

    static public function FetchProvinceApi()
    {
        $data = RajaOngkir_Province::all();

        $data = ['result' => 1,
                     'data' => $data
                    ];
            return response()->json($data, 200);
    }

    static public function FetchCityApi($province){
        $data = RajaOngkir_City::where([['province_id', $province], ['type', 'Kota']])->select(DB::raw('CONCAT(type, " ", city_name) AS city_name'))->get();
        
        $data = ['result' => 1,
                     'data' => $data
                    ];
            return response()->json($data, 200);
    }
}
