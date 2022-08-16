<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TypeCustomer extends Model
{
    protected $fillable = [
        'name', 'active', 'created_at', 'updated_at',
    ];

    public function data_sourcing()
    {
        return $this->hasMany('App\DataSourcing');
    }

    public function data_therapy()
    {
        return $this->hasMany('App\DataTherapy');
    }
}
