<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Acceptance extends Model
{
    protected $fillable = [
        'name', 'description',  'cso_id', 'branch_id', 'order_id', 'active',
    ];

    public function cso()
    {
        return $this->belongsTo('App\Cso');
    }
    public function order()
    {
        return $this->belongsTo('App\Order');
    }
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    public function branch()
    {
        return $this->belongsTo('App\Branch');
    }
}
