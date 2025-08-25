<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductoLibro extends Model
{
    protected $connection = 'mysql_libros';
    protected $table = 'productos';
    protected $primaryKey = 'IdProducto';
    public $timestamps = false;

    protected $fillable = [
        'nombre_producto',
        'precio_producto',
        'descripcion_producto',
        'IdCategoria',
        'stock_producto',
        'avatar_producto',
        'tipo_producto',
        'condicion_producto',
        'stock_previo',
        'alias'
    ];

    protected $casts = [
        'precio_producto' => 'decimal:2',
        'condicion_producto' => 'boolean'
    ];

    // Relaciones
    public function comprasUsuario()
    {
        return $this->hasMany(CompraUsuario::class, 'fk_IdProducto_compra', 'IdProducto');
    }
}
