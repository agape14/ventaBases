<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ubigeo extends Model
{
    protected $connection = 'mysql_libros';
    protected $table = 'ubigeo';
    protected $primaryKey = 'IDUBIGEO';
    public $timestamps = false;
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'IDUBIGEO',
        'DESCRIPCION',
        'DESCRIPCION_EN'
    ];

    // Relaciones
    public function direccionesPedido()
    {
        return $this->hasMany(DireccionPedido::class, 'fk_IdUbigeoDireccion', 'IDUBIGEO');
    }
}
