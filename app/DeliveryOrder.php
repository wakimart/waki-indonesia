<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeliveryOrder extends Model
{
	static $Promo = [ '1'=> ['code'=>'PRW10001I', 'name'=>'Promo Bedsheet + Bedsheet'], 
						'2'=> ['code'=>'PRW10002I', 'name'=>'Promo Eco Disinfectant + Eco Disinfectant'],
						'3'=> ['code'=>'PRW10003I', 'name'=>'Promo Far Infrared Medical Lamp + Pen Accupunture'],
						'4'=> ['code'=>'PRW10004I', 'name'=>'Promo Bio Energy Water System + Hand Blender + Pen Accupunture'],
						];

    protected $fillable = [
        'code', 'no_member', 'name', 'address', 'phone', 'arr_product', 'cso_code',
    ];
}
