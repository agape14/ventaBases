<?php

namespace App\Models;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubSubCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'subsubcategory_id',
        'category_id',
        'subcategory_id',
        'name',
    ];

    //get category
    public function category(){
        return $this->belongsTo(Category::class,'category_id','category_id');
    }

    //get subcategory
    public function subCategory(){
        return $this->belongsTo(SubCategory::class,'subcategory_id','subcategory_id');
    }
}