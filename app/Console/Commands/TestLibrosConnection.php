<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Pedido;
use App\Models\ProductoLibro;
use App\Models\Ubigeo;

class TestLibrosConnection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'libros:test-connection';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Probar la conexión con el sistema de libros';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Probando conexión con el sistema de libros...');

        try {
            // Probar conexión básica
            DB::connection('mysql_libros')->getPdo();
            $this->info('✓ Conexión a la base de datos establecida correctamente');

            // Probar consultas a las tablas
            $this->testTableQueries();

            $this->info('✓ Todas las pruebas de conexión fueron exitosas');
            return 0;

        } catch (\Exception $e) {
            $this->error('✗ Error en la conexión: ' . $e->getMessage());
            return 1;
        }
    }

    /**
     * Probar consultas a las tablas principales
     */
    private function testTableQueries()
    {
        // Probar tabla pedidos
        try {
            $pedidosCount = Pedido::count();
            $this->info("✓ Tabla 'pedidos': {$pedidosCount} registros encontrados");
        } catch (\Exception $e) {
            $this->warn("⚠ Tabla 'pedidos': " . $e->getMessage());
        }

        // Probar tabla productos
        try {
            $productosCount = ProductoLibro::count();
            $this->info("✓ Tabla 'productos': {$productosCount} registros encontrados");
        } catch (\Exception $e) {
            $this->warn("⚠ Tabla 'productos': " . $e->getMessage());
        }

        // Probar tabla ubigeo
        try {
            $ubigeosCount = Ubigeo::count();
            $this->info("✓ Tabla 'ubigeo': {$ubigeosCount} registros encontrados");
        } catch (\Exception $e) {
            $this->warn("⚠ Tabla 'ubigeo': " . $e->getMessage());
        }

        // Probar consulta con relaciones
        try {
            $pedidoConRelaciones = Pedido::with(['comprasUsuario', 'direccionPedido'])
                ->first();
            
            if ($pedidoConRelaciones) {
                $this->info("✓ Consulta con relaciones exitosa");
            } else {
                $this->info("✓ Consulta con relaciones exitosa (sin datos)");
            }
        } catch (\Exception $e) {
            $this->warn("⚠ Consulta con relaciones: " . $e->getMessage());
        }
    }
}
