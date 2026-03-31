<?php

namespace App\Helpers;

class SqlServerHelper
{
    private static $conn = null;

    public static function connect()
    {
        if (self::$conn !== null) {
            return self::$conn;
        }

        $serverName = env('DB_HOST2') . ',' . env('DB_PORT2');
        
        $connectionInfo = array(
            "Database" => env('DB_DATABASE2'),
            "UID" => env('DB_USERNAME2'),
            "PWD" => env('DB_PASSWORD2'),
            "CharacterSet" => "UTF-8",
            "LoginTimeout" => 30,
            "TransactionIsolation" => SQLSRV_TXN_READ_UNCOMMITTED
        );
        
        self::$conn = @sqlsrv_connect($serverName, $connectionInfo);
        
        if (self::$conn === false) {
            throw new \Exception("Error de conexion SQL Server: " . json_encode(sqlsrv_errors()));
        }
        
        return self::$conn;
    }

    public static function getMaxValue($table, $column)
    {
        $conn = self::connect();
        $sql = "SELECT MAX([$column]) as maxValue FROM [$table]";
        $stmt = sqlsrv_query($conn, $sql);
        
        if ($stmt === false) {
            throw new \Exception("Error en query MAX: " . json_encode(sqlsrv_errors()));
        }
        
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        $maxValue = $row['maxValue'] ?? 0;
        sqlsrv_free_stmt($stmt);
        
        return (int)$maxValue;
    }

    public static function getValue($table, $column, $where)
    {
        $conn = self::connect();
        $sql = "SELECT [$column] FROM [$table] WHERE $where";
        $stmt = sqlsrv_query($conn, $sql);
        
        if ($stmt === false) {
            throw new \Exception("Error en query: " . json_encode(sqlsrv_errors()));
        }
        
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        $value = $row[$column] ?? null;
        sqlsrv_free_stmt($stmt);
        
        return $value;
    }

    public static function exists($table, $where)
    {
        $conn = self::connect();
        $sql = "SELECT COUNT(*) as total FROM [$table] WHERE $where";
        $stmt = sqlsrv_query($conn, $sql);
        
        if ($stmt === false) {
            throw new \Exception("Error en query EXISTS: " . json_encode(sqlsrv_errors()));
        }
        
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        $total = $row['total'] ?? 0;
        sqlsrv_free_stmt($stmt);
        
        return $total > 0;
    }

    public static function insert($table, $data)
    {
        $conn = self::connect();
        $columns = array_keys($data);
        $values = array_values($data);
        
        $columnsList = '[' . implode('], [', $columns) . ']';
        $placeholders = implode(', ', array_fill(0, count($values), '?'));
        
        $sql = "INSERT INTO [$table] ($columnsList) VALUES ($placeholders)";
        $stmt = sqlsrv_query($conn, $sql, $values);
        
        if ($stmt === false) {
            throw new \Exception("Error en INSERT: " . json_encode(sqlsrv_errors()));
        }
        
        sqlsrv_free_stmt($stmt);
        return true;
    }

    public static function close()
    {
        if (self::$conn !== null) {
            sqlsrv_close(self::$conn);
            self::$conn = null;
        }
    }
}
