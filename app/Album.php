<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    protected $fillable = [
        "event_id",
        "arr_photo",
        "active",
        "url_video",
    ];

    protected $casts = [
        'arr_photo' => 'json',
        'url_video' => 'json',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, "event_id");
    }
}
