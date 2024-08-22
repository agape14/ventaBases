<?php

namespace App\Models;

use App\Models\Product;
use App\Models\ProductSize;
use App\Models\ProductColor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_items_id',
        'order_id',
        'product_id',
        'product_variant_id',
        'color_id',
        'size_id',
        'unit_price',
        'quantity',
        'total_price',
    ];

    public function product(){
        return $this->belongsTo(Product::class,'product_id','product_id');
    }

    public function color(){
        return $this->belongsTo(ProductColor::class,'color_id','color_id');
    }

    public function size(){
        return $this->belongsTo(ProductSize::class,'size_id','size_id');
    }

}