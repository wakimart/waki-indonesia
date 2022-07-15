<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Order extends Model
{
    use Sortable;
	static $CashUpgrade = [ '1'=>'CASH', '2'=>'UPGARDE' ];
	static $PaymentType = [ '1'=>'CASH', '2'=>'CARD' ];
	static $Banks = [ '1'=>'BCA', '2'=>'BNI', '3'=>'MEGA', '4'=>'HSBC', '5'=>'MANDIRI', '6'=>'DANAMON', '7'=>'CITIBANK', '8'=>'CIMB NIAGA', '9'=>'MAYBANK', '10'=>'OCBC', '11'=>'PANIN BANK', '12'=>'PERMATA BANK', '13'=>'STANDARD CHATER', '14'=>'BUKOPIN', '15'=>'BLIBLI.COM', '16'=>'Bank Jateng', '17'=>'TUNAI', ];
    static $Know_From = ['1'=>'Pameran/Showroom WAKI', '2'=>'Facebook', '3'=>'Instagram', '4'=>'Waki/Wakimart Customer Service', '5'=>'MGM', '6'=>'Program Refrensi'];
    static $status = ['1' => 'new', '2' => 'process', '3' => 'delivery', '4' => 'success', '5' => 'reject'];

    protected $fillable = [
        'code', 'no_member', 'name', 'address', 'phone', 'cash_upgrade', 'payment_type', 'total_payment', 'down_payment', 'remaining_payment', 'customer_type', 'description', '30_cso_id', '70_cso_id', 'cso_id', 'branch_id', 'city', 'active','orderDate', 'distric', 'province', 'know_from', 'status', 'delivery_cso_id'
    ];
    public $sortable = [
        'name', 'code', 'created_at', 'name', 'orderDate',
    ];

    protected $casts = [
        'image' => 'json',
    ];

    public function cso()
    {
        return $this->belongsTo('App\Cso');
    }
    public function cso_id_30()
    {
        return $this->belongsTo('App\Cso', '30_cso_id', 'id');
    }
    public function cso_id_70()
    {
        return $this->belongsTo('App\Cso', '70_cso_id', 'id');
    }
    public function branch()
    {
        return $this->belongsTo('App\Branch');
    }
    public function orderDetail()
    {
        return $this->hasMany("App\OrderDetail");
    }
    public function orderPayment()
    {
        return $this->hasMany("App\OrderPayment");
    }
    public function getCSO()
    {
        return Cso::where('id', $this->cso_id)->first();
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

    // Unused, column product udah di hapus
    // public function getPrice()
    // {
    //     $result = [];
    //     $arr_promo = json_decode($this->product);
    //     foreach ($arr_promo as $key => $promo) {
    //         if(is_numeric($promo->id)){
    //             $promos = Promo::find($promo->id);
    //             array_push($result, $promos->price);
    //         }else{
    //             array_push($result, "0");
    //         }
    //     }
    //     return $result;
    // }
}
