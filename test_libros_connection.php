<?php
/**
 * Script de prueba para verificar la conexión con el sistema de libros
 * Ejecutar desde la raíz del proyecto: php test_libros_connection.php
 */

require_once 'vendor/autoload.php';

// Cargar Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\Pedido;
use App\Models\ProductoLibro;
use App\Models\Ubigeo;

echo "=== PRUEBA DE CONEXIÓN SISTEMA DE LIBROS ===\n\n";

try {
    // 1. Probar conexión básica
    echo "1. Probando conexión básica...\n";
    DB::connection('mysql_libros')->getPdo();
    echo "✓ Conexión establecida correctamente\n\n";

    // 2. Probar consultas a las tablas
    echo "2. Probando consultas a las tablas...\n";
    
    // Tabla pedidos
    try {
        $pedidosCount = DB::connection('mysql_libros')->table('pedidos')->count();
        echo "✓ Tabla 'pedidos': {$pedidosCount} registros\n";
    } catch (Exception $e) {
        echo "⚠ Tabla 'pedidos': " . $e->getMessage() . "\n";
    }
    
    // Tabla productos
    try {
        $productosCount = DB::connection('mysql_libros')->table('productos')->count();
        echo "✓ Tabla 'productos': {$productosCount} registros\n";
    } catch (Exception $e) {
        echo "⚠ Tabla 'productos': " . $e->getMessage() . "\n";
    }
    
    // Tabla ubigeo
    try {
        $ubigeosCount = DB::connection('mysql_libros')->table('ubigeo')->count();
        echo "✓ Tabla 'ubigeo': {$ubigeosCount} registros\n";
    } catch (Exception $e) {
        echo "⚠ Tabla 'ubigeo': " . $e->getMessage() . "\n";
    }
    
    echo "\n";

    // 3. Probar modelos Eloquent
    echo "3. Probando modelos Eloquent...\n";
    
    try {
        $pedidosCount = Pedido::count();
        echo "✓ Modelo Pedido: {$pedidosCount} registros\n";
    } catch (Exception $e) {
        echo "⚠ Modelo Pedido: " . $e->getMessage() . "\n";
    }
    
    try {
        $productosCount = ProductoLibro::count();
        echo "✓ Modelo ProductoLibro: {$productosCount} registros\n";
    } catch (Exception $e) {
        echo "⚠ Modelo ProductoLibro: " . $e->getMessage() . "\n";
    }
    
    try {
        $ubigeosCount = Ubigeo::count();
        echo "✓ Modelo Ubigeo: {$ubigeosCount} registros\n";
    } catch (Exception $e) {
        echo "⚠ Modelo Ubigeo: " . $e->getMessage() . "\n";
    }
    
    echo "\n";

    // 4. Probar consulta con relaciones
    echo "4. Probando consulta con relaciones...\n";
    try {
        $pedidoConRelaciones = Pedido::with(['comprasUsuario', 'direccionPedido'])->first();
        if ($pedidoConRelaciones) {
            echo "✓ Consulta con relaciones exitosa\n";
        } else {
            echo "✓ Consulta con relaciones exitosa (sin datos)\n";
        }
    } catch (Exception $e) {
        echo "⚠ Consulta con relaciones: " . $e->getMessage() . "\n";
    }
    
    echo "\n=== PRUEBA COMPLETADA ===\n";
    echo "Si ves ✓, la conexión está funcionando correctamente.\n";
    echo "Si ves ⚠, hay algún problema que necesita ser resuelto.\n";

} catch (Exception $e) {
    echo "✗ Error en la conexión: " . $e->getMessage() . "\n";
    echo "\nPosibles soluciones:\n";
    echo "1. Verifica que las variables de entorno estén configuradas en .env\n";
    echo "2. Verifica que la base de datos 'sistema_libros' exista\n";
    echo "3. Verifica que las credenciales de MySQL sean correctas\n";
    echo "4. Ejecuta: php artisan config:clear\n";
}
