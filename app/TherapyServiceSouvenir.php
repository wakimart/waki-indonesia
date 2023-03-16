<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TherapyServiceSouvenir extends Model
{
    public function souvenir(){
        return $this->belongsTo('App\Souvenir');
    }

    public function user(){
        return $this->belongsTo('App\User');
    }
}
