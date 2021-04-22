<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReferenceSouvenir extends Model
{
    protected $fillable = [
        "reference_id",
        "souvenir_id",
        "link_hs",
        "status",
    ];

    public function reference()
    {
        return $this->belongsTo("App\Reference");
    }

    public function souvenir()
    {
        return $this->belongsTo("App\Souvenir");
    }
}
