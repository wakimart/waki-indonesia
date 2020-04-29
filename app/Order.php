<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
	static $CashUpgrade = [ '1'=>'CASH', '2'=>'UPGARDE' ];
	static $PaymentType = [ '1'=>'CASH', '2'=>'CARD' ];
	static $Banks = [ '1'=>'BCA', '2'=>'BNI', '3'=>'MEGA', '4'=>'UOB', '5'=>'HSBC', '6'=>'MANDIRI', '7'=>'BRI', '8'=>'DANAMON', '9'=>'ANZ', '10'=>'CITIBANK'  ];

    protected $fillable = [
        'code', 'no_member', 'name', 'address', 'phone', 'cash_upgrade', 'product', 'old_product', 'prize', 'payment_type', 'bank', 'total_payment', 'down_payment', 'remaining_payment', 'customer_type', 'description', '30_cso_id', '70_cso_id', 'cso_id', 'branch_id', 'city',
    ];

    public function cso()
    {
        return $this->belongsTo('App\Cso');
    }
    public function cso_id_30()
    {
        return $this->belongsTo('App\Cso');
    }
    public function cso_id_70()
    {
        return $this->belongsTo('App\Cso');
    }
    public function branch()
    {
        return $this->belongsTo('App\Branch');
    }
}
