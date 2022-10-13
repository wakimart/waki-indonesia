<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    public static $Type = ['warehouse', 'branch', 'vendor'];
    
    protected $fillable = [
        "code",
        "name",
        "address",
        "province_id",
        "city_id",
        "subdistrict_id",
        "description",
        "parent_warehouse_id",
        "active",
        "type",
    ];

    public function parentWarehouse()
    {
        return $this->belongsTo("App\Warehouse", "parent_warehouse_id", "id");
    }

    public function stock()
    {
        return $this->hasMany("App\Stock");
    }

    public function getProvinceName()
    {
        return RajaOngkir_Province::where("province_id", $this->province_id)
            ->first()
            ->province;
    }

    public function getCityFullName()
    {
        $city = RajaOngkir_City::where("city_id", $this->city_id)->first();

        return $city['type'] . " " . $city['city_name'];
    }

    public function getDistrictName()
    {
        return RajaOngkir_Subdistrict::where("subdistrict_id", $this->subdistrict_id)
            ->first()
            ->subdistrict_name;
    }

    public function stockWithProduct($product_id)
    {
        if(!empty($product_id)){
            return $this->hasMany("App\Stock")->where('product_id', $product_id)->get();
        }
        return $this->hasMany("App\Stock")->get();
    }
}
