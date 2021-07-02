<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'title', 'sku', 'description'
    ];

    public function productVariantPrices(){
        return $this->hasMany('App\Models\ProductVariantPrice', 'product_id');
    }

    public function productVariants(){
        return $this->hasMany('App\Models\ProductVariant','product_id', 'id')->groupBy('variant');
    }

    public function productImages(){
        return $this->hasMany('App\Models\ProductImage','product_id', 'id');
    }


}
