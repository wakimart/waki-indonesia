<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RegistrationPromotion extends Model
{
    protected $fillable = [
        'first_name', 'last_name', 'address', 'province_id', 'district_id', 'subdistrict_id', 'email', 'phone','active',
    ];

    public function province()
    {
        return $this->belongsTo('App\RajaOngkir_Province', 'province_id', 'province_id');
    }

    public function district()
    {
        return $this->belongsTo('App\RajaOngkir_City', 'district_id', 'city_id');
    }

    public function subdistrict()
    {
        return $this->belongsTo('App\RajaOngkir_Subdistrict');
    }
}
