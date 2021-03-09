<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RegistrationPromotion extends Model
{
    protected $fillable = [
        'first_name', 'last_name', 'address', 'email', 'phone','active',
    ];
}
