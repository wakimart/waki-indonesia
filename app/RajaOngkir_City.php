<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RajaOngkir_City extends Model
{
    protected $table = 'raja_ongkir__cities';
    protected $fillable = [
		'city_id','province_id','province','type','city_name','postal_code',
    ];

    public function province()
    {
        return $this->belongsTo('App\RajaOngkir_Province', 'province_id', 'province_id');
    }
    public function subdistrict()
    {
        return $this->hasMany('App\RajaOngkir_Subdistrict', 'city_id', 'city_id');
    }
}
