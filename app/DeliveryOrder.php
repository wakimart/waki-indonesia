<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeliveryOrder extends Model
{
	static $Promo = [ '1'=> ['code'=>'PRW10001I', 'name'=>'Promo Bedsheet + Bedsheet', 'harga'=> 'Rp. 4.990.000'], 
						'2'=> ['code'=>'PRW10002I', 'name'=>'Promo Eco Disinfectant + Eco Disinfectant', 'harga'=> 'Rp. 3.200.000'],
						'3'=> ['code'=>'PRW10003I', 'name'=>'Promo Far Infrared Medical Lamp + Pen Accupunture', 'harga'=> 'Rp. 2.199.000'],
						'4'=> ['code'=>'PRW10004I', 'name'=>'Promo Bio Energy Water System + Hand Blender + Pen Accupunture', 'harga'=> '4.990.000'],
						'5'=> ['code'=>'PW10005I', 'name'=>'Promo High Potential Therapy', 'harga'=> 'Rp. 21.900.000'],
						'6'=> ['code'=>'PW10006I', 'name'=>'Promo Electro', 'harga'=> '-'],
						'7'=> ['code'=>'PW10007I', 'name'=>'Promo HEPA', 'harga'=> '-'],
						];

    static $type_register = ['Normal Register', "MGM", "Refrensi", "Take Away"];

    protected $fillable = [
        'code', 'no_member', 'name', 'address', 'phone', 'arr_product', 'cso_id', 'branch_id', 'city',  'active', 'distric', 'province',
    ];

    public function cso()
    {
        return $this->belongsTo('App\Cso');
    }
    public function branch()
    {
        return $this->belongsTo('App\Branch');
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
