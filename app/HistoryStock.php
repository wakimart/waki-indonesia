<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HistoryStock extends Model
{
    protected $fillable = [
        'stock_id',
        "code",
        "date",
        'type',
        'quantity',
        "description",
    ];

    public function stock()
    {
        return $this->belongsTo('App\Stock');
    }
}
