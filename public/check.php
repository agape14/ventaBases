<?php
echo "<h2>Prueba con ODBC Driver 18</h2>";

$tests = [
    'Driver 18 - Encrypt=no' => [
        "Database" => "emilima7_EMILIMAv2",
        "UID" => "sa",
        "PWD" => "york9010*",
        "Driver" => "ODBC Driver 18 for SQL Server",
        "Encrypt" => "no",
        "TrustServerCertificate" => "no"
    ],
    'Driver 18 - Encrypt=yes + Trust' => [
        "Database" => "emilima7_EMILIMAv2",
        "UID" => "sa",
        "PWD" => "york9010*",
        "Driver" => "ODBC Driver 18 for SQL Server",
        "Encrypt" => "yes",
        "TrustServerCertificate" => "yes"
    ],
    'Driver 17 - Sin opciones SSL' => [
        "Database" => "emilima7_EMILIMAv2",
        "UID" => "sa",
        "PWD" => "york9010*",
        "Driver" => "ODBC Driver 17 for SQL Server"
    ]
];

foreach ($tests as $testName => $connectionInfo) {
    echo "<h3>$testName</h3>";
    
    $conn = @sqlsrv_connect("191.98.144.37,1433", $connectionInfo);
    
    if ($conn === false) {
        echo '<span style="color:red">✗ Error:</span><br><pre>';
        print_r(sqlsrv_errors());
        echo '</pre>';
    } else {
        echo '<span style="color:green">✓ CONEXIÓN EXITOSA!</span><br>';
        
        $sql = "SELECT MAX(codComprob) as maxCod FROM t_comprobante";
        $stmt = sqlsrv_query($conn, $sql);
        
        if ($stmt) {
            $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
            echo "Último codComprob: <strong>" . $row['maxCod'] . "</strong><br>";
            sqlsrv_free_stmt($stmt);
        }
        
        sqlsrv_close($conn);
    }
    echo "<hr>";
}
?>
