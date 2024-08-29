<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonaJuridica extends Model
{
    protected $table = 'personas_juridicas';
    protected $primaryKey = 'persona_juridica_id';

    protected $fillable = [
        'customer_id', 'ruc', 'razon_social', 'representante_legal_id',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function representanteLegal()
    {
        return $this->belongsTo(PersonaNatural::class, 'representante_legal_id');
    }
}
