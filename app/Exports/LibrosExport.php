<?php

namespace App\Exports;

use App\Models\Pedido;
use App\Helpers\MetodosPagoHelper;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LibrosExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $filtros;

    public function __construct($filtros = [])
    {
        $this->filtros = $filtros;
    }

    public function collection()
    {
        $query = Pedido::with(['comprasUsuario.producto', 'direccionPedido.ubigeo'])
            ->orderBy('fecha_pedido', 'desc');

        // Log para debug
        \Log::info('Filtros aplicados en exportación:', $this->filtros);

        // Aplicar filtros
        if (isset($this->filtros['estado_pago']) && $this->filtros['estado_pago'] !== '') {
            $query->where('estadopago_ped', $this->filtros['estado_pago']);
            \Log::info('Filtro estado_pago aplicado: ' . $this->filtros['estado_pago']);
        }

        if (isset($this->filtros['email_cliente']) && $this->filtros['email_cliente'] !== '') {
            $query->where('email_cliente', 'like', '%' . $this->filtros['email_cliente'] . '%');
            \Log::info('Filtro email_cliente aplicado: ' . $this->filtros['email_cliente']);
        }

        if (isset($this->filtros['fecha_desde']) && $this->filtros['fecha_desde'] !== '') {
            $query->where('fecha_pedido', '>=', $this->filtros['fecha_desde'] . ' 00:00:00');
            \Log::info('Filtro fecha_desde aplicado: ' . $this->filtros['fecha_desde']);
        }

        if (isset($this->filtros['fecha_hasta']) && $this->filtros['fecha_hasta'] !== '') {
            $query->where('fecha_pedido', '<=', $this->filtros['fecha_hasta'] . ' 23:59:59');
            \Log::info('Filtro fecha_hasta aplicado: ' . $this->filtros['fecha_hasta']);
        }

        $result = $query->get();
        \Log::info('Total de registros exportados: ' . $result->count());
        
        return $result;
    }

    public function headings(): array
    {
        return [
            'ID Pedido',
            'Fecha',
            'Cliente',
            'Email',
            'Documento',
            'Método de Pago',
            'Estado de Pago',
            'Total',
            'Dirección',
            'Teléfono',
            'Ubigeo',
            'Productos'
        ];
    }

    public function map($pedido): array
    {
        // Obtener método de pago como texto
        $metodoPago = MetodosPagoHelper::getNombreMetodoPago($pedido->IdMetododepago);
        
        // Obtener productos como texto
        $productos = '';
        if ($pedido->comprasUsuario) {
            $productosArray = [];
            foreach ($pedido->comprasUsuario as $compra) {
                if ($compra->producto) {
                    $productosArray[] = $compra->producto->nombre_producto . ' (x' . $compra->cant_producto . ')';
                }
            }
            $productos = implode(', ', $productosArray);
        }

        // Obtener dirección
        $direccion = '';
        $telefono = '';
        $ubigeo = '';
        if ($pedido->direccionPedido) {
            $direccion = $pedido->direccionPedido->direccion_ped;
            $telefono = $pedido->direccionPedido->telf_ped;
            if ($pedido->direccionPedido->ubigeo) {
                $ubigeo = $pedido->direccionPedido->ubigeo->DESCRIPCION;
            }
        }

        return [
            $pedido->IdPedido,
            $pedido->fecha_pedido ? \Carbon\Carbon::parse($pedido->fecha_pedido)->format('d/m/Y H:i') : 'N/A',
            $pedido->nombre_cliente . ' ' . $pedido->apellidos_cliente,
            $pedido->email_cliente,
            $pedido->nro_documento,
            $metodoPago,
            ucfirst($pedido->estadopago_ped ?? 'N/A'),
            'S/ ' . number_format($pedido->total_ped ?? 0, 2),
            $direccion,
            $telefono,
            $ubigeo,
            $productos
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E2EFDA']
                ]
            ]
        ];
    }
}
