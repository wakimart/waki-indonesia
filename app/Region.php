<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $fillable = [
        "name", "bg_color"
    ];

    public function geometryDistrict()
    {
        return $this->hasMany('App\GeometryDistrict');
    }

    public function branch()
    {
        return $this->hasMany('App\Branches');
    }
}
