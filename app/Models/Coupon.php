<?php

namespace App\Models;

use App\Models\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Coupon extends Model
{
    use HasFactory;
    protected $fillable = [
        'coupon_id',
        'coupon_code',
        'coupon_discount',
        'start_date',
        'end_date',
        'created_at'
    ];

    public function order()
    {
        return $this->hasMany(Order::class,'coupon_id','coupon_id');
    }
}