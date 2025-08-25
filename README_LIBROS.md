# Sistema de Libros - Segunda Conexión MySQL

Este documento describe la implementación de una segunda conexión de MySQL para integrar un sistema de venta de libros con el sistema actual.

## Configuración

### 1. Variables de Entorno

Agregar las siguientes variables a tu archivo `.env`:

```env
# Conexión secundaria (sistema de libros)
DB_HOST_LIBROS=127.0.0.1
DB_PORT_LIBROS=3306
DB_DATABASE_LIBROS=sistema_libros
DB_USERNAME_LIBROS=tu_usuario_libros
DB_PASSWORD_LIBROS=tu_password_libros
DB_CHARSET_LIBROS=utf8mb4
DB_COLLATION_LIBROS=utf8mb4_unicode_ci
```

### 2. Configuración de Base de Datos

La configuración se encuentra en `config/database.php` con la conexión `mysql_libros`.

## Modelos Creados

### 1. Pedido
- **Archivo**: `app/Models/Pedido.php`
- **Tabla**: `pedidos`
- **Conexión**: `mysql_libros`

### 2. CompraUsuario
- **Archivo**: `app/Models/CompraUsuario.php`
- **Tabla**: `compras_usuario`
- **Conexión**: `mysql_libros`

### 3. DireccionPedido
- **Archivo**: `app/Models/DireccionPedido.php`
- **Tabla**: `direccion_pedido`
- **Conexión**: `mysql_libros`

### 4. Ubigeo
- **Archivo**: `app/Models/Ubigeo.php`
- **Tabla**: `ubigeo`
- **Conexión**: `mysql_libros`

### 5. ProductoLibro
- **Archivo**: `app/Models/ProductoLibro.php`
- **Tabla**: `productos`
- **Conexión**: `mysql_libros`

## Controlador

### LibrosController
- **Archivo**: `app/Http/Controllers/LibrosController.php`
- **Funcionalidades**:
  - Obtener pedidos
  - Crear pedidos
  - Actualizar estado de pago
  - Cancelar pedidos
  - Obtener productos
  - Obtener ubigeos

## Servicio

### LibrosService
- **Archivo**: `app/Services/LibrosService.php`
- **Funcionalidades**:
  - Lógica de negocio para pedidos
  - Filtros avanzados
  - Estadísticas
  - Manejo de transacciones

## Rutas Disponibles

Todas las rutas requieren autenticación (`auth` middleware):

```php
// Obtener todos los pedidos
GET /libros/pedidos

// Obtener un pedido específico
GET /libros/pedidos/{id}

// Crear un nuevo pedido
POST /libros/pedidos

// Actualizar estado de pago
PUT /libros/pedidos/{id}/estado-pago

// Cancelar un pedido
PUT /libros/pedidos/{id}/cancelar

// Obtener productos
GET /libros/productos

// Obtener ubigeos
GET /libros/ubigeos
```

## Ejemplos de Uso

### 1. Obtener Pedidos

```php
use App\Services\LibrosService;

$librosService = new LibrosService();

// Obtener todos los pedidos
$pedidos = $librosService->obtenerPedidos();

// Obtener pedidos con filtros
$pedidos = $librosService->obtenerPedidos([
    'estado_pago' => 'pagado',
    'fecha_desde' => '2024-01-01',
    'fecha_hasta' => '2024-12-31'
]);
```

### 2. Crear un Pedido

```php
$datosPedido = [
    'IdRepartidor' => 1,
    'IdMetododepago' => 'tarjeta',
    'total_ped' => 150.00,
    'estadopago_ped' => 'pendiente',
    'IdCarrito' => 123,
    'nombre_cliente' => 'Juan',
    'apellidos_cliente' => 'Pérez',
    'email_cliente' => 'juan@example.com',
    'IdTipoDocumento' => 1,
    'nro_documento' => '12345678',
    'comprobante_tipo' => 'boleta',
    'compras' => [
        [
            'cant_producto' => 2,
            'fk_IdProducto_compra' => 1,
            'subtotal_compra' => 100.00,
            'precio_compra' => 50.00
        ]
    ],
    'direccion' => [
        'direccion_ped' => 'Av. Principal 123',
        'comentario_ped' => 'Casa azul',
        'fk_IdUbigeoDireccion' => '150101',
        'telf_ped' => '999888777'
    ]
];

$pedido = $librosService->crearPedido($datosPedido);
```

### 3. Usar el Controlador

```php
use App\Http\Controllers\LibrosController;

$controller = new LibrosController();

// Obtener pedidos
$response = $controller->getPedidos();

// Crear pedido
$request = new Request($datosPedido);
$response = $controller->crearPedido($request);
```

### 4. Usar los Modelos Directamente

```php
use App\Models\Pedido;
use App\Models\ProductoLibro;

// Obtener pedidos con relaciones
$pedidos = Pedido::with(['comprasUsuario.producto', 'direccionPedido.ubigeo'])
    ->orderBy('fecha_pedido', 'desc')
    ->get();

// Obtener productos activos
$productos = ProductoLibro::where('condicion_producto', 1)
    ->orderBy('nombre_producto')
    ->get();
```

## Relaciones entre Modelos

### Pedido
- `hasMany` CompraUsuario
- `hasOne` DireccionPedido

### CompraUsuario
- `belongsTo` Pedido
- `belongsTo` ProductoLibro

### DireccionPedido
- `belongsTo` Pedido
- `belongsTo` Ubigeo

### Ubigeo
- `hasMany` DireccionPedido

### ProductoLibro
- `hasMany` CompraUsuario

## Notas Importantes

1. **Conexión Separada**: Todos los modelos del sistema de libros usan la conexión `mysql_libros`.

2. **Transacciones**: Las operaciones que involucran múltiples tablas usan transacciones para mantener la integridad de los datos.

3. **Logging**: El servicio incluye logging de errores para facilitar el debugging.

4. **Validación**: Se recomienda agregar validación de datos en el controlador antes de procesar las solicitudes.

5. **Middleware**: Todas las rutas están protegidas con el middleware de autenticación.

## Próximos Pasos

1. Configurar las variables de entorno en el archivo `.env`
2. Crear la base de datos del sistema de libros
3. Importar las tablas existentes
4. Probar las conexiones y funcionalidades
5. Agregar validaciones según los requisitos del negocio
6. Implementar interfaces de usuario si es necesario
