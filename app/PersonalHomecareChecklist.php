<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PersonalHomecareChecklist extends Model
{
    protected $fillable = [
        "condition",
        "image",
    ];

    protected $casts = [
        "condition" => "array",
        "image" => "array",
    ];

    public function personalHomecareOut()
    {
        return $this->hasMany("App\PersonalHomecare", "checklist_out");
    }

    public function personalHomecareIn()
    {
        return $this->hasMany("App\PersonalHomecare", "checklist_in");
    }
}
