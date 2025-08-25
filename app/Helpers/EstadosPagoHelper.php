<?php

namespace App\Helpers;

class EstadosPagoHelper
{
    /**
     * Mapeo de estados de pago a nombres legibles
     */
    public static function getEstadosPago()
    {
        return [
            'pendiente' => 'Pendiente',
            'pago aceptado' => 'Pago Aceptado',
            'pago rechazado' => 'Pago Rechazado',
            'cancelado' => 'Cancelado',
            'entregado' => 'Entregado',
            'en proceso' => 'En Proceso',
            'enviado' => 'Enviado',
            'devuelto' => 'Devuelto',
            'reembolsado' => 'Reembolsado'
        ];
    }

    /**
     * Obtener el nombre del estado de pago por código
     */
    public static function getNombreEstadoPago($codigo)
    {
        $estados = self::getEstadosPago();
        return $estados[$codigo] ?? 'Estado no definido';
    }

    /**
     * Obtener el código del estado de pago por nombre
     */
    public static function getCodigoEstadoPago($nombre)
    {
        $estados = self::getEstadosPago();
        return array_search($nombre, $estados) ?: null;
    }

    /**
     * Obtener todos los estados de pago para select
     */
    public static function getEstadosPagoForSelect()
    {
        $estados = self::getEstadosPago();
        $options = [];
        
        foreach ($estados as $codigo => $nombre) {
            $options[] = [
                'value' => $codigo,
                'text' => $nombre
            ];
        }
        
        return $options;
    }

    /**
     * Obtener la clase CSS para el badge del estado
     */
    public static function getClaseBadge($estado)
    {
        switch ($estado) {
            case 'pendiente':
                return 'badge-warning';
            case 'pago aceptado':
                return 'badge-success';
            case 'pago rechazado':
                return 'badge-danger';
            case 'cancelado':
                return 'badge-danger';
            case 'entregado':
                return 'badge-success';
            case 'en proceso':
                return 'badge-info';
            case 'enviado':
                return 'badge-primary';
            case 'devuelto':
                return 'badge-warning';
            case 'reembolsado':
                return 'badge-secondary';
            default:
                return 'badge-secondary';
        }
    }

    /**
     * Verificar si un estado permite cancelación
     */
    public static function permiteCancelacion($estado)
    {
        $estadosCancelables = [
            'pendiente',
            'pago rechazado',
            'en proceso'
        ];
        
        return in_array($estado, $estadosCancelables);
    }

    /**
     * Verificar si un estado permite edición
     */
    public static function permiteEdicion($estado)
    {
        $estadosEditables = [
            'pendiente',
            'pago rechazado',
            'en proceso'
        ];
        
        return in_array($estado, $estadosEditables);
    }

    /**
     * Obtener estados que permiten cancelación para select
     */
    public static function getEstadosCancelablesForSelect()
    {
        $estados = self::getEstadosPago();
        $options = [];
        
        foreach ($estados as $codigo => $nombre) {
            if (self::permiteCancelacion($codigo)) {
                $options[] = [
                    'value' => $codigo,
                    'text' => $nombre
                ];
            }
        }
        
        return $options;
    }

    /**
     * Obtener estados que permiten edición para select
     */
    public static function getEstadosEditablesForSelect()
    {
        $estados = self::getEstadosPago();
        $options = [];
        
        foreach ($estados as $codigo => $nombre) {
            if (self::permiteEdicion($codigo)) {
                $options[] = [
                    'value' => $codigo,
                    'text' => $nombre
                ];
            }
        }
        
        return $options;
    }
}
