<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
}
