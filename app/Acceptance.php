<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Acceptance extends Model
{
    protected $table = 'acceptance';
    protected $fillable = [
        'name', 'code', 'description',  'cso_id', 'branch_id', 'order_id', 'active', 'user_id'
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
