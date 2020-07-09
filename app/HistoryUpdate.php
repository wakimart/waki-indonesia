<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HistoryUpdate extends Model
{
     protected $fillable = [
        'method', 'meta', 'user_id', 'type'
    ];

    public function user(){
        return $this->hasOne('App\User');
    }
}
