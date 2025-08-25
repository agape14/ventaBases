<?php

namespace App\Helpers;

class MetodosPagoHelper
{
    /**
     * Mapeo de códigos de método de pago a nombres legibles
     */
    public static function getMetodosPago()
    {
        return [
            '1' => 'Efectivo',
            '2' => 'Tarjeta de Crédito',
            '3' => 'Tarjeta de Débito',
            '4' => 'Transferencia Bancaria',
            '5' => 'Depósito Bancario',
            '6' => 'Pago Móvil',
            '7' => 'Yape',
            '8' => 'Plin',
            '9' => 'Tunki',
            '10' => 'Otros'
        ];
    }

    /**
     * Obtener el nombre del método de pago por código
     */
    public static function getNombreMetodoPago($codigo)
    {
        $metodos = self::getMetodosPago();
        return $metodos[$codigo] ?? 'Método no definido';
    }

    /**
     * Obtener el código del método de pago por nombre
     */
    public static function getCodigoMetodoPago($nombre)
    {
        $metodos = self::getMetodosPago();
        return array_search($nombre, $metodos) ?: null;
    }

    /**
     * Obtener todos los métodos de pago para select
     */
    public static function getMetodosPagoForSelect()
    {
        $metodos = self::getMetodosPago();
        $options = [];
        
        foreach ($metodos as $codigo => $nombre) {
            $options[] = [
                'value' => $codigo,
                'text' => $nombre
            ];
        }
        
        return $options;
    }
}
