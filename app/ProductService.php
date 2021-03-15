<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductService extends Model
{
    protected $fillable = [
        'service_id', 'product_id', 'sparepart', 'due_date', 'issues',
    ];

    public function service()
    {
        return $this->belongsTo('App\Service');
    }

    public function product()
    {
        return $this->belongsTo('App\Product');
    }
}
