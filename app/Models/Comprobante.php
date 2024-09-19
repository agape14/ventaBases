<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comprobante extends Model
{
    protected $table = 't_comprobante';  // Nombre de la tabla
    protected $primaryKey = 'codComprob';  // Llave primaria
    public $timestamps = false;  // Asumiendo que no tienes created_at y updated_at

    // Atributos que pueden ser asignados masivamente
    protected $fillable = [
        'codComprob',
        'serie',
        'numero',
        'tipo',
        'fechaEmision',
        'codPersona',
        'nombreCompletoSUNAT',
        'direccionSUNAT',
        'ruc',
        'subtotal',
        'igv',
        'total',
        'estComprob',
        'tipoDoc',
        'emitido',
        'generado',
        'fechaVenc',
        'tipoComprobOrigen',
        'numComprobOrigen',
        'ticket',
        'ticketCreado',
        'ticketValido',
        'ticketVence',
        'mes',
        'voucher',
        'pagoPendiente',
        'ticketMes',
        'ticketAnio',
        'origen',
        'fechaResumen',
        'metodoPago',
        'serieComprobOrigen',
        'facturaElectronica',
        'tipoMaterial',
        'observaciones',
        'fechaPago',
        'registradoPor',
        'fechaRegistro',
        'modificadoPor',
        'fechaModificado',
        'horaComprobante',
        'tipoNota',
        'motivoNota',
        'ticketAutorizado',
        'idPedido',
        'cantidad',
    ];
}
