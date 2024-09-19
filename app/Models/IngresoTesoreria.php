<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IngresoTesoreria extends Model
{
    protected $table = 't_ingreso_tesoreria';  // Nombre de la tabla
    protected $primaryKey = 'codIngreso';  // Llave primaria
    public $timestamps = false;  // Asumiendo que no tienes created_at y updated_at

    // Atributos que pueden ser asignados masivamente
    protected $fillable = [
        'codIngreso',
        'codComprob',
        'codConcepto',
        'fecha',
        'canal',
        'subtotal',
        'igv',
        'total',
        'estIngreso',
        'detalle',
        'codPla',
        'importe',
        'intereses',
        'codRefer',
        'serie',
        'numero',
        'documento',
        'item',
        'pendientePago',
        'pagoPendiente',
        'fechaPago',
        'registradoPor',
        'fechaRegistro',
        'modificadoPor',
        'fechaModificado',
        'afectoIGV',
        'cantidad',
        'preciounit',
        'totalExonerado',
    ];
}
