<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HomeService extends Model
{
     protected $fillable = [
        'code', 'no_member', 'name', 'address', 'phone', 'city', 'cso_id', 'branch_id', 'cso_phone', 'appointment', 'cso2_id', 'active', 'cash', 'cash_description', 'description', 'type_customer', 'type_homeservices', 'distric', 'province',
    ];

    public function cso()
    {
        return $this->belongsTo('App\Cso');
    }
    public function cso2()
    {
        return $this->belongsTo('App\Cso');
    }
    public function branch()
    {
        return $this->belongsTo('App\Branch');
    }
    public function historyUpdate()
    {
        return HistoryUpdate::where([['type_menu', 'Home Service'], ['menu_id', $this['id']]])->orderBy('id', 'DESC')->first();
    }
    public function getDistrict()
    {
        $district = RajaOngkir_Subdistrict::where('subdistrict_id', $this->distric)->first();
        if ($district != null) {
            $district['type_city'] = RajaOngkir_City::where('city_id', $district['city_id'])->first()['type'];
            $district['kota_kab'] = $district['type_city'].' '.$district['city']; 
        }
        return $district;
    }
}
