<?php

namespace App\Models;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockHistory extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id','product_variant_id','quantity','type','note',
    ];

    public function product(){
        return $this->belongsTo(Product::class,'product_id','product_id');
    }

    public function productVariant(){
        return $this->belongsTo(ProductVariant::class,'product_variant_id','product_variant_id');
    }

}
