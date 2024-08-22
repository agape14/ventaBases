<?php

namespace App\Models;

use App\Models\Category;
use App\Models\SubSubCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubCategory extends Model
{
    use HasFactory;
    protected $fillable = [
        'subcategory_id',
        'category_id',
        'name',
    ];

    public function category(){
        return $this->belongsTo(Category::class,'category_id','category_id');
    }

    public function subsubCategory(){
        return $this->hasMany(SubSubCategory::class,'subcategory_id','subcategory_id');
    }
}