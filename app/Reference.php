<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reference extends Model
{
    protected $fillable = [
        "submission_id",
        "name",
        "age",
        "phone",
        "province",
        "city",
        "active",
        'online_signature'
    ];

    public function submission()
    {
        return $this->belongsTo("App\Submission");
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
    public function reference_souvenir()
    {
        return $this->hasOne('App\ReferenceSouvenir');
    }
}
