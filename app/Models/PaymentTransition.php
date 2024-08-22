<?php

namespace App\Models;

use App\Models\Order;
use App\Models\PaymentInfo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentTransition extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'order_id',
        'payment_info_id',
        'payment_screenshot',
    ];

    //order
   public function order(){
        return $this->belongsTo(Order::class,'order_id','order_id');
   }

   //payment info
   public function paymentInfo(){
        return $this->belongsTo(PaymentInfo::class,'payment_info_id','id');
   }
}