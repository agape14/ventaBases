<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test JavaScript - Sistema de Libros</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h1>Test JavaScript - Sistema de Libros</h1>
        
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

    <script>
    $(document).ready(function() {
        console.log('📄 Documento listo - jQuery disponible:', typeof $ !== 'undefined');
        console.log('🎯 Botón aplicar filtros encontrado:', $('#aplicar-filtros').length > 0);
        
        let currentPage = 1;
        let currentFilters = {};
        
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
            console.log('🌐 URL de la petición:', '/admin/libros/api/pedidos');
            
            $.get('/admin/libros/api/pedidos', params, function(response) {
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
                        <td>${venta.IdMetododepago}</td>
                        <td>${new Date(venta.fecha_pedido).toLocaleDateString()}</td>
                        <td>
                            <button class="btn btn-sm btn-info">Ver</button>
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
</body>
</html>
