<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\HomeService;

class ReferenceSouvenir extends Model
{
    protected $fillable = [
        "reference_id",
        "souvenir_id",
        "link_hs",
        "status",
        "delivery_status_souvenir",
        "order_id",
        "prize_id",
        "status_prize",
        "delivery_status_prize",
        "is_acc",
        "final_status",
        "wakimart_link"
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

    public function fetch_hs()
    {
        if($this->link_hs != null){
            $result = HomeService::whereIn('id', json_decode($this->link_hs, true))->get();
            return $result;
        }
        return null;
    }
}
