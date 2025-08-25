<?php

namespace App\Services;

use App\Models\Pedido;
use App\Models\CompraUsuario;
use App\Models\DireccionPedido;
use App\Models\Ubigeo;
use App\Models\ProductoLibro;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Helpers\EstadosPagoHelper;

class LibrosService
{
    /**
     * Obtener todos los pedidos con sus relaciones
     */
    public function obtenerPedidos($filtros = [])
    {
        try {
            $query = Pedido::with(['comprasUsuario.producto', 'direccionPedido.ubigeo']);

            // Aplicar filtros si se proporcionan
            if (isset($filtros['estado_pago'])) {
                $query->where('estadopago_ped', $filtros['estado_pago']);
            }

            if (isset($filtros['fecha_desde'])) {
                $query->where('fecha_pedido', '>=', $filtros['fecha_desde'] . ' 00:00:00');
            }

            if (isset($filtros['fecha_hasta'])) {
                $query->where('fecha_pedido', '<=', $filtros['fecha_hasta'] . ' 23:59:59');
            }

            if (isset($filtros['email_cliente'])) {
                $query->where('email_cliente', 'like', '%' . $filtros['email_cliente'] . '%');
            }

            return $query->orderBy('fecha_pedido', 'desc')->get();
        } catch (\Exception $e) {
            Log::error('Error al obtener pedidos: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Crear un nuevo pedido con todas sus relaciones
     */
    public function crearPedido($datos)
    {
        try {
            DB::connection('mysql_libros')->beginTransaction();

            // Crear el pedido
            $pedido = Pedido::create([
                'IdRepartidor' => $datos['IdRepartidor'] ?? 0,
                'fecha_pedido' => now(),
                'IdMetododepago' => $datos['IdMetododepago'],
                'total_ped' => $datos['total_ped'],
                'estadopago_ped' => $datos['estadopago_ped'],
                'IdCarrito' => $datos['IdCarrito'] ?? 0,
                'nombre_cliente' => $datos['nombre_cliente'],
                'apellidos_cliente' => $datos['apellidos_cliente'],
                'email_cliente' => $datos['email_cliente'],
                'IdTipoDocumento' => $datos['IdTipoDocumento'],
                'nro_documento' => $datos['nro_documento'],
                'comprobante_tipo' => $datos['comprobante_tipo'],
                'migracion_ped' => $datos['migracion_ped'] ?? false
            ]);

            // Crear las compras del usuario
            if (isset($datos['compras']) && is_array($datos['compras'])) {
                foreach ($datos['compras'] as $compra) {
                    CompraUsuario::create([
                        'cant_producto' => $compra['cant_producto'],
                        'fk_IdProducto_compra' => $compra['fk_IdProducto_compra'],
                        'id_compras_ped' => $pedido->IdPedido,
                        'fecha_reg_compra' => now(),
                        'subtotal_compra' => $compra['subtotal_compra'],
                        'email_cliente' => $datos['email_cliente'],
                        'precio_compra' => $compra['precio_compra']
                    ]);
                }
            }

            // Crear la dirección del pedido
            if (isset($datos['direccion'])) {
                DireccionPedido::create([
                    'direccion_ped' => $datos['direccion']['direccion_ped'],
                    'comentario_ped' => $datos['direccion']['comentario_ped'] ?? '',
                    'fk_IdUbigeoDireccion' => $datos['direccion']['fk_IdUbigeoDireccion'],
                    'fk_IdPedido' => $pedido->IdPedido,
                    'telf_ped' => $datos['direccion']['telf_ped']
                ]);
            }

            DB::connection('mysql_libros')->commit();

            return $pedido->load(['comprasUsuario.producto', 'direccionPedido.ubigeo']);
        } catch (\Exception $e) {
            DB::connection('mysql_libros')->rollBack();
            Log::error('Error al crear pedido: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Actualizar estado de pago de un pedido
     */
    public function actualizarEstadoPago($idPedido, $estadoPago, $logResPago = null)
    {
        try {
            $pedido = Pedido::findOrFail($idPedido);
            $pedido->update([
                'estadopago_ped' => $estadoPago,
                'log_res_pago' => $logResPago
            ]);

            return $pedido;
        } catch (\Exception $e) {
            Log::error('Error al actualizar estado de pago: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Actualizar un pedido completo con todas sus relaciones
     */
    public function actualizarPedido($idPedido, $datos)
    {
        try {
            DB::connection('mysql_libros')->beginTransaction();

            // Actualizar el pedido
            $pedido = Pedido::findOrFail($idPedido);
            
            // Verificar si el estado actual permite edición
            if (!EstadosPagoHelper::permiteEdicion($pedido->estadopago_ped)) {
                throw new \Exception('No se puede editar un pedido con estado: ' . EstadosPagoHelper::getNombreEstadoPago($pedido->estadopago_ped));
            }
            $pedido->update([
                'IdRepartidor' => $datos['IdRepartidor'] ?? 0,
                'IdMetododepago' => $datos['IdMetododepago'],
                'total_ped' => $datos['total_ped'],
                'estadopago_ped' => $datos['estadopago_ped'],
                'IdCarrito' => $datos['IdCarrito'] ?? 0,
                'nombre_cliente' => $datos['nombre_cliente'],
                'apellidos_cliente' => $datos['apellidos_cliente'],
                'email_cliente' => $datos['email_cliente'],
                'IdTipoDocumento' => $datos['IdTipoDocumento'],
                'nro_documento' => $datos['nro_documento'],
                'comprobante_tipo' => $datos['comprobante_tipo'],
                'migracion_ped' => $datos['migracion_ped'] ?? false
            ]);

            // Eliminar compras existentes
            CompraUsuario::where('id_compras_ped', $idPedido)->delete();

            // Crear las nuevas compras del usuario
            if (isset($datos['compras']) && is_array($datos['compras'])) {
                foreach ($datos['compras'] as $compra) {
                    CompraUsuario::create([
                        'cant_producto' => $compra['cant_producto'],
                        'fk_IdProducto_compra' => $compra['fk_IdProducto_compra'],
                        'id_compras_ped' => $pedido->IdPedido,
                        'fecha_reg_compra' => now(),
                        'subtotal_compra' => $compra['subtotal_compra'],
                        'email_cliente' => $datos['email_cliente'],
                        'precio_compra' => $compra['precio_compra']
                    ]);
                }
            }

            // Actualizar la dirección del pedido
            if (isset($datos['direccion'])) {
                $direccion = DireccionPedido::where('fk_IdPedido', $idPedido)->first();
                if ($direccion) {
                    $direccion->update([
                        'direccion_ped' => $datos['direccion']['direccion_ped'],
                        'comentario_ped' => $datos['direccion']['comentario_ped'] ?? '',
                        'fk_IdUbigeoDireccion' => $datos['direccion']['fk_IdUbigeoDireccion'],
                        'telf_ped' => $datos['direccion']['telf_ped']
                    ]);
                } else {
                    // Si no existe la dirección, crearla
                    DireccionPedido::create([
                        'direccion_ped' => $datos['direccion']['direccion_ped'],
                        'comentario_ped' => $datos['direccion']['comentario_ped'] ?? '',
                        'fk_IdUbigeoDireccion' => $datos['direccion']['fk_IdUbigeoDireccion'],
                        'fk_IdPedido' => $pedido->IdPedido,
                        'telf_ped' => $datos['direccion']['telf_ped']
                    ]);
                }
            }

            DB::connection('mysql_libros')->commit();

            return $pedido->load(['comprasUsuario.producto', 'direccionPedido.ubigeo']);
        } catch (\Exception $e) {
            DB::connection('mysql_libros')->rollBack();
            Log::error('Error al actualizar pedido: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Cancelar un pedido
     */
    public function cancelarPedido($idPedido)
    {
        try {
            $pedido = Pedido::findOrFail($idPedido);
            
            // Verificar si el estado permite cancelación
            if (!EstadosPagoHelper::permiteCancelacion($pedido->estadopago_ped)) {
                throw new \Exception('No se puede cancelar un pedido con estado: ' . EstadosPagoHelper::getNombreEstadoPago($pedido->estadopago_ped));
            }
            
            $pedido->update([
                'estadopago_ped' => 'cancelado',
                'hora_cancelacion' => now(),
                'fecha_cancelacion' => now()
            ]);

            return $pedido;
        } catch (\Exception $e) {
            Log::error('Error al cancelar pedido: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Obtener productos activos
     */
    public function obtenerProductos($filtros = [])
    {
        try {
            $query = ProductoLibro::where('condicion_producto', 1);

            if (isset($filtros['categoria'])) {
                $query->where('IdCategoria', $filtros['categoria']);
            }

            if (isset($filtros['busqueda'])) {
                $query->where('nombre_producto', 'like', '%' . $filtros['busqueda'] . '%');
            }

            return $query->orderBy('nombre_producto')->get();
        } catch (\Exception $e) {
            Log::error('Error al obtener productos: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Obtener ubigeos (solo distritos de LIMA)
     */
    public function obtenerUbigeos($busqueda = null)
    {
        try {
            $query = Ubigeo::query();

            // Filtrar solo distritos de LIMA (ubigeos que empiezan con "15")
            $query->where('IDUBIGEO', 'like', '15%');

            if ($busqueda) {
                $query->where('DESCRIPCION', 'like', '%' . $busqueda . '%');
            }

            return $query->orderBy('DESCRIPCION')->get();
        } catch (\Exception $e) {
            Log::error('Error al obtener ubigeos: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Obtener un pedido específico
     */
    public function obtenerPedido($id)
    {
        try {
            return Pedido::with(['comprasUsuario.producto', 'direccionPedido.ubigeo'])
                ->findOrFail($id);
        } catch (\Exception $e) {
            Log::error('Error al obtener pedido: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Obtener estadísticas de pedidos
     */
    public function obtenerEstadisticas($fechaDesde = null, $fechaHasta = null)
    {
        try {
            $query = Pedido::query();

            if ($fechaDesde) {
                $query->where('fecha_pedido', '>=', $fechaDesde);
            }

            if ($fechaHasta) {
                $query->where('fecha_pedido', '<=', $fechaHasta);
            }

            $totalPedidos = $query->count();
            $totalVentas = $query->sum('total_ped');
            $pedidosPendientes = $query->where('estadopago_ped', 'pendiente')->count();
            $pedidosPagoAceptado = $query->where('estadopago_ped', 'pago aceptado')->count();

            return [
                'total_pedidos' => $totalPedidos,
                'total_ventas' => $totalVentas,
                'pedidos_pendientes' => $pedidosPendientes,
                'pedidos_pago_aceptado' => $pedidosPagoAceptado
            ];
        } catch (\Exception $e) {
            Log::error('Error al obtener estadísticas: ' . $e->getMessage());
            throw $e;
        }
    }
}
