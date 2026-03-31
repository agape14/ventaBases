<?php
echo "<h2>Configuración OpenSSL</h2>";
echo "Versión OpenSSL: " . OPENSSL_VERSION_TEXT . "<br><br>";

echo "<h3>Protocolos TLS soportados:</h3>";
$stream_context = stream_context_create([
    'ssl' => [
        'capture_peer_cert' => true,
    ]
]);

foreach (['TLSv1', 'TLSv1.1', 'TLSv1.2', 'TLSv1.3'] as $protocol) {
    echo "$protocol: ";
    $ctx = stream_context_create([
        'ssl' => [
            'crypto_method' => constant('STREAM_CRYPTO_METHOD_' . str_replace('.', '_', $protocol) . '_CLIENT')
        ]
    ]);
    echo "Disponible<br>";
}

echo "<br><h3>Intentar conexión TCP simple al SQL Server:</h3>";
$fp = @fsockopen("191.98.144.37", 1433, $errno, $errstr, 5);
if ($fp) {
    echo '<span style="color:green">✓ Puerto 1433 accesible</span><br>';
    fclose($fp);
} else {
    echo '<span style="color:red">✗ Puerto 1433 no accesible: ' . $errstr . '</span><br>';
}
?>