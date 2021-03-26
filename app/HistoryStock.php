<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HistoryStock extends Model
{
    protected $fillable = [
        'upgrade_id', 'stock_id', 'type_warehouse', 'quantity',
    ];

    public function upgrade()
    {
        return $this->belongsTo('App\Upgrade');
    }

    public function stock()
    {
        return $this->belongsTo('App\Stock');
    }
}
