<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_variant_id',
        'product_id',
        'color_id',
        'size_id',
        'available_stock'
    ];

    //product
    public function product(){
        return $this->belongsTo(Product::class,'product_id','product_id');
    }

    //color
    public function color(){
        return $this->belongsTo(ProductColor::class,'color_id','color_id');
    }

    //size
    public function size(){
        return $this->belongsTo(ProductSize::class,'size_id','size_id');
    }



}
