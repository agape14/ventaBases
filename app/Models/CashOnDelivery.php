<?php

namespace App\Models;

use App\Models\City;
use App\Models\Township;
use App\Models\StateDivision;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CashOnDelivery extends Model
{
    use HasFactory;

    protected $fillable = [
        'state_division_id','city_id','township_id','status'
    ];

    public function stateDivision(){
        return $this->belongsTo(StateDivision::class,'state_division_id','state_division_id');
    }

    public function city(){
        return $this->belongsTo(City::class,'city_id','city_id');
    }

    public function township(){
        return $this->belongsTo(Township::class,'township_id','township_id');
    }
}