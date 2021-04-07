<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeliveryOrder extends Model
{
	static $Promo = [ '1'=> ['code'=>'NewYear_2021_1', 'name'=>'Promo WAKI ION AIR HUMIDIFIER + WAKI BIO 8001', 'harga'=> 'Rp. 11.900.000'], 
                        '2'=> ['code'=>'NewYear_2021_2', 'name'=>'Promo WAKI ION AIR HUMIDIFIER + WAKI SHIATSU MASSAGER', 'harga'=> 'Rp. 11.900.000'],
                        '3'=> ['code'=>'NewYear_2021_3', 'name'=>'Promo WAKI ELECTRO MASSAGER + WAKI SUPER SHIATSU MASSAGER', 'harga'=> 'Rp. 29.900.000'],
                        '4'=> ['code'=>'NewYear_2021_4', 'name'=>'Promo WAKI HEPA POWER AIR PURIFIER + WAKI DISHWASHER (WKE1016)', 'harga'=> 'Rp. 19.900.000'],
                        '5'=> ['code'=>'NewYear_2021_5', 'name'=>'Promo WAKI HPT 2079B + WAKI HEPA POWER AIR PURIFIER', 'harga'=> 'Rp. 21.900.000'],
                        '6'=> ['code'=>'NewYear_2021_6', 'name'=>'Promo WAKI RO + WAKI HEPA POWER AIR PURIFIER', 'harga'=> 'Rp. 25.900.000'],
                        '7'=> ['code'=>'NewYear_2021_7', 'name'=>'Promo WAKI BIO 8001 + WAKI BUTTERFLY MASSAGE', 'harga'=> 'Rp. 14.900.000'],
                        '8'=> ['code'=>'NewYear_2021_8', 'name'=>'Promo WAKI ULTRA ELECTRO MASSAGER + WAKI HPT CHAIR', 'harga'=> 'Rp. 59.900.000'],
                        '9'=> ['code'=>'NewYear_2021_9', 'name'=>'Promo WAKI HPT 2079B + WAKI RO', 'harga'=> 'Rp. 21.900.000'],
                        '10'=> ['code'=>'NewYear_2021_10', 'name'=>'Promo WAKI ELECTRO MASSAGER + WAKI HEPA POWER AIR PURIFIER', 'harga'=> 'Rp. 29.900.000'],
                        '11'=> ['code'=>'NewYear_2021_11', 'name'=>'Promo WAKI HEPA POWER AIR PURIFIER + WAKI RO', 'harga'=> 'Rp. 25.900.000'],
                        '12'=> ['code'=>'NewYear_2021_12', 'name'=>'Promo WAKI ELECTRO MASSAGER + WAKI HPT 2075', 'harga'=> 'Rp. 7.000.000'],
                        '13'=> ['code'=>'NewYear_2021_13', 'name'=>'Promo WAKI HEPA POWER AIR PURIFIER + WAKI HEPA POWER AIR PURIFIER', 'harga'=> 'Rp. 19.900.000 '],
                        '14'=> ['code'=>'NewYear_2021_14', 'name'=>'Promo WAKI ELECTRO LASSER + WAKI SUPER HEPA', 'harga'=> 'Rp. 33.900.000'],
                        '15'=> ['code'=>'NewYear_2021_15', 'name'=>'Promo WAKI ELECTRO LASSER + WAKI HEPA POWER AIR PURIFIER', 'harga'=> 'Rp. 33.900.000'],
                        '16'=> ['code'=>'NewYear_2021_16', 'name'=>'Promo WAKI ELECTRO MASSAGER + WAKI RO', 'harga'=> 'Rp. 29.900.000'],
                        '17'=> ['code'=>'PRW10008', 'name'=>'WAKI BIO ENERGY WATER SYSTEM + WAKI ECO DINSFECTANT', 'harga'=> 'Rp. 4.990.000'],
                        '18'=> ['code'=>'PRW10009', 'name'=>'WAKI NIFIR BEDSHEET + WAKI BIO ENERGY WATER SYSTEM', 'harga'=> 'Rp. 4.900.000'],
                        '19'=> ['code'=>'PRW10010I', 'name'=>'WAKI FAR INFRARED MEDICAL LAMP + WAKI NIFIR HEALTH PEN', 'harga'=> 'Rp. 2.199.000'],
                        '20'=> ['code'=>'PRW10011I', 'name'=>'WAKI HYDROGEN WATER GENERATOR + WAKI FAR INFRARED MEDICAL LAMP', 'harga'=> 'Rp. 3.100.000'],
                        '21'=> ['code'=>'PRW10012I', 'name'=>'WAKI ECO DINSFECTANT + WAKI HAND BLENDER', 'harga'=> 'Rp. 3.200.000'],
                        '22'=> ['code'=>'PRW10013', 'name'=>'WAKI ECO DINSFECTANT + WAKI HYDROGEN WATER GENERATOR ', 'harga'=> 'Rp. 3.200.000'],
                        '23'=> ['code'=>'PRW10014I', 'name'=>'WAKI NIFIR BEDSHEET + WAKI NIFIR BEDSHEET', 'harga'=> 'Rp. 4.990.000']
                        ];
    static $type_register = ['Normal Register', "MGM", "Refrensi", "Take Away"];

    protected $fillable = [
        'code',
        'no_member',
        'name',
        'address',
        'phone',
        'arr_product',
        'cso_id',
        'branch_id',
        'city',
        'active',
        'distric',
        'province',
        'type_register',
        'image',
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
