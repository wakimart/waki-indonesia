<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'code', 'quick_desc', 'description', 'image', 'video', 'category_id', 'name', 'price', 'in_price', 'active', 'flipbook_url', 'show', 'can_buy'
    ];

    public function category()
    {
        return $this->belongsTo('App\CategoryProduct');
    }

    public function personalHomecareProduct()
    {
        return $this->hasMany('App\PersonalHomecareProduct');
    }

    public function stock()
    {
        return $this->hasMany('App\Stock');
    }

    public function stock_with_warehouse($warehuse_id)
    {
        return $this->hasMany('App\Stock')->where('warehouse_id', $warehouse_id);
    }
}
