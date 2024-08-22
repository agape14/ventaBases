<?php

namespace App\Models;

use PDO;
use App\Models\SubCategory;
use App\Models\SubSubCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;
    protected $fillable = [
        'category_id',
        'name',
        'image',
    ];

    public function subCategory(){
        return $this->hasMany(SubCategory::class,'category_id','category_id');
    }

    public function subsubCategory(){
        return $this->hasMany(SubSubCategory::class,'category_id','category_id');
    }

}