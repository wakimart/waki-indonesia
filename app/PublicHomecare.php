<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PublicHomecare extends Model
{
    protected $fillable = [
        "status", "start_date", "end_date", "name",
        "phone", "address", "province_id",
        "city_id", "district_id", "branch_id",
        "cso_id", "cso_optional_id", "approval_letter",
        "other_product", "active",
    ];

    public function branch()
    {
        return $this->belongsTo('App\Branch');
    }

    public function cso()
    {
        return $this->belongsTo('App\Cso');
    }

    public function csoOptional()
    {
        return $this->belongsTo('App\Cso', 'cso_optional_id', 'id');
    }

    public function getProvinceName()
    {
        return RajaOngkir_Province::where("province_id", $this->province_id)->first()->province;
    }

    public function getCityFullName()
    {
        $queryCity = RajaOngkir_City::where("city_id", $this->city_id)->first();

        return $queryCity->type . " " . $queryCity->city_name;
    }

    public function getDistrictName()
    {
        return RajaOngkir_Subdistrict::where("subdistrict_id", $this->district_id)->first()->subdistrict_name;
    }

    public function publicHomecareProduct()
    {
        return $this->hasMany('App\PublicHomecareProduct', 'public_homecare_id', 'id');
    }
}
