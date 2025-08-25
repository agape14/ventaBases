<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $connection = 'mysql_libros';
    protected $table = 'pedidos';
    protected $primaryKey = 'IdPedido';
    public $timestamps = false;

    protected $fillable = [
        'IdRepartidor',
        'fecha_pedido',
        'IdMetododepago',
        'total_ped',
        'estadopago_ped',
        'IdCarrito',
        'nombre_cliente',
        'apellidos_cliente',
        'email_cliente',
        'IdTipoDocumento',
        'nro_documento',
        'comprobante_tipo',
        'migracion_ped',
        'hora_cancelacion',
        'fecha_cancelacion',
        'log_res_pago'
    ];

    protected $casts = [
        'fecha_pedido' => 'datetime',
        'total_ped' => 'decimal:2',
        'migracion_ped' => 'boolean',
        'hora_cancelacion' => 'datetime',
        'fecha_cancelacion' => 'datetime'
    ];

    // Relaciones
    public function comprasUsuario()
    {
        return $this->hasMany(CompraUsuario::class, 'id_compras_ped', 'IdPedido');
    }

    public function direccionPedido()
    {
        return $this->hasOne(DireccionPedido::class, 'fk_IdPedido', 'IdPedido');
    }
}
