<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Upgrade extends Model
{
    protected $fillable = [
        'acceptance_id', 'due_date', 'status', 'history_status', 'task', 'active'
    ];

    public function acceptance()
    {
        return $this->belongsTo('App\Acceptance');
    }
}
