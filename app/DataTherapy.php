<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DataTherapy extends Model
{
    protected $fillable = [
        'name', 'no_ktp', 'img_ktp', 'branch_id', 'cso_id', 'phone', 'address', 'type_customer_id', 'user_id', 'active', 'created_at', 'updated_at'
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
