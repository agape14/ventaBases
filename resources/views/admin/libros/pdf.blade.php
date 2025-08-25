<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Ventas de Libros</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            line-height: 1.2;
            margin: 0;
            padding: 10px;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #333;
            padding-bottom: 8px;
        }
        .header h1 {
            margin: 0;
            color: #333;
            font-size: 18px;
        }
        .header p {
            margin: 5px 0;
            font-size: 11px;
        }
        .filtros {
            margin-bottom: 15px;
            padding: 8px;
            background-color: #f5f5f5;
            border-radius: 3px;
            font-size: 9px;
        }
        .filtros h3 {
            margin: 0 0 8px 0;
            color: #333;
            font-size: 11px;
        }
        .filtros p {
            margin: 3px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 9px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 4px 6px;
            text-align: left;
            vertical-align: top;
            word-wrap: break-word;
            max-width: 0;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            font-size: 9px;
        }
        .total-row {
            background-color: #e8f5e8;
            font-weight: bold;
        }
        .estado-pagado {
            color: #28a745;
        }
        .estado-pendiente {
            color: #ffc107;
        }
        .estado-cancelado {
            color: #dc3545;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 8px;
            color: #666;
        }
        .text-truncate {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 80px;
        }
        .text-small {
            font-size: 8px;
        }
        .col-id { width: 4%; }
        .col-fecha { width: 8%; }
        .col-cliente { width: 12%; }
        .col-email { width: 15%; }
        .col-doc { width: 8%; }
        .col-metodo { width: 10%; }
        .col-estado { width: 6%; }
        .col-total { width: 8%; }
        .col-direccion { width: 15%; }
        .col-telefono { width: 8%; }
        .col-ubigeo { width: 6%; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de Ventas de Libros</h1>
        <p>Generado el: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>

    @if(!empty($filtros))
    <div class="filtros">
        <h3>Filtros Aplicados:</h3>
        @if(isset($filtros['estado_pago']) && $filtros['estado_pago'] !== '')
            <p><strong>Estado:</strong> {{ ucfirst($filtros['estado_pago']) }}</p>
        @endif
        @if(isset($filtros['email_cliente']) && $filtros['email_cliente'] !== '')
            <p><strong>Email:</strong> {{ $filtros['email_cliente'] }}</p>
        @endif
        @if(isset($filtros['fecha_desde']))
            <p><strong>Desde:</strong> {{ $filtros['fecha_desde'] }}</p>
        @endif
        @if(isset($filtros['fecha_hasta']))
            <p><strong>Hasta:</strong> {{ $filtros['fecha_hasta'] }}</p>
        @endif
    </div>
    @endif

    <table>
        <thead>
            <tr>
                <th class="col-id">ID</th>
                <th class="col-fecha">Fecha</th>
                <th class="col-cliente">Cliente</th>
                <th class="col-email">Email</th>
                <th class="col-doc">Doc.</th>
                <th class="col-metodo">Método</th>
                <th class="col-estado">Estado</th>
                <th class="col-total">Total</th>
                <th class="col-direccion">Dirección</th>
                <th class="col-telefono">Teléfono</th>
                <th class="col-ubigeo">Ubigeo</th>
            </tr>
        </thead>
        <tbody>
            @php $totalGeneral = 0; @endphp
            @foreach($pedidos as $pedido)
                @php $totalGeneral += $pedido->total_ped ?? 0; @endphp
                <tr>
                    <td class="col-id">{{ $pedido->IdPedido }}</td>
                    <td class="col-fecha">{{ $pedido->fecha_pedido ? \Carbon\Carbon::parse($pedido->fecha_pedido)->format('d/m/Y') : 'N/A' }}</td>
                    <td class="col-cliente text-truncate" title="{{ $pedido->nombre_cliente }} {{ $pedido->apellidos_cliente }}">
                        {{ Str::limit($pedido->nombre_cliente . ' ' . $pedido->apellidos_cliente, 20) }}
                    </td>
                    <td class="col-email text-truncate" title="{{ $pedido->email_cliente }}">
                        {{ Str::limit($pedido->email_cliente, 25) }}
                    </td>
                    <td class="col-doc">{{ Str::limit($pedido->nro_documento, 12) }}</td>
                    <td class="col-metodo text-truncate" title="{{ \App\Helpers\MetodosPagoHelper::getNombreMetodoPago($pedido->IdMetododepago) }}">
                        {{ Str::limit(\App\Helpers\MetodosPagoHelper::getNombreMetodoPago($pedido->IdMetododepago), 15) }}
                    </td>
                                       <td class="col-estado estado-{{ $pedido->estadopago_ped ?? 'pendiente' }}">
                       {{ Str::limit(\App\Helpers\EstadosPagoHelper::getNombreEstadoPago($pedido->estadopago_ped), 8) }}
                   </td>
                    <td class="col-total">S/ {{ number_format($pedido->total_ped ?? 0, 2) }}</td>
                    <td class="col-direccion text-truncate" title="{{ $pedido->direccionPedido->direccion_ped ?? 'N/A' }}">
                        {{ Str::limit($pedido->direccionPedido->direccion_ped ?? 'N/A', 30) }}
                    </td>
                    <td class="col-telefono">{{ Str::limit($pedido->direccionPedido->telf_ped ?? 'N/A', 12) }}</td>
                    <td class="col-ubigeo text-truncate" title="{{ $pedido->direccionPedido->ubigeo->DESCRIPCION ?? 'N/A' }}">
                        {{ Str::limit($pedido->direccionPedido->ubigeo->DESCRIPCION ?? 'N/A', 15) }}
                    </td>
                </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="7"><strong>TOTAL GENERAL:</strong></td>
                <td colspan="4"><strong>S/ {{ number_format($totalGeneral, 2) }}</strong></td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>Reporte generado automáticamente por el sistema de ventas de libros</p>
        <p>Total de registros: {{ count($pedidos) }} | Total ventas: S/ {{ number_format($totalGeneral, 2) }}</p>
    </div>
</body>
</html>
