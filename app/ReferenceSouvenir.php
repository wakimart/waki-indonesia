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
        "order_id",
        "prize_id",
        "delivery_status",
    ];

    public function reference()
    {
        return $this->belongsTo("App\Reference");
    }

    public function souvenir()
    {
        return $this->belongsTo("App\Souvenir");
    }

    public function order()
    {
        return $this->belongsTo("App\Order");
    }

    public function prize()
    {
        return $this->belongsTo("App\Prize");
    }
}
