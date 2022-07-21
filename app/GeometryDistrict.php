<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GeometryDistrict extends Model
{
    protected $table = 'geometry_districts';
    protected $fillable = [
        'district', 'region_od', 'geom'
    ];

    public function getDistrict()
    {
        $district = RajaOngkir_Subdistrict::where('subdistrict_id', $this->distric)->first();
        if ($district != null) {
            $district['type_city'] = RajaOngkir_City::where('city_id', $district['city_id'])->first()['type'];
            $district['kota_kab'] = $district['type_city'].' '.$district['city']; 
        }
        return $district;
    }
    
    public function districObj()
    {
        return $this->belongsTo('App\RajaOngkir_Subdistrict', 'distric', 'subdistrict_id');
    }

    public function region()
    {
        return $this->belongsTo('App\Region');
    }
}
