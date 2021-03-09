<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductService extends Model
{
    protected $fillable = [
        'service_id', 'sparepart_id', 'product_id', 'due_date', 'issues',
    ];

    public function service()
    {
        return $this->belongsTo('App\Service');
    }

    public function sparepart()
    {
        return $this->belongsTo('App\Sparepart');
    }

    public function product()
    {
        return $this->belongsTo('App\Product');
    }
}
