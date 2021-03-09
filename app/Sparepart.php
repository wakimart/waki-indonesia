<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sparepart extends Model
{
    protected $fillable = [
        'name', 'price', 'active',
    ];
}
