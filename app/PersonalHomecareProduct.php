<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PersonalHomecareProduct extends Model
{
    protected $fillable = [
        "code",
        "branch_id",
        "product_id",
        "status",
        "active",
    ];

    public function branch()
    {
        return $this->belongsTo("App\Branch");
    }

    public function product()
    {
        return $this->belongsTo("App\Product");
    }

    public function personalHomecare()
    {
        return $this->hasMany("App\PersonalHomecare", "ph_product_id");
    }
}
