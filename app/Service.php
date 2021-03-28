<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'code','no_mpc', 'name', 'address', 'phone', 'service_date', 'service_option', 'status', 'history_status', 'active',
    ];

    public function product_services()
    {
        return $this->hasMany('App\ProductService');
    }

    public function statusBy($status){
    	$arrHistory = json_decode($this->history_status);
    	foreach ($arrHistory as $key => $value) {
    		if($value->status == $status){
    			$value->user_id = User::find($value->user_id);
    			return $value;
    		}
    	}
    }

    public function getDetailSales($branch_id, $cso_id){
    	$arr_serviceoption = json_decode($this->service_option);
    	foreach ($arr_serviceoption as $key => $value) {
    		if($value->branch_id == $branch_id && $value->cso_id == $cso_id){
    			$value->branch_id = Branch::find($value->branch_id);
    			$value->cso_id = Cso::find($value->cso_id);
    			return $value;
    		}
    	}
    }
}
