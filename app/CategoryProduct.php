<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoryProduct extends Model
{
    protected $fillable = [
        'name'
    ];

    public function product()
    {
        return $this->hasMany('App\Product', 'category_id', 'id')->where('active', true);
    }

    public function productIndex()
    {
        return $this->hasMany('App\Product', 'category_id', 'id')->where('active', true)->where('show', true);
    }

    public function productCanbuy()
    {
        return $this->hasMany('App\Product', 'category_id', 'id')->where('active', true)->where('can_buy', true);
    }

    public function productStock()
    {
        return $this->hasMany('App\Product', 'category_id', 'id')->where('active', true)->where('can_buy', false)->where('show', false);
    }
}
