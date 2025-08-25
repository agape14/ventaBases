<?php
// Archivo temporal para probar la conexión de libros
// Copia estas variables a tu archivo .env

return [
    'variables_env' => [
        // Conexión principal (sistema actual)
        'DB_CONNECTION=mysql',
        'DB_HOST=127.0.0.1',
        'DB_PORT=3306',
        'DB_DATABASE=venta_bases',
        'DB_USERNAME=root',
        'DB_PASSWORD=',
        
        // Conexión secundaria (sistema de libros)
        'DB_HOST_LIBROS=127.0.0.1',
        'DB_PORT_LIBROS=3306',
        'DB_DATABASE_LIBROS=sistema_libros',
        'DB_USERNAME_LIBROS=root',
        'DB_PASSWORD_LIBROS=',
        'DB_CHARSET_LIBROS=utf8mb4',
        'DB_COLLATION_LIBROS=utf8mb4_unicode_ci',
    ],
    
    'instrucciones' => [
        '1. Copia las variables de arriba a tu archivo .env',
        '2. Ajusta los valores según tu configuración de MySQL',
        '3. Crea la base de datos "sistema_libros" en MySQL',
        '4. Ejecuta: php artisan libros:test-connection',
        '5. Si no tienes .env, copia .env.example y renómbralo a .env',
    ],
    
    'comandos_utiles' => [
        'php artisan config:clear',
        'php artisan cache:clear',
        'php artisan libros:test-connection',
        'php artisan route:list --path=libros',
    ]
];
