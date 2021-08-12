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
        "ph_product_id",
        "checklist_out",
        "checklist_in",
        "active",
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
}
