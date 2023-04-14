<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TheraphyService extends Model
{
    protected $fillable = [
    	'code','registered_date','name','phone','province_id','city_id','subdistrict_id','address','email_facebook','meta_condition','status','active', 'branch_id', 'type', 'expired_date', 'therapy_location_id', 'request',
    ];

    protected $casts = [
        'meta_condition' => 'json',
    ];

    public static $meta_default = [
    	'Adakah anggota keluarga yang mengikuti WAKi High Potential Terapi',
    	'Orang tua / Mertua tinggal serumah',
    	'Pekerjaan Anda di kantor / luar kantor',
    	'Apakah anda rutin berolahraga',
    	'Apakah anda sering ke pegunungan',
    	'Adakah masalah kesehatan : ',
		'Darah Tinggi',
		'Kencing Manis',
		'Kolesterol',
		'Asam Urat',
		'Susah Tidur',
		'Susah Buang Air Besar'
    ];

    public function theraphySignIn()
    {
        return $this->hasMany('App\TheraphySignIn');
    }

    public function branch()
    {
        return $this->belongsTo('App\Branch');
    }

    public function subdistrictCityProvince()
    {
        return $this->belongsTo('App\RajaOngkir_Subdistrict', 'subdistrict_id', 'subdistrict_id');
    }

    public function therapyLocation()
    {
        return $this->belongsTo('App\TherapyLocation');
    }

    public function therapyServiceSouvenir()
    {
        return $this->hasMany('App\TherapyServiceSouvenir', 'therapy_service_id');
    }
}
