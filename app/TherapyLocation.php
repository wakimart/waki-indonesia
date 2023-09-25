<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TherapyLocation extends Model
{
    protected $fillable = ['name', 'branch_id', 'province_id', 'city_id', 'subdistrict_id', 'address'];

    public function subdistrictCityProvince()
    {
        return $this->belongsTo('App\RajaOngkir_Subdistrict', 'subdistrict_id', 'subdistrict_id');
    }
}
