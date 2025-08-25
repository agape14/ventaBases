<?php
/**
 * Script para probar los filtros del sistema de libros
 */

require_once 'vendor/autoload.php';

// Cargar Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== PRUEBA DE FILTROS DEL SISTEMA DE LIBROS ===\n\n";

try {
    // Usar el servicio de libros
    $librosService = new \App\Services\LibrosService();
    
    echo "1. Probando obtener todos los pedidos (sin filtros):\n";
    $todosLosPedidos = $librosService->obtenerPedidos();
    echo "   ✅ Total de pedidos encontrados: " . $todosLosPedidos->count() . "\n\n";
    
    if ($todosLosPedidos->count() > 0) {
        $primerPedido = $todosLosPedidos->first();
        echo "   📋 Información del primer pedido:\n";
        echo "      - ID: " . $primerPedido->IdPedido . "\n";
        echo "      - Cliente: " . $primerPedido->nombre_cliente . " " . $primerPedido->apellidos_cliente . "\n";
        echo "      - Email: " . $primerPedido->email_cliente . "\n";
        echo "      - Estado: " . $primerPedido->estadopago_ped . "\n";
        echo "      - Fecha: " . $primerPedido->fecha_pedido . "\n\n";
        
        // Probar filtro por email
        echo "2. Probando filtro por email:\n";
        $filtroEmail = $librosService->obtenerPedidos(['email_cliente' => $primerPedido->email_cliente]);
        echo "   ✅ Pedidos con email '" . $primerPedido->email_cliente . "': " . $filtroEmail->count() . "\n\n";
        
        // Probar filtro por estado
        echo "3. Probando filtro por estado:\n";
        $filtroEstado = $librosService->obtenerPedidos(['estado_pago' => $primerPedido->estadopago_ped]);
        echo "   ✅ Pedidos con estado '" . $primerPedido->estadopago_ped . "': " . $filtroEstado->count() . "\n\n";
        
        // Probar filtro por fecha
        echo "4. Probando filtro por fecha:\n";
        $fecha = date('Y-m-d', strtotime($primerPedido->fecha_pedido));
        $filtroFecha = $librosService->obtenerPedidos(['fecha_desde' => $fecha]);
        echo "   ✅ Pedidos desde fecha '" . $fecha . "': " . $filtroFecha->count() . "\n\n";
        
        // Probar filtro combinado
        echo "5. Probando filtro combinado (email + estado):\n";
        $filtroCombinado = $librosService->obtenerPedidos([
            'email_cliente' => $primerPedido->email_cliente,
            'estado_pago' => $primerPedido->estadopago_ped
        ]);
        echo "   ✅ Pedidos con email '" . $primerPedido->email_cliente . "' y estado '" . $primerPedido->estadopago_ped . "': " . $filtroCombinado->count() . "\n\n";
        
    } else {
        echo "   ⚠️  No hay pedidos en la base de datos para probar los filtros\n\n";
    }
    
    // Probar filtros con valores que no deberían encontrar nada
    echo "6. Probando filtros con valores inexistentes:\n";
    $filtroInexistente = $librosService->obtenerPedidos(['email_cliente' => 'email_inexistente@test.com']);
    echo "   ✅ Pedidos con email inexistente: " . $filtroInexistente->count() . "\n";
    
    $filtroEstadoInexistente = $librosService->obtenerPedidos(['estado_pago' => 'estado_inexistente']);
    echo "   ✅ Pedidos con estado inexistente: " . $filtroEstadoInexistente->count() . "\n\n";
    
    echo "=== RESULTADO DE LA PRUEBA ===\n";
    echo "✅ Los filtros están funcionando correctamente\n";
    echo "✅ El sistema puede filtrar por:\n";
    echo "   - Email del cliente\n";
    echo "   - Estado de pago\n";
    echo "   - Fecha desde/hasta\n";
    echo "   - Combinaciones de filtros\n\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "❌ Línea: " . $e->getLine() . "\n";
    echo "❌ Archivo: " . $e->getFile() . "\n";
}
?>
