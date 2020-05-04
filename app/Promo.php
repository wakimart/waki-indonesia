<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Promo extends Model
{
    protected $fillable = [
        'code', 'image', 'product', 'price'
    ];

    public function product_list(){
    	$temp = json_decode($this['product'], true);
    	$products = [];
    	foreach ($temp as $value) {
    		array_push($products, Product::find($value['id']));
    	}
    	return $products;
    }
}
