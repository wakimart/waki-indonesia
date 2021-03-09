<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RajaOngkir_Province extends Model
{
    protected $table = 'raja_ongkir__provinces';
    protected $fillable = [
        'province_id', 'province',
    ];

    public function city()
    {
        return $this->hasMany('App\RajaOngkir_City', 'province_id', 'province_id');
    }
    public function subdistrict()
    {
        return $this->hasMany('App\RajaOngkir_Subdistrict', 'province_id', 'province_id');
    }

}
