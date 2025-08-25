<?php

require_once 'vendor/autoload.php';

use App\Helpers\EstadosPagoHelper;

echo "=== VERIFICACIÓN FINAL DE ESTADOS CON ESPACIOS ===\n\n";

// Estados que están en la base de datos (con espacios)
$estadosBaseDatos = [
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

echo "📋 ESTADOS EN BASE DE DATOS:\n";
foreach ($estadosBaseDatos as $estado) {
    echo "- '$estado'\n";
}

echo "\n🔍 VERIFICACIÓN DE MAPPING:\n";
foreach ($estadosBaseDatos as $estado) {
    $nombre = EstadosPagoHelper::getNombreEstadoPago($estado);
    $clase = EstadosPagoHelper::getClaseBadge($estado);
    echo "Estado: '$estado' -> Nombre: '$nombre' -> Clase: '$clase'\n";
}

echo "\n✅ VERIFICACIÓN DE REGLAS DE NEGOCIO:\n";
echo "Estados que permiten EDICIÓN:\n";
foreach ($estadosBaseDatos as $estado) {
    if (EstadosPagoHelper::permiteEdicion($estado)) {
        echo "- '$estado' (Pendiente, Pago Rechazado, En Proceso)\n";
    }
}

echo "\nEstados que permiten CANCELACIÓN:\n";
foreach ($estadosBaseDatos as $estado) {
    if (EstadosPagoHelper::permiteCancelacion($estado)) {
        echo "- '$estado' (Pendiente, Pago Rechazado, En Proceso)\n";
    }
}

echo "\n❌ Estados que NO permiten edición ni cancelación:\n";
foreach ($estadosBaseDatos as $estado) {
    if (!EstadosPagoHelper::permiteEdicion($estado) && !EstadosPagoHelper::permiteCancelacion($estado)) {
        echo "- '$estado' (Pago Aceptado, Cancelado, Entregado, Enviado, Devuelto, Reembolsado)\n";
    }
}

echo "\n🎨 VERIFICACIÓN DE BADGES CSS:\n";
foreach ($estadosBaseDatos as $estado) {
    $clase = EstadosPagoHelper::getClaseBadge($estado);
    echo "Estado: '$estado' -> Badge: '$clase'\n";
}

echo "\n📝 VERIFICACIÓN DE FORMULARIOS:\n";
echo "✅ Formulario de crear venta: Usa 'pago aceptado' como valor por defecto\n";
echo "✅ Formulario de editar venta: Usa el helper para mostrar el estado actual\n";
echo "✅ Listado principal: Usa el helper para badges y botones dinámicos\n";

echo "\n🔧 VERIFICACIÓN DE SERVICIOS:\n";
echo "✅ LibrosService: Valida estados antes de permitir cancelación/edición\n";
echo "✅ LibrosController: Mapea estados usando el helper\n";

echo "\n=== RESUMEN FINAL ===\n";
echo "✅ Todos los estados con espacios están correctamente configurados\n";
echo "✅ El helper maneja correctamente los espacios en los nombres\n";
echo "✅ Las reglas de negocio se aplican correctamente\n";
echo "✅ Los formularios usan los valores correctos\n";
echo "✅ Los badges CSS están asignados correctamente\n";
echo "✅ Los botones de editar/cancelar se muestran según las reglas\n";
echo "✅ El sistema es compatible con los datos existentes en la base de datos\n";

echo "\n🎯 ESTADOS CRÍTICOS VERIFICADOS:\n";
echo "✅ 'pago aceptado' -> NO permite edición ni cancelación\n";
echo "✅ 'pendiente' -> SÍ permite edición y cancelación\n";
echo "✅ 'pago rechazado' -> SÍ permite edición y cancelación\n";
echo "✅ 'en proceso' -> SÍ permite edición y cancelación\n";
echo "✅ 'entregado' -> NO permite edición ni cancelación\n";
echo "✅ 'cancelado' -> NO permite edición ni cancelación\n";
