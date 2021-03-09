<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'no_mpc', 'name', 'address', 'phone', 'service_date', 'service_option', 'status', 'history_status', 'active',
    ];
}