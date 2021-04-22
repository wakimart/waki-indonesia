<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    protected $fillable = [
        "code",
        "no_member",
        "branch_id",
        "cso_id",
        "name",
        "address",
        "phone",
        "province",
        "city",
        "district",
        "type",
        "active",
    ];

    public function branch()
    {
        return $this->belongsTo("App\Branch");
    }

    public function cso()
    {
        return $this->belongsTo("App\Cso");
    }

    public function province()
    {
        return $this->belongsTo("App\RajaOngkir_Province", "province");
    }

    public function city()
    {
        return $this->belongsTo("App\RajaOngkir_City", "city");
    }

    public function district()
    {
        return $this->belongsTo("App\RajaOngkir_Subdistrict", "district");
    }
}
