<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HistoryStock extends Model
{
    protected $fillable = [
        'stock_from_id',
        'stock_to_id',
        "code",
        "date",
        'type',
        'quantity',
        "description",
        'upgrade_id',
        'type_warehouse',
        'koli',
        'user_id',
    ];

    public function stockFrom()
    {
        return $this->belongsTo('App\Stock', 'stock_from_id', 'id');
    }

    public function stockTo()
    {
        return $this->belongsTo('App\Stock', 'stock_to_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
