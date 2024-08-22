<?php

namespace App\Models;

use App\Models\Category;
use App\Models\SubCategory;
use App\Models\ProductReview;
use App\Models\ProductVariant;
use App\Models\SubSubCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id',
        'brand_id',
        'category_id',
        'subcategory_id',
        'subsubcategory_id',
        'name',
        'short_description',
        'long_description',
        'original_price',
        'selling_price',
        'discount_price',
        'special_offer',
        'featured',
        'publish_status',
    ];

    public function brand(){
        return $this->belongsTo(Brand::class,'brand_id','brand_id');
    }

    public function category(){
        return $this->belongsTo(Category::class,'category_id','category_id');
    }

    public function subcategory(){
        return $this->belongsTo(SubCategory::class,'subcategory_id','subcategory_id');
    }

    public function subsubcategory(){
        return $this->belongsTo(SubSubCategory::class,'subsubcategory_id','subsubcategory_id');
    }

    public function productVariant(){
        return $this->hasMany(ProductVariant::class,'product_id','product_id');
    }

    public function productReview(){
        return $this->hasMany(ProductReview::class,'product_id','product_id');
    }
}