<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\CompraUsuario;
use App\Models\DireccionPedido;
use App\Models\Ubigeo;
use App\Models\ProductoLibro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LibrosController extends Controller
{
    /**
     * Obtener todos los pedidos del sistema de libros
     */
    public function getPedidos()
    {
        try {
            $pedidos = Pedido::with(['comprasUsuario.producto', 'direccionPedido.ubigeo'])
                ->orderBy('fecha_pedido', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $pedidos
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener pedidos: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener un pedido específico por ID
     */
    public function getPedido($id)
    {
        try {
            $pedido = Pedido::with(['comprasUsuario.producto', 'direccionPedido.ubigeo'])
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $pedido
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener pedido: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crear un nuevo pedido en el sistema de libros
     */
    public function crearPedido(Request $request)
    {
        try {
            DB::connection('mysql_libros')->beginTransaction();

            // Crear el pedido
            $pedido = Pedido::create([
                'IdRepartidor' => $request->IdRepartidor ?? 0,
                'fecha_pedido' => now(),
                'IdMetododepago' => $request->IdMetododepago,
                'total_ped' => $request->total_ped,
                'estadopago_ped' => $request->estadopago_ped,
                'IdCarrito' => $request->IdCarrito,
                'nombre_cliente' => $request->nombre_cliente,
                'apellidos_cliente' => $request->apellidos_cliente,
                'email_cliente' => $request->email_cliente,
                'IdTipoDocumento' => $request->IdTipoDocumento,
                'nro_documento' => $request->nro_documento,
                'comprobante_tipo' => $request->comprobante_tipo,
                'migracion_ped' => $request->migracion_ped ?? false
            ]);

            // Crear las compras del usuario si se proporcionan
            if ($request->has('compras')) {
                foreach ($request->compras as $compra) {
                    CompraUsuario::create([
                        'cant_producto' => $compra['cant_producto'],
                        'fk_IdProducto_compra' => $compra['fk_IdProducto_compra'],
                        'id_compras_ped' => $pedido->IdPedido,
                        'fecha_reg_compra' => now(),
                        'subtotal_compra' => $compra['subtotal_compra'],
                        'email_cliente' => $request->email_cliente,
                        'precio_compra' => $compra['precio_compra']
                    ]);
                }
            }

            // Crear la dirección del pedido si se proporciona
            if ($request->has('direccion')) {
                DireccionPedido::create([
                    'direccion_ped' => $request->direccion['direccion_ped'],
                    'comentario_ped' => $request->direccion['comentario_ped'] ?? '',
                    'fk_IdUbigeoDireccion' => $request->direccion['fk_IdUbigeoDireccion'],
                    'fk_IdPedido' => $pedido->IdPedido,
                    'telf_ped' => $request->direccion['telf_ped']
                ]);
            }

            DB::connection('mysql_libros')->commit();

            return response()->json([
                'success' => true,
                'message' => 'Pedido creado exitosamente',
                'data' => $pedido->load(['comprasUsuario.producto', 'direccionPedido.ubigeo'])
            ], 201);

        } catch (\Exception $e) {
            DB::connection('mysql_libros')->rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al crear pedido: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener productos del sistema de libros
     */
    public function getProductos()
    {
        try {
            $productos = ProductoLibro::where('condicion_producto', 1)
                ->orderBy('nombre_producto')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $productos
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener productos: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener ubigeos
     */
    public function getUbigeos()
    {
        try {
            $ubigeos = Ubigeo::orderBy('DESCRIPCION')->get();

            return response()->json([
                'success' => true,
                'data' => $ubigeos
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener ubigeos: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar estado de pago de un pedido
     */
    public function actualizarEstadoPago(Request $request, $id)
    {
        try {
            $pedido = Pedido::findOrFail($id);
            $pedido->update([
                'estadopago_ped' => $request->estadopago_ped,
                'log_res_pago' => $request->log_res_pago ?? null
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Estado de pago actualizado exitosamente',
                'data' => $pedido
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar estado de pago: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancelar un pedido
     */
    public function cancelarPedido(Request $request, $id)
    {
        try {
            $pedido = Pedido::findOrFail($id);
            $pedido->update([
                'hora_cancelacion' => now(),
                'fecha_cancelacion' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pedido cancelado exitosamente',
                'data' => $pedido
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cancelar pedido: ' . $e->getMessage()
            ], 500);
        }
    }
}
