<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HomeService extends Model
{
     protected $fillable = [
        'code', 'no_member', 'name', 'address', 'phone', 'city', 'cso_id', 'branch_id', 'cso_phone', 'appointment',  
    ];

    public function cso()
    {
        return $this->belongsTo('App\Cso');
    }
    public function branch()
    {
        return $this->belongsTo('App\Branch');
    }
}
