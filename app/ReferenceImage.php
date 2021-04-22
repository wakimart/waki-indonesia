<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReferenceImage extends Model
{
    protected $fillable = [
        "reference_id",
        "image_1",
        "image_2",
        "active",
    ];

    public function reference()
    {
        return $this->belongsTo("App\Reference");
    }
}
