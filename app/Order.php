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

    protected $fillable = [
        'code', 'no_member', 'name', 'address', 'phone', 'cash_upgrade', 'product', 'old_product', 'prize', 'payment_type', 'bank', 'total_payment', 'down_payment', 'remaining_payment', 'customer_type', 'description', '30_cso_id', '70_cso_id', 'cso_id', 'branch_id', 'city', 'active','orderDate', 'distric', 'province', 'know_from',
    ];
    public $sortable = [
        'name', 'code', 'created_at', 'name', 'orderDate',
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
    public function getCSO()
    {
        return Cso::where('id', $this->cso_id)->first();
    }
}
