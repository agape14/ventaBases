<?php
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    echo "Probando conexión a SIGI...\n";
    
    $maxCod = DB::connection('sqlsrv')
        ->table('t_comprobante')
        ->max('codComprob');
    
    echo "✓ Conexión exitosa!\n";
    echo "Último codComprob: " . $maxCod . "\n";
    
} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "Código: " . $e->getCode() . "\n";
}