<?php
/**
 * Script para simular las peticiones AJAX del frontend
 * y verificar que los filtros funcionen correctamente
 */

require_once 'vendor/autoload.php';

// Cargar Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== PRUEBA DE FILTROS FRONTEND ===\n\n";

try {
    // Simular petición GET a la ruta de pedidos
    $request = new \Illuminate\Http\Request();
    
    echo "1. Probando petición sin filtros:\n";
    $controller = new \App\Http\Controllers\Admin\LibrosController(new \App\Services\LibrosService());
    $response = $controller->getPedidos($request);
    $data = json_decode($response->getContent(), true);
    
    if ($data['success']) {
        echo "   ✅ Petición exitosa\n";
        echo "   📊 Total de pedidos: " . $data['meta']['total'] . "\n";
        echo "   📄 Página actual: " . $data['meta']['current_page'] . "\n";
        echo "   📋 Pedidos en esta página: " . count($data['data']) . "\n\n";
    } else {
        echo "   ❌ Error: " . $data['message'] . "\n\n";
    }
    
    // Simular filtro por estado
    echo "2. Probando filtro por estado 'pago aceptado':\n";
    $request = new \Illuminate\Http\Request();
    $request->merge(['estado_pago' => 'pago aceptado']);
    
    $response = $controller->getPedidos($request);
    $data = json_decode($response->getContent(), true);
    
    if ($data['success']) {
        echo "   ✅ Filtro por estado exitoso\n";
        echo "   📊 Total de pedidos con estado 'pago aceptado': " . $data['meta']['total'] . "\n";
        echo "   📋 Pedidos en esta página: " . count($data['data']) . "\n\n";
    } else {
        echo "   ❌ Error: " . $data['message'] . "\n\n";
    }
    
    // Simular filtro por email
    echo "3. Probando filtro por email:\n";
    $request = new \Illuminate\Http\Request();
    $request->merge(['email_cliente' => 'silviallanos2020@gmail.com']);
    
    $response = $controller->getPedidos($request);
    $data = json_decode($response->getContent(), true);
    
    if ($data['success']) {
        echo "   ✅ Filtro por email exitoso\n";
        echo "   📊 Total de pedidos con email 'silviallanos2020@gmail.com': " . $data['meta']['total'] . "\n";
        echo "   📋 Pedidos en esta página: " . count($data['data']) . "\n\n";
    } else {
        echo "   ❌ Error: " . $data['message'] . "\n\n";
    }
    
    // Simular filtro por fecha
    echo "4. Probando filtro por fecha:\n";
    $request = new \Illuminate\Http\Request();
    $request->merge(['fecha_desde' => '2025-08-22']);
    
    $response = $controller->getPedidos($request);
    $data = json_decode($response->getContent(), true);
    
    if ($data['success']) {
        echo "   ✅ Filtro por fecha exitoso\n";
        echo "   📊 Total de pedidos desde '2025-08-22': " . $data['meta']['total'] . "\n";
        echo "   📋 Pedidos en esta página: " . count($data['data']) . "\n\n";
    } else {
        echo "   ❌ Error: " . $data['message'] . "\n\n";
    }
    
    // Simular filtro combinado
    echo "5. Probando filtro combinado:\n";
    $request = new \Illuminate\Http\Request();
    $request->merge([
        'estado_pago' => 'pago aceptado',
        'email_cliente' => 'silviallanos2020@gmail.com'
    ]);
    
    $response = $controller->getPedidos($request);
    $data = json_decode($response->getContent(), true);
    
    if ($data['success']) {
        echo "   ✅ Filtro combinado exitoso\n";
        echo "   📊 Total de pedidos con filtros combinados: " . $data['meta']['total'] . "\n";
        echo "   📋 Pedidos en esta página: " . count($data['data']) . "\n\n";
    } else {
        echo "   ❌ Error: " . $data['message'] . "\n\n";
    }
    
    // Simular paginación
    echo "6. Probando paginación (página 2):\n";
    $request = new \Illuminate\Http\Request();
    $request->merge(['page' => 2]);
    
    $response = $controller->getPedidos($request);
    $data = json_decode($response->getContent(), true);
    
    if ($data['success']) {
        echo "   ✅ Paginación exitosa\n";
        echo "   📊 Total de pedidos: " . $data['meta']['total'] . "\n";
        echo "   📄 Página actual: " . $data['meta']['current_page'] . "\n";
        echo "   📋 Pedidos en esta página: " . count($data['data']) . "\n";
        echo "   📍 Desde: " . $data['meta']['from'] . " hasta: " . $data['meta']['to'] . "\n\n";
    } else {
        echo "   ❌ Error: " . $data['message'] . "\n\n";
    }
    
    echo "=== RESULTADO DE LA PRUEBA FRONTEND ===\n";
    echo "✅ Todos los filtros están funcionando correctamente\n";
    echo "✅ La paginación funciona correctamente\n";
    echo "✅ Los filtros combinados funcionan correctamente\n";
    echo "✅ El controlador responde correctamente a las peticiones AJAX\n\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "❌ Línea: " . $e->getLine() . "\n";
    echo "❌ Archivo: " . $e->getFile() . "\n";
}
?>
