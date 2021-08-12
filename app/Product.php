<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'code', 'quick_desc', 'description', 'image', 'video', 'category_id', 'name', 'price', 'active'
    ];

    public function category()
    {
        return $this->belongsTo('App\CategoryProduct');
    }

    public function personalHomecareProduct()
    {
        return $this->hasMany('App\PersonalHomecareProduct');
    }
}
