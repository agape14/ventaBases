<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\LibrosService;
use App\Helpers\MetodosPagoHelper;
use App\Exports\LibrosExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class LibrosController extends Controller
{
    protected $librosService;

    public function __construct(LibrosService $librosService)
    {
        $this->librosService = $librosService;
    }

    /**
     * Mostrar listado de ventas
     */
    public function index()
    {
        return view('admin.libros.index');
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        return view('admin.libros.create');
    }

    /**
     * Almacenar nueva venta
     */
    public function store(Request $request)
    {
        try {
            // Validar datos
            $validator = Validator::make($request->all(), [
                'nombre_cliente' => 'required|string|max:45',
                'apellidos_cliente' => 'required|string|max:45',
                'email_cliente' => 'required|email|max:45',
                'nro_documento' => 'required|string|max:11',
                'IdTipoDocumento' => 'required|integer',
                'IdMetododepago' => 'required|string|max:45',
                'estadopago_ped' => 'required|string|max:45',
                'comprobante_tipo' => 'required|string|max:45',
                'total_ped' => 'required|numeric|min:0',
                'IdRepartidor' => 'nullable|integer',
                'IdCarrito' => 'nullable|integer',
                'direccion.direccion_ped' => 'required|string|max:45',
                'direccion.telf_ped' => 'required|string|max:100',
                'direccion.fk_IdUbigeoDireccion' => 'required|string|max:6',
                'direccion.comentario_ped' => 'nullable|string',
                'compras' => 'required|array|min:1',
                'compras.*.fk_IdProducto_compra' => 'required|integer',
                'compras.*.cant_producto' => 'required|integer|min:1',
                'compras.*.precio_compra' => 'required|numeric|min:0',
                'compras.*.subtotal_compra' => 'required|numeric|min:0',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Preparar datos
            $datos = $request->all();
            $datos['migracion_ped'] = false;

            // Crear venta
            $pedido = $this->librosService->crearPedido($datos);

            return response()->json([
                'success' => true,
                'message' => 'Venta registrada exitosamente',
                'data' => $pedido
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar la venta: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        try {
            $pedido = $this->librosService->obtenerPedido($id);
            return view('admin.libros.edit', compact('pedido'));
        } catch (\Exception $e) {
            return redirect()->route('admin#libros.index')
                ->with('error', 'Error al cargar la venta: ' . $e->getMessage());
        }
    }

    /**
     * Actualizar venta
     */
    public function update(Request $request, $id)
    {
        try {
            // Validar datos
            $validator = Validator::make($request->all(), [
                'nombre_cliente' => 'required|string|max:45',
                'apellidos_cliente' => 'required|string|max:45',
                'email_cliente' => 'required|email|max:45',
                'nro_documento' => 'required|string|max:11',
                'IdTipoDocumento' => 'required|integer',
                'IdMetododepago' => 'required|string|max:45',
                'estadopago_ped' => 'required|string|max:45',
                'comprobante_tipo' => 'required|string|max:45',
                'total_ped' => 'required|numeric|min:0',
                'IdRepartidor' => 'nullable|integer',
                'IdCarrito' => 'nullable|integer',
                'direccion.direccion_ped' => 'required|string|max:45',
                'direccion.telf_ped' => 'required|string|max:100',
                'direccion.fk_IdUbigeoDireccion' => 'required|string|max:6',
                'direccion.comentario_ped' => 'nullable|string',
                'compras' => 'required|array|min:1',
                'compras.*.fk_IdProducto_compra' => 'required|integer',
                'compras.*.cant_producto' => 'required|integer|min:1',
                'compras.*.precio_compra' => 'required|numeric|min:0',
                'compras.*.subtotal_compra' => 'required|numeric|min:0',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Preparar datos
            $datos = $request->all();
            $datos['migracion_ped'] = false;

            // Actualizar venta completa
            $pedido = $this->librosService->actualizarPedido($id, $datos);

            return response()->json([
                'success' => true,
                'message' => 'Venta actualizada exitosamente',
                'data' => $pedido
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la venta: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancelar venta
     */
    public function cancelar($id)
    {
        try {
            $pedido = $this->librosService->cancelarPedido($id);

            return response()->json([
                'success' => true,
                'message' => 'Venta cancelada exitosamente',
                'data' => $pedido
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cancelar la venta: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener pedidos con filtros y paginación
     */
    public function getPedidos(Request $request)
    {
        try {
            $filtros = [
                'estado_pago' => $request->estado_pago,
                'fecha_desde' => $request->fecha_desde,
                'fecha_hasta' => $request->fecha_hasta,
                'email_cliente' => $request->email_cliente,
            ];

            // Limpiar filtros vacíos
            $filtros = array_filter($filtros, function($value) {
                return $value !== null && $value !== '';
            });

            // Si no hay filtros, verificar si el usuario es administrador
            if (empty($filtros)) {
                // Solo mostrar datos iniciales si el usuario es administrador
                if (auth()->user()->role === 'admin') {
                    $pedidos = $this->librosService->obtenerPedidos([]);
                    $pedidos = $pedidos->take(10); // Solo las 10 últimas
                    $perPage = 10;
                    $page = 1;
                    $total = $pedidos->count();
                    
                    $meta = [
                        'current_page' => 1,
                        'last_page' => 1,
                        'per_page' => 10,
                        'total' => $total,
                        'from' => 1,
                        'to' => $total,
                    ];
                } else {
                    // Para usuarios no administradores, no mostrar nada inicialmente
                    $pedidos = collect([]);
                    $meta = [
                        'current_page' => 1,
                        'last_page' => 1,
                        'per_page' => 10,
                        'total' => 0,
                        'from' => 0,
                        'to' => 0,
                    ];
                }
            } else {
                // Si hay filtros, usar paginación normal para todos los usuarios
                $pedidos = $this->librosService->obtenerPedidos($filtros);
                $perPage = 15;
                $page = $request->get('page', 1);
                $offset = ($page - 1) * $perPage;
                
                $total = $pedidos->count();
                $pedidosPaginated = $pedidos->slice($offset, $perPage);
                $pedidos = $pedidosPaginated;
                
                $meta = [
                    'current_page' => (int)$page,
                    'last_page' => ceil($total / $perPage),
                    'per_page' => $perPage,
                    'total' => $total,
                    'from' => $offset + 1,
                    'to' => min($offset + $perPage, $total),
                ];
            }

            // Mapear métodos de pago y agregar logs de debug
            $pedidosMapeados = $pedidos->map(function($pedido) {
                // Log para debug
                Log::info('Pedido ID: ' . $pedido->IdPedido . ', Método Pago: ' . $pedido->IdMetododepago);
                
                // Mapear método de pago
                $pedido->metodo_pago_nombre = MetodosPagoHelper::getNombreMetodoPago($pedido->IdMetododepago);
                
                return $pedido;
            });

            return response()->json([
                'success' => true,
                'data' => $pedidosMapeados->values(),
                'meta' => $meta
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener pedidos: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener un pedido específico
     */
    public function getPedido($id)
    {
        try {
            $pedido = $this->librosService->obtenerPedido($id);

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
     * Obtener productos
     */
    public function getProductos(Request $request)
    {
        try {
            $filtros = [
                'categoria' => $request->categoria,
                'busqueda' => $request->busqueda,
            ];

            $productos = $this->librosService->obtenerProductos($filtros);

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
    public function getUbigeos(Request $request)
    {
        try {
            $ubigeos = $this->librosService->obtenerUbigeos($request->busqueda);

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
     * Obtener estadísticas
     */
    public function estadisticas(Request $request)
    {
        try {
            $estadisticas = $this->librosService->obtenerEstadisticas(
                $request->fecha_desde,
                $request->fecha_hasta
            );

            return response()->json([
                'success' => true,
                'data' => $estadisticas
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener estadísticas: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Exportar a Excel
     */
    public function exportarExcel(Request $request)
    {
        try {
            $filtros = $request->only(['estado_pago', 'email_cliente', 'fecha_desde', 'fecha_hasta']);
            
            $nombreArchivo = 'ventas_libros_' . date('Y-m-d_H-i-s') . '.xlsx';
            
            return Excel::download(new LibrosExport($filtros), $nombreArchivo);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al exportar a Excel: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Exportar a PDF
     */
    public function exportarPdf(Request $request)
    {
        try {
            $filtros = $request->only(['estado_pago', 'email_cliente', 'fecha_desde', 'fecha_hasta']);
            
            // Obtener datos con filtros
            $pedidos = $this->librosService->obtenerPedidos($filtros);
            
            $nombreArchivo = 'ventas_libros_' . date('Y-m-d_H-i-s') . '.pdf';
            
            $pdf = Pdf::loadView('admin.libros.pdf', [
                'pedidos' => $pedidos,
                'filtros' => $filtros
            ]);
            
            return $pdf->download($nombreArchivo);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al exportar a PDF: ' . $e->getMessage()
            ], 500);
        }
    }
}
