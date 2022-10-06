<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PublicHomecareProduct extends Model
{
    protected $fillable = [
        'public_homecare_id', 'ph_product_id',
        'checklist_out_id', 'checklist_in_id',
    ];

    public function publicHomecare()
    {
        return $this->belongsTo('App\PublicHomecare', 'public_homecare_id', 'id');   
    }

    public function personalHomecareProduct()
    {
        return $this->belongsTo('App\PersonalHomecareProduct', 'ph_product_id');
    }

    public function checklistOut()
    {
        return $this->belongsTo("App\PersonalHomecareChecklist", "checklist_out_id");
    }

    public function checklistIn()
    {
        return $this->belongsTo("App\PersonalHomecareChecklist", "checklist_in_id");
    }
}
