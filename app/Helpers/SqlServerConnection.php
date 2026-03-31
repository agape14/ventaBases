<?php

namespace App\Helpers;

class SqlServerConnection
{
    public static function connect()
    {
        $serverName = env('DB_HOST2') . ',' . env('DB_PORT2');
        
        $connectionInfo = array(
            "Database" => env('DB_DATABASE2'),
            "UID" => env('DB_USERNAME2'),
            "PWD" => env('DB_PASSWORD2'),
            "CharacterSet" => "UTF-8",
            "Encrypt" => false,
            "TrustServerCertificate" => true,
            "LoginTimeout" => 30,
            "MultipleActiveResultSets" => false,
            // Forzar el uso de ODBC Driver 18
            "Driver" => "ODBC Driver 18 for SQL Server"
        );

        $conn = sqlsrv_connect($serverName, $connectionInfo);

        if ($conn === false) {
            $errors = sqlsrv_errors();
            throw new \Exception("Error de conexión SQL Server: " . json_encode($errors));
        }

        return $conn;
    }

    public static function query($sql, $params = [])
    {
        $conn = self::connect();
        $stmt = sqlsrv_query($conn, $sql, $params);

        if ($stmt === false) {
            $errors = sqlsrv_errors();
            sqlsrv_close($conn);
            throw new \Exception("Error en query: " . json_encode($errors));
        }

        return ['conn' => $conn, 'stmt' => $stmt];
    }

    public static function fetchAll($stmt)
    {
        $results = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $results[] = $row;
        }
        return $results;
    }

    public static function close($conn)
    {
        sqlsrv_close($conn);
    }
}
