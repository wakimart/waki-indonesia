<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DataSourcing extends Model
{
    protected $fillable = [
        'name', 'branch_id', 'cso_id', 'phone', 'address', 'type_customer_id', 'user_id', 'active'
    ];

    public function branch()
    {
        return $this->belongsTo('App\Branch');
    }

    public function cso()
    {
        return $this->belongsTo('App\Cso');
    }

    public function type_customer()
    {
        return $this->belongsTo('App\TypeCustomer');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
