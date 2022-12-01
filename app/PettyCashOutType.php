<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PettyCashOutType extends Model
{
    protected $fillable = [
        'code', 'name', 'max',
    ];
}
