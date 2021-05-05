<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Souvenir extends Model
{
    protected $fillable = [
        'name',
        'active',
    ];
}
