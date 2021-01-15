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
    static public function FetchDistrictAPI($city){
        $data = [];
        $data['rajaongkir'] = [];
    	$data['rajaongkir']['results'] = RajaOngkir_Subdistrict::where('city_id', $city)->get();
    	return $data;
    }
    static public function FetchDistrict($city){
        $data = [];
        $data['rajaongkir'] = [];
    	$data['rajaongkir']['results'] = RajaOngkir_Subdistrict::where('city_id',$city)->get();
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
        $data = RajaOngkir_City::where('province_id', $province)->select(DB::raw('CONCAT(type, " ", city_name) AS city_name'))->get();
        
        $data = ['result' => 1,
                     'data' => $data
                    ];
            return response()->json($data, 200);
    }

    static public function FetchAllCityApi($province){
        $data = RajaOngkir_City::where('province_id', $province)->get();
        
        $data = ['result' => 1,
                     'data' => $data
                    ];
            return response()->json($data, 200);
    }
    static public function FetchAllDistrictApi($city){
        $data = RajaOngkir_Subdistrict::where('city_id', $city)->get();
        
        $data = ['result' => 1,
                     'data' => $data
                    ];
            return response()->json($data, 200);
    }

    static public function FetchProvinceByCity($city_name){
        $type_city = explode(" ", $city_name)[0];
        $city_name = substr($city_name, strlen($type_city)+1);
        $provinceNya = RajaOngkir_City::where([['city_name', $city_name], ['type', $type_city]])->first()->provinceNya;

        return $provinceNya;
    }
}
