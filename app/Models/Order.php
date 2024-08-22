<?php

namespace App\Models;

use App\Models\City;
use App\Models\Township;
use App\Models\OrderItem;
use App\Models\StateDivision;
use App\Models\PaymentTransition;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id',
        'user_id',
        'state_division_id',
        'city_id',
        'township_id',
        'name',
        'email',
        'phone',
        'note',
        'payment_method',
        'sub_total',
        'coupon_discount',
        'grand_total',
        'invoice_number',
        'order_date',
        'order_month',
        'order_year',
        'confirmed_date',
        'processing_date',
        'picked_date',
        'shipped_date',
        'delivered_date',
        'cancel_date',
        'return_date',
        'return_reason',
        'status'
    ];
    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function stateDivision(){
        return $this->belongsTo(StateDivision::class,'state_division_id','state_division_id');
    }
    public function city(){
        return $this->belongsTo(City::class,'city_id','city_id');
    }
    public function township(){
        return $this->belongsTo(Township::class,'township_id','township_id');
    }

    public function orderItem(){
        return $this->hasMany(OrderItem::class,'order_id','order_id');
    }

    public function paymentTransition(){
        return $this->hasOne(PaymentTransition::class,'order_id','order_id');
    }
}