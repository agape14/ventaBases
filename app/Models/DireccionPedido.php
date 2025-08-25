<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DireccionPedido extends Model
{
    protected $connection = 'mysql_libros';
    protected $table = 'direccion_pedido';
    protected $primaryKey = 'IdDireccionPedido';
    public $timestamps = false;

    protected $fillable = [
        'direccion_ped',
        'comentario_ped',
        'fk_IdUbigeoDireccion',
        'fk_IdPedido',
        'telf_ped'
    ];

    // Relaciones
    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'fk_IdPedido', 'IdPedido');
    }

    public function ubigeo()
    {
        return $this->belongsTo(Ubigeo::class, 'fk_IdUbigeoDireccion', 'IDUBIGEO');
    }
}
