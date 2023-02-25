<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TheraphySignIn extends Model
{
    protected $fillable = [
    	'theraphy_service_id', 'therapy_date', 'user_id'
    ];

    public function theraphyService()
    {
        return $this->belongsTo('App\TheraphyService');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
