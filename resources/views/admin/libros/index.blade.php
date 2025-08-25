@extends('admin.layouts.app')

@section('title', 'Ventas de Libros')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Ventas de Libros</h3>
                                            <div class="card-tools">
                            <div class="btn-group">
                                <button type="button" class="btn btn-success" id="exportar-excel">
                                    <i class="fas fa-file-excel"></i> Exportar Excel
                                </button>
                                <button type="button" class="btn btn-danger" id="exportar-pdf">
                                    <i class="fas fa-file-pdf"></i> Exportar PDF
                                </button>
                            </div>
                            <a href="{{ route('admin#libros.create') }}" class="btn btn-primary ml-2">
                                <i class="fas fa-plus"></i> Nueva Venta
                            </a>
                        </div>
                </div>
                <div class="card-body">
                    <!-- Filtros -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label>Estado de Pago:</label>
                            <select class="form-control" id="filtro-estado">
                                <option value="">Todos</option>
                                <option value="pendiente">Pendiente</option>
                                <option value="pagado">Pagado</option>
                                <option value="cancelado">Cancelado</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Fecha Desde:</label>
                            <input type="date" class="form-control" id="filtro-fecha-desde">
                        </div>
                        <div class="col-md-3">
                            <label>Fecha Hasta:</label>
                            <input type="date" class="form-control" id="filtro-fecha-hasta">
                        </div>
                        <div class="col-md-3">
                            <label>Email Cliente:</label>
                            <input type="text" class="form-control" id="filtro-email" placeholder="Buscar por email...">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-12">
                            <button type="button" class="btn btn-info" id="aplicar-filtros">
                                <i class="fas fa-search"></i> Aplicar Filtros
                            </button>
                            <button type="button" class="btn btn-secondary" id="limpiar-filtros">
                                <i class="fas fa-times"></i> Limpiar Filtros
                            </button>
                        </div>
                    </div>
                    
                    <!-- Tabla de ventas -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="ventas-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Cliente</th>
                                    <th>Email</th>
                                    <th>Total</th>
                                    <th>Estado</th>
                                    <th>Método Pago</th>
                                    <th>Fecha</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Los datos se cargarán dinámicamente -->
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Paginación -->
                    <div class="d-flex justify-content-center mt-3">
                        <nav aria-label="Paginación de ventas">
                            <ul class="pagination" id="pagination">
                                <!-- La paginación se generará dinámicamente -->
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Detalles -->
<div class="modal fade" id="detallesModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles de la Venta</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="detalles-content">
                <!-- El contenido se cargará dinámicamente -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmación -->
<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Acción</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="confirm-message"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirm-action">Confirmar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
$(document).ready(function() {
    console.log('📄 Documento listo - jQuery disponible:', typeof $ !== 'undefined');
    console.log('🎯 Botón aplicar filtros encontrado:', $('#aplicar-filtros').length > 0);
    
    let currentPage = 1;
    let currentFilters = {};
    
    // Establecer fecha actual por defecto en "Fecha Hasta"
    let today = new Date();
    let todayString = today.toISOString().split('T')[0];
    $('#filtro-fecha-desde').val(todayString);
    $('#filtro-fecha-hasta').val(todayString);
    // Cargar ventas
    function cargarVentas(page = 1) {
        console.log('🚀 Función cargarVentas ejecutada con página:', page);
        console.log('📊 Filtros actuales:', currentFilters);
        
        currentPage = page;
        let params = {
            page: page,
            ...currentFilters
        };
        
        console.log('📡 Parámetros de la petición:', params);
        console.log('🌐 URL de la petición:', '{{ route("admin#libros.pedidos") }}');
        
        $.get('{{ route("admin#libros.pedidos") }}', params, function(response) {
            console.log('✅ Respuesta recibida:', response);
            if (response.success) {
                console.log('📋 Datos recibidos:', response.data.length, 'registros');
                renderizarTabla(response.data);
                renderizarPaginacion(response.meta);
            } else {
                console.error('❌ Error en la respuesta:', response.message);
                alert('Error al cargar las ventas: ' + response.message);
            }
        }).fail(function(xhr, status, error) {
            console.error('❌ Error en la petición AJAX:', error);
            console.error('📊 Estado:', status);
            console.error('📄 Respuesta:', xhr.responseText);
            alert('Error al cargar las ventas: ' + error);
        });
    }
    
    // Renderizar tabla
    function renderizarTabla(ventas) {
        let tbody = $('#ventas-table tbody');
        tbody.empty();
        
        if (ventas.length === 0) {
            tbody.append('<tr><td colspan="8" class="text-center">No se encontraron ventas</td></tr>');
            return;
        }
        
        ventas.forEach(function(venta) {
            let estadoClass = '';
            switch(venta.estadopago_ped) {
                case 'pagado': estadoClass = 'badge-success'; break;
                case 'pendiente': estadoClass = 'badge-warning'; break;
                case 'cancelado': estadoClass = 'badge-danger'; break;
                default: estadoClass = 'badge-secondary';
            }
            
            let row = `
                <tr>
                    <td>${venta.IdPedido}</td>
                    <td>${venta.nombre_cliente} ${venta.apellidos_cliente}</td>
                    <td>${venta.email_cliente}</td>
                                         <td>S/ ${parseFloat(venta.total_ped).toFixed(2)}</td>
                     <td><span class="badge ${estadoClass}">${venta.estadopago_ped}</span></td>
                     <td>${venta.metodo_pago_nombre || venta.IdMetododepago || 'No definido'}</td>
                     <td>${new Date(venta.fecha_pedido).toLocaleDateString()}</td>
                    <td>
                        <button class="btn btn-sm btn-info ver-detalles" data-id="${venta.IdPedido}">
                            <i class="fas fa-eye"></i>
                        </button>
                        ${venta.estadopago_ped !== 'cancelado' && ['1', '5', '6'].includes(venta.IdMetododepago) ? 
                            `<button class="btn btn-sm btn-warning editar-venta" data-id="${venta.IdPedido}">
                                <i class="fas fa-edit"></i>
                            </button>` : ''
                        }
                        ${venta.estadopago_ped !== 'cancelado' ? 
                            `<button class="btn btn-sm btn-danger cancelar-venta" data-id="${venta.IdPedido}">
                                <i class="fas fa-times"></i>
                            </button>` : ''
                        }
                    </td>
                </tr>
            `;
            tbody.append(row);
        });
    }
    
    // Renderizar paginación
    function renderizarPaginacion(meta) {
        let pagination = $('#pagination');
        pagination.empty();
        
        if (meta.last_page <= 1) return;
        
        // Botón anterior
        if (meta.current_page > 1) {
            pagination.append(`
                <li class="page-item">
                    <a class="page-link" href="#" data-page="${meta.current_page - 1}">Anterior</a>
                </li>
            `);
        }
        
        // Páginas
        for (let i = 1; i <= meta.last_page; i++) {
            let active = i === meta.current_page ? 'active' : '';
            pagination.append(`
                <li class="page-item ${active}">
                    <a class="page-link" href="#" data-page="${i}">${i}</a>
                </li>
            `);
        }
        
        // Botón siguiente
        if (meta.current_page < meta.last_page) {
            pagination.append(`
                <li class="page-item">
                    <a class="page-link" href="#" data-page="${meta.current_page + 1}">Siguiente</a>
                </li>
            `);
        }
    }
    
    // Ver detalles
    $(document).on('click', '.ver-detalles', function() {
        let id = $(this).data('id');
        
        $.get(`{{ route("admin#libros.pedidos") }}/${id}`, function(response) {
            if (response.success) {
                let venta = response.data;
                
                // Mapear método de pago a texto
                let metodoPagoTexto = '';
                switch(venta.IdMetododepago) {
                    case '1': metodoPagoTexto = 'Efectivo'; break;
                    case '2': metodoPagoTexto = 'Tarjeta de Crédito'; break;
                    case '3': metodoPagoTexto = 'Tarjeta de Débito'; break;
                    case '4': metodoPagoTexto = 'Transferencia Bancaria'; break;
                    case '5': metodoPagoTexto = 'Yape'; break;
                    case '6': metodoPagoTexto = 'Plin'; break;
                    default: metodoPagoTexto = 'N/A';
                }
                
                let detalles = `
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Información del Cliente</h6>
                            <p><strong>Nombre:</strong> ${venta.nombre_cliente} ${venta.apellidos_cliente}</p>
                            <p><strong>Email:</strong> ${venta.email_cliente}</p>
                            <p><strong>Documento:</strong> ${venta.nro_documento}</p>
                        </div>
                        <div class="col-md-6">
                            <h6>Información del Pedido</h6>
                            <p><strong>Total:</strong> S/ ${parseFloat(venta.total_ped).toFixed(2)}</p>
                            <p><strong>Estado:</strong> ${venta.estadopago_ped}</p>
                            <p><strong>Método de Pago:</strong> ${metodoPagoTexto}</p>
                            <p><strong>Fecha:</strong> ${new Date(venta.fecha_pedido).toLocaleString()}</p>
                        </div>
                    </div>
                `;
                
                if (venta.direccion_pedido) {
                    detalles += `
                        <hr>
                        <h6>Dirección de Entrega</h6>
                        <p><strong>Dirección:</strong> ${venta.direccion_pedido.direccion_ped}</p>
                        <p><strong>Teléfono:</strong> ${venta.direccion_pedido.telf_ped}</p>
                        <p><strong>Ubigeo:</strong> ${venta.direccion_pedido.ubigeo ? venta.direccion_pedido.ubigeo.DESCRIPCION : 'N/A'}</p>
                        <p><strong>Comentario:</strong> ${venta.direccion_pedido.comentario_ped || 'Sin comentarios'}</p>
                    `;
                }
                
                if (venta.compras_usuario && venta.compras_usuario.length > 0) {
                    detalles += `
                        <hr>
                        <h6>Productos</h6>
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Precio Unit.</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                    `;
                    
                    venta.compras_usuario.forEach(function(compra) {
                        detalles += `
                            <tr>
                                <td>${compra.producto ? compra.producto.nombre_producto : 'N/A'}</td>
                                <td>${compra.cant_producto}</td>
                                <td>S/ ${parseFloat(compra.precio_compra).toFixed(2)}</td>
                                <td>S/ ${parseFloat(compra.subtotal_compra).toFixed(2)}</td>
                            </tr>
                        `;
                    });
                    
                    // Calcular total de productos
                    let totalProductos = venta.compras_usuario.reduce((total, compra) => total + parseFloat(compra.subtotal_compra || 0), 0);
                    
                    detalles += `
                            </tbody>
                        </table>
                        <p class="mt-2"><strong>Total de Productos:</strong> S/ ${totalProductos.toFixed(2)}</p>
                    `;
                }
                
                $('#detalles-content').html(detalles);
                $('#detallesModal').modal('show');
            } else {
                alert('Error al cargar los detalles: ' + response.message);
            }
        });
    });
    
    // Editar venta
    $(document).on('click', '.editar-venta', function() {
        let id = $(this).data('id');
        window.location.href = `{{ route("admin#libros.edit", ['id' => ':id']) }}`.replace(':id', id);
    });
    
    // Cancelar venta
    $(document).on('click', '.cancelar-venta', function() {
        let id = $(this).data('id');
        
        $('#confirm-message').text('¿Está seguro de que desea cancelar esta venta?');
        $('#confirm-action').off('click').on('click', function() {
            // Mostrar loading
            $('#confirm-action').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Cancelando...');
            
            $.ajax({
                url: `{{ route("admin#libros.cancelar", ['id' => ':id']) }}`.replace(':id', id),
                method: 'PUT',
                data: {_token: '{{ csrf_token() }}'},
                success: function(response) {
                    if (response.success) {
                        // Mostrar mensaje de éxito
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: 'Venta cancelada exitosamente',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        cargarVentas(currentPage);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message
                        });
                    }
                    $('#confirmModal').modal('hide');
                },
                error: function(xhr) {
                    let errorMsg = 'Error al cancelar la venta';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: errorMsg
                    });
                    $('#confirmModal').modal('hide');
                },
                complete: function() {
                    // Restaurar botón
                    $('#confirm-action').prop('disabled', false).html('Confirmar');
                }
            });
        });
        
        $('#confirmModal').modal('show');
    });
    
    // Aplicar filtros
    $('#aplicar-filtros').click(function() {
        console.log('🔍 Botón "Aplicar Filtros" clickeado');
        
        currentFilters = {
            estado_pago: $('#filtro-estado').val(),
            fecha_desde: $('#filtro-fecha-desde').val(),
            fecha_hasta: $('#filtro-fecha-hasta').val(),
            email_cliente: $('#filtro-email').val()
        };
        
        console.log('📋 Filtros capturados:', currentFilters);
        console.log('🔄 Llamando a cargarVentas(1)');
        
        cargarVentas(1);
    });

    // Exportar a Excel
    $('#exportar-excel').click(function() {
        let filtros = {
            estado_pago: $('#filtro-estado').val(),
            email_cliente: $('#filtro-email').val(),
            fecha_desde: $('#filtro-fecha-desde').val(),
            fecha_hasta: $('#filtro-fecha-hasta').val()
        };
        
        // Mostrar información de filtros aplicados
        let filtrosAplicados = [];
        if (filtros.estado_pago) filtrosAplicados.push('Estado: ' + filtros.estado_pago);
        if (filtros.email_cliente) filtrosAplicados.push('Email: ' + filtros.email_cliente);
        if (filtros.fecha_desde) filtrosAplicados.push('Desde: ' + filtros.fecha_desde);
        if (filtros.fecha_hasta) filtrosAplicados.push('Hasta: ' + filtros.fecha_hasta);
        
        let mensaje = filtrosAplicados.length > 0 ? 
            'Exportando con filtros: ' + filtrosAplicados.join(', ') : 
            'Exportando todas las ventas';
        
        // Mostrar loading
        Swal.fire({
            title: 'Exportando a Excel...',
            text: mensaje,
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        // Crear formulario temporal para enviar los filtros
        let form = $('<form>', {
            'method': 'POST',
            'action': '{{ route("admin#libros.exportar-excel") }}'
        });
        
        form.append($('<input>', {
            'type': 'hidden',
            'name': '_token',
            'value': '{{ csrf_token() }}'
        }));
        
        // Agregar filtros al formulario
        Object.keys(filtros).forEach(function(key) {
            if (filtros[key]) {
                form.append($('<input>', {
                    'type': 'hidden',
                    'name': key,
                    'value': filtros[key]
                }));
            }
        });
        
        $('body').append(form);
        form.submit();
        form.remove();
        
        // Cerrar loading después de un tiempo
        setTimeout(() => {
            Swal.close();
        }, 2000);
    });

    // Exportar a PDF
    $('#exportar-pdf').click(function() {
        let filtros = {
            estado_pago: $('#filtro-estado').val(),
            email_cliente: $('#filtro-email').val(),
            fecha_desde: $('#filtro-fecha-desde').val(),
            fecha_hasta: $('#filtro-fecha-hasta').val()
        };
        
        // Mostrar información de filtros aplicados
        let filtrosAplicados = [];
        if (filtros.estado_pago) filtrosAplicados.push('Estado: ' + filtros.estado_pago);
        if (filtros.email_cliente) filtrosAplicados.push('Email: ' + filtros.email_cliente);
        if (filtros.fecha_desde) filtrosAplicados.push('Desde: ' + filtros.fecha_desde);
        if (filtros.fecha_hasta) filtrosAplicados.push('Hasta: ' + filtros.fecha_hasta);
        
        let mensaje = filtrosAplicados.length > 0 ? 
            'Exportando con filtros: ' + filtrosAplicados.join(', ') : 
            'Exportando todas las ventas';
        
        // Mostrar loading
        Swal.fire({
            title: 'Exportando a PDF...',
            text: mensaje,
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        // Crear formulario temporal para enviar los filtros
        let form = $('<form>', {
            'method': 'POST',
            'action': '{{ route("admin#libros.exportar-pdf") }}'
        });
        
        form.append($('<input>', {
            'type': 'hidden',
            'name': '_token',
            'value': '{{ csrf_token() }}'
        }));
        
        // Agregar filtros al formulario
        Object.keys(filtros).forEach(function(key) {
            if (filtros[key]) {
                form.append($('<input>', {
                    'type': 'hidden',
                    'name': key,
                    'value': filtros[key]
                }));
            }
        });
        
        $('body').append(form);
        form.submit();
        form.remove();
        
        // Cerrar loading después de un tiempo
        setTimeout(() => {
            Swal.close();
        }, 2000);
    });
    
    // Limpiar filtros
    $('#limpiar-filtros').click(function() {
        console.log('🧹 Botón "Limpiar Filtros" clickeado');
        
        $('#filtro-estado').val('');
        $('#filtro-fecha-desde').val('');
        $('#filtro-fecha-hasta').val('');
        $('#filtro-email').val('');
        currentFilters = {};
        
        console.log('🔄 Llamando a cargarVentas(1) después de limpiar');
        cargarVentas(1);
    });
    
    // Paginación
    $(document).on('click', '.page-link', function(e) {
        e.preventDefault();
        let page = $(this).data('page');
        cargarVentas(page);
    });
    
    // Cargar datos iniciales
    cargarVentas();
});
</script>
@endsection
