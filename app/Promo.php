<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    protected $fillable = [
        'code', 'image', 'product', 'price'
    ];

    public function product_list()
    {
        $temp = json_decode($this['product'], true);
        $products = [];

        foreach ($temp as $value) {
            array_push($products, Product::find($value['id']));
        }

        return $products;
    }

    public function productCode()
    {
        $products = json_decode($this->product, true);
        $productCode = [];

        foreach ($products as $product) {
            $queryProduct = Product::select("code")
            ->where("id", $product["id"])
            ->first();

            $productCode[] = $queryProduct->code;
        }

        return $productCode;
    }
}
