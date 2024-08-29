<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonaNatural extends Model
{
    protected $table = 'personas_naturales';
    protected $primaryKey = 'persona_natural_id';

    protected $fillable = [
        'customer_id', 'dni', 'birthdate',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
