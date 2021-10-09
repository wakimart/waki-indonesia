<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PersonalHomecare extends Model
{
    protected $fillable = [
        "status",
        "schedule",
        "name",
        "phone",
        "address",
        "province_id",
        "city_id",
        "subdistrict_id",
        "branch_id",
        "cso_id",
        "id_card",
        "member_wakimart",
        "ph_product_id",
        "checklist_out",
        "checklist_in",
        "active",
        "is_extend",
        "reschedule_date",
        "extend_reason",
        "reschedule_reason",
    ];

    public function branch()
    {
        return $this->belongsTo('App\Branch');
    }

    public function cso()
    {
        return $this->belongsTo('App\Cso');
    }

    public function personalHomecareProduct()
    {
        return $this->belongsTo('App\PersonalHomecareProduct', 'ph_product_id');
    }

    public function checklistOut()
    {
        return $this->belongsTo("App\PersonalHomecareChecklist", "checklist_out");
    }

    public function checklistIn()
    {
        return $this->belongsTo("App\PersonalHomecareChecklist", "checklist_in");
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
        return RajaOngkir_Subdistrict::where("subdistrict_id", $this->subdistrict_id)->first()->subdistrict_name;
    }
}
