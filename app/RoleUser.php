<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoleUser extends Model
{
    protected $fillable = [
        'user_id', 'user_category_id',
    ];
}
