<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserGeolocation extends Model
{
    protected $fillable = [
        "user_id",
        "date",
        "filename",
    ];

    public function user()
    {
        return $this->belongsTo("App\User");
    }
}
