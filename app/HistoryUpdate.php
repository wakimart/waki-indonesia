<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HistoryUpdate extends Model
{
     protected $fillable = [
        'method', 'meta', 'user_id', 'type_menu', 'menu_id',
    ];

    protected $casts = [
        'meta' => 'json',
    ];
    public function user(){
        return $this->belongsTo('App\User');
    }
}
