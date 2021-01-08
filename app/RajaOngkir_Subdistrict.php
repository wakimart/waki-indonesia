<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RajaOngkir_Subdistrict extends Model
{
    protected $fillable = [
		'subdistrict_id', 'province_id', 'province', 'city_id', 'city', 'type', 'subdistrict_name',
    ];

    public function province()
    {
        return $this->belongsTo('App\RajaOngkir_Province', 'province_id', 'province_id');
    }
    public function city()
    {
        return $this->belongsTo('App\RajaOngkir_City', 'city_id', 'city_id');
    }
}
