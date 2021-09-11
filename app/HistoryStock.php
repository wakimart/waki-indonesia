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
        'upgrade_id',
        'type_warehouse',
    ];

    public function stock()
    {
        return $this->belongsTo('App\Stock');
    }
}
