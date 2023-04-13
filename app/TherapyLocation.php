<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TherapyLocation extends Model
{
    protected $fillable = ['name', 'branch_id', 'province_id', 'city_id', 'subdistrict_id', 'address'];
}
