<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserGeolocation extends Model
{
    protected $fillable = [
        "user_id",
        "presense_image",
        "date",
        "filename",
    ];

    protected $casts = [
        "presense_image" => "array",
    ];

    public function user()
    {
        return $this->belongsTo("App\User");
    }
}
