<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customers';
    protected $primaryKey = 'customer_id';

    protected $fillable = [
        'customer_type', 'name', 'email', 'phone', 'address',
    ];

    public function personaNatural()
    {
        return $this->hasOne(PersonaNatural::class, 'customer_id');
    }

    public function personaJuridica()
    {
        return $this->hasOne(PersonaJuridica::class, 'customer_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id');
    }
}
