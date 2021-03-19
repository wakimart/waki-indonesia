<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Upgrade extends Model
{
    protected $fillable = [
        'acceptance_id', 'due_date', 'status', 'history_status', 'task', 'active'
    ];

    protected $casts = [
        'history_status' => 'json',
    ];

    public function acceptance()
    {
        return $this->belongsTo('App\Acceptance');
    }

    public function statusBy($status){
    	$arrHistory = $this->history_status;
    	foreach ($arrHistory as $key => $value) {
    		if($value['status'] == $status){
    			$value['user_id'] = User::find($value['user_id']);
    			return $value;
    		}
    	}
    }
}
