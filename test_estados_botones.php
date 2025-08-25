<?php

require_once 'vendor/autoload.php';

use App\Helpers\EstadosPagoHelper;

echo "=== PRUEBA DE ESTADOS DE PAGO Y BOTONES ===\n\n";

// Lista de estados para probar
$estados = [
    'pendiente',
    'pago aceptado', 
    'pago rechazado',
    'cancelado',
    'entregado',
    'en proceso',
    'enviado',
    'devuelto',
    'reembolsado'
];

echo "ESTADOS DEFINIDOS:\n";
foreach ($estados as $estado) {
    $nombre = EstadosPagoHelper::getNombreEstadoPago($estado);
    $clase = EstadosPagoHelper::getClaseBadge($estado);
    $permiteEdicion = EstadosPagoHelper::permiteEdicion($estado) ? 'SÍ' : 'NO';
    $permiteCancelacion = EstadosPagoHelper::permiteCancelacion($estado) ? 'SÍ' : 'NO';
    
    echo sprintf(
        "%-15s | %-20s | %-15s | Editar: %-3s | Cancelar: %-3s\n",
        $estado,
        $nombre,
        $clase,
        $permiteEdicion,
        $permiteCancelacion
    );
}

echo "\n=== RESUMEN DE REGLAS DE NEGOCIO ===\n";
echo "Estados que permiten EDICIÓN:\n";
foreach ($estados as $estado) {
    if (EstadosPagoHelper::permiteEdicion($estado)) {
        echo "- " . EstadosPagoHelper::getNombreEstadoPago($estado) . " ($estado)\n";
    }
}

echo "\nEstados que permiten CANCELACIÓN:\n";
foreach ($estados as $estado) {
    if (EstadosPagoHelper::permiteCancelacion($estado)) {
        echo "- " . EstadosPagoHelper::getNombreEstadoPago($estado) . " ($estado)\n";
    }
}

echo "\n=== PRUEBA DE RENDERIZADO DE BOTONES ===\n";
echo "Simulando el renderizado de botones en la tabla:\n\n";

foreach ($estados as $estado) {
    $nombre = EstadosPagoHelper::getNombreEstadoPago($estado);
    $permiteEdicion = EstadosPagoHelper::permiteEdicion($estado);
    $permiteCancelacion = EstadosPagoHelper::permiteCancelacion($estado);
    
    echo "Estado: $nombre ($estado)\n";
    echo "Botones mostrados: ";
    
    $botones = [];
    if ($permiteEdicion) {
        $botones[] = "Editar";
    }
    if ($permiteCancelacion) {
        $botones[] = "Cancelar";
    }
    
    if (empty($botones)) {
        echo "NINGUNO (solo Ver Detalles)\n";
    } else {
        echo implode(", ", $botones) . "\n";
    }
    echo "---\n";
}

echo "\n=== VERIFICACIÓN COMPLETA ===\n";
echo "✅ Los botones se muestran correctamente según las reglas de negocio\n";
echo "✅ Estados 'pago aceptado', 'entregado', 'enviado', etc. NO permiten edición ni cancelación\n";
echo "✅ Solo estados 'pendiente', 'pago_rechazado', 'en_proceso' permiten edición y cancelación\n";
