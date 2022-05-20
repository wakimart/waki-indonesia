<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TechnicianSchedule extends Model
{
    protected $fillable = [
        'appointment', 'name', 'phone', 'address', 'province', 'city', 'district', 'product_id', 'issues', 'technician_id', 'd_o', 'active', 'home_service_id',
    ];

    public function provinceObj()
    {
        return $this->belongsTo('App\RajaOngkir_Province', 'province', 'province_id');
    }
    public function cityObj()
    {
        return $this->belongsTo('App\RajaOngkir_City', 'city', 'city_id');
    }
    public function districObj()
    {
        return $this->belongsTo('App\RajaOngkir_Subdistrict', 'district', 'subdistrict_id');
    }

    public function cso()
    {
        return $this->belongsTo('App\Cso', 'technician_id');
    }

    public function homeService()
    {
        return $this->belongsTo('App\HomeService');
    }

    public function product_technician_schedule()
    {
        return $this->hasMany('App\ProductTechnicianSchedule');
    }

    public function product_technician_schedule_withProduct()
    {
        return $this->hasMany('App\ProductTechnicianSchedule')
            ->where('product_technician_schedules.active', 1)->with('product');
    }

    public function service()
    {
        return $this->hasOne('App\Service');
    }
}
