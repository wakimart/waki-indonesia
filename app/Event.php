<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        "name",
        "active",
    ];

    public function album()
    {
        return $this->hasMany('App\Album');
    }
}
