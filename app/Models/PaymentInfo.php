<?php

namespace App\Models;

use App\Models\PaymentTransition;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'account_number',
        'type',
        'qr_code',
        'status',
    ];

    public function paymentTransition(){
        return $this->hasMany(PaymentTransition::class,'payment_info_id','id');
    }
}