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
        "status",
    ];

    public function branch()
    {
        return $this->belongsTo("App\Branch");
    }

    public function cso()
    {
        return $this->belongsTo("App\Cso");
    }

    public function province_obj()
    {
        return $this->belongsTo("App\RajaOngkir_Province", "province", "province_id");
    }

    public function city_obj()
    {
        return $this->belongsTo("App\RajaOngkir_City", "city", "city_id");
    }

    public function district_obj()
    {
        return $this->belongsTo("App\RajaOngkir_Subdistrict", "district", "subdistrict_id");
    }

    public function submissionDeliveryorder()
    {
        return $this->hasOne("App\SubmissionDeliveryorder");
    }    
    public function reference()
    {
        return $this->hasMany('App\Reference')->where('active', true);
    }
}
