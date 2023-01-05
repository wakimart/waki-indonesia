<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Region;

class Branch extends Model
{
    protected $fillable = [
        'code', 'name',  'active', 'color', 'region_id'
    ];

    protected $casts = [
        'region_id' => 'json',
    ];

    public function cso()
    {
        return $this->hasMany('App\Cso');
    }

    public function deliveryOrder()
    {
        return $this->hasMany('App\DeliveryOrder');
    }

    public function order()
    {
        return $this->hasMany('App\Order');
    }

    public function personalHomecare()
    {
        return $this->hasMany('App\PersonalHomecare');
    }

    public function personalHomecareProduct()
    {
        return $this->hasMany('App\PersonalHomecareProduct');
    }

    public function region()
    {
        return $this->region_id ? Region::whereIn('id', $this->region_id)->get() : null;
    }

    public function regionDistrict()
    {
        $allRegion = Region::whereIn('id', $this->region_id)->get();
        $districtList = [];
        $cityNya = "";
        $cityTypeNya = "";
        $provinceNya = "";

        foreach ($allRegion as $regionNya) {
            $geoNya = $regionNya->hasMany('App\GeometryDistrict')->get();
            foreach ($geoNya as $key => $value) {
                array_push($districtList, $value['distric']);
                $cityNya = $value->getDistrict()['city_id'];
                $provinceNya = $value->getDistrict()['province_id'];
                $cityTypeNya = $value->getDistrict()['type_city'];
            }
        }
        
        return ['district' => $districtList, 'province' => $provinceNya, 'city' => $cityNya, 'cityType' => $cityTypeNya];
    }
}
