<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserGeolocation extends Model
{
    protected $fillable = [
        "user_id",
        "presence_image",
        "date",
        "filename",
        "device_id",
    ];

    protected $casts = [
        "presence_image" => "array",
    ];

    public function user()
    {
        return $this->belongsTo("App\User");
    }
}
