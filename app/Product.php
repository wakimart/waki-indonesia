<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'code', 'quick_desc', 'description', 'image', 'video', 'category_id', 'name', 'price'
    ];

    public function category()
    {
        return $this->belongsTo('App\CategoryProduct');
    }
}
