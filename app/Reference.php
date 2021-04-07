<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reference extends Model
{
    protected $fillable = [
        "name",
        "age",
        "phone",
        "province",
        "city",
        "deliveryorder_id",
        "souvenir_id",
        "link_hs",
        "status"
    ];

    public function deliveryorder()
    {
        return $this->belongsTo("App\DeliveryOrder");
    }

    public function souvenir()
    {
        return $this->belongsTo("App\Souvenir");
    }

    public function getCityName()
    {
        return RajaOngkir_City::where("city_id", $this->city)->first()->city_name;
    }

    public function getCityFullName()
    {
        $queryCity = RajaOngkir_City::where("city_id", $this->city)->first();

        return $queryCity->type . " " . $queryCity->city_name;
    }
}
