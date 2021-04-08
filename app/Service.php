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
    	$this->branch = Branch::find($branch_id);
        $this->cso = Cso::find($cso_id);
        return $this;
    }

    public function ServiceOptionDelivery()
    {
        $result = [];
        $data = json_decode($this->service_option);
        if(isset($data[0]->homeService)){
            $serviceHS = HomeService::find($data[0]->homeService);
            if($serviceHS != null){
                $result['recipient_name'] = $serviceHS['name'];
                $result['recipient_phone'] = $serviceHS['phone'];
                $result['address'] = $serviceHS['address'];
                $result['branch_id'] = $serviceHS['branch_id'];
                $result['cso_id'] = $serviceHS['cso_id'];
                $result['appointment'] = $serviceHS['appointment'];
            }
        }
        else{
            $result = $data[0];
        }
        return (object) $result;
    }
}
