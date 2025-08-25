<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompraUsuario extends Model
{
    protected $connection = 'mysql_libros';
    protected $table = 'compras_usuario';
    protected $primaryKey = 'row_compras';
    public $timestamps = false;

    protected $fillable = [
        'cant_producto',
        'fk_IdProducto_compra',
        'id_compras_ped',
        'fecha_reg_compra',
        'subtotal_compra',
        'email_cliente',
        'precio_compra'
    ];

    protected $casts = [
        'fecha_reg_compra' => 'datetime',
        'subtotal_compra' => 'decimal:2',
        'precio_compra' => 'decimal:2'
    ];

    // Relaciones
    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'id_compras_ped', 'IdPedido');
    }

    public function producto()
    {
        return $this->belongsTo(ProductoLibro::class, 'fk_IdProducto_compra', 'IdProducto');
    }
}
