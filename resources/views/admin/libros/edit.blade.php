@extends('admin.layouts.app')

@section('title', 'Editar Venta de Libros')

@section('style')
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container--default .select2-selection--single {
        height: 38px;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 36px;
        padding-left: 12px;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Editar Venta de Libros</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin#libros.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Volver al Listado
                        </a>
                    </div>
                </div>
                
                <!-- Información del Pedido -->
                @if(isset($pedido))
                <div class="card-body bg-light">
                    <div class="row">
                        <div class="col-md-3">
                            <strong>ID Pedido:</strong> {{ $pedido->IdPedido }}
                        </div>
                        <div class="col-md-3">
                            <strong>Fecha:</strong> {{ $pedido->fecha_pedido ? \Carbon\Carbon::parse($pedido->fecha_pedido)->format('d/m/Y H:i') : 'N/A' }}
                        </div>
                        <div class="col-md-3">
                            <strong>Estado:</strong> 
                            <span class="badge badge-{{ $pedido->estadopago_ped == 'pagado' ? 'success' : ($pedido->estadopago_ped == 'pendiente' ? 'warning' : 'danger') }}">
                                {{ ucfirst($pedido->estadopago_ped ?? 'N/A') }}
                            </span>
                        </div>
                        <div class="col-md-3">
                            <strong>Total:</strong> S/ {{ number_format($pedido->total_ped ?? 0, 2) }}
                        </div>
                    </div>
                </div>
                @endif
                <div class="card-body">
                    <form id="form-venta">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="IdCarrito" value="0">
                        <input type="hidden" name="IdRepartidor" value="0">
                        
                        <!-- Información del Cliente -->
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Información del Cliente</h5>
                                <div class="form-group">
                                    <label for="nombre_cliente">Nombre *</label>
                                    <input type="text" class="form-control" id="nombre_cliente" name="nombre_cliente" value="{{ $pedido->nombre_cliente ?? '' }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="apellidos_cliente">Apellidos *</label>
                                    <input type="text" class="form-control" id="apellidos_cliente" name="apellidos_cliente" value="{{ $pedido->apellidos_cliente ?? '' }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="email_cliente">Email *</label>
                                    <input type="email" class="form-control" id="email_cliente" name="email_cliente" value="{{ $pedido->email_cliente ?? '' }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="nro_documento">Número de Documento *</label>
                                    <input type="text" class="form-control" id="nro_documento" name="nro_documento" value="{{ $pedido->nro_documento ?? '' }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="IdTipoDocumento">Tipo de Documento *</label>
                                    <select class="form-control" id="IdTipoDocumento" name="IdTipoDocumento" required>
                                        <option value="">Seleccionar...</option>
                                        <option value="1" {{ ($pedido->IdTipoDocumento ?? '') == 1 ? 'selected' : '' }}>DNI</option>
                                        <option value="2" {{ ($pedido->IdTipoDocumento ?? '') == 2 ? 'selected' : '' }}>CE</option>
                                        <option value="3" {{ ($pedido->IdTipoDocumento ?? '') == 3 ? 'selected' : '' }}>RUC</option>
                                        <option value="4" {{ ($pedido->IdTipoDocumento ?? '') == 4 ? 'selected' : '' }}>Pasaporte</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <h5>Información del Pedido</h5>
                                <div class="form-group">
                                    <label for="IdMetododepago">Método de Pago *</label>
                                    <select class="form-control" id="IdMetododepago" name="IdMetododepago" required>
                                        <option value="">Seleccionar...</option>
                                        @foreach(\App\Helpers\MetodosPagoHelper::getMetodosPagoForSelect() as $metodo)
                                            <option value="{{ $metodo['value'] }}" {{ ($pedido->IdMetododepago ?? '') == $metodo['value'] ? 'selected' : '' }}>{{ $metodo['text'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="estadopago_ped">Estado de Pago *</label>
                                    <select class="form-control" id="estadopago_ped" name="estadopago_ped" required>
                                        <option value="">Seleccionar...</option>
                                        <option value="pendiente" {{ ($pedido->estadopago_ped ?? '') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                        <option value="pagado" {{ ($pedido->estadopago_ped ?? '') == 'pagado' ? 'selected' : '' }}>Pagado</option>
                                        <option value="cancelado" {{ ($pedido->estadopago_ped ?? '') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="comprobante_tipo">Tipo de Comprobante *</label>
                                    <select class="form-control" id="comprobante_tipo" name="comprobante_tipo" required>
                                        <option value="">Seleccionar...</option>
                                        <option value="boleta" {{ ($pedido->comprobante_tipo ?? '') == 'boleta' ? 'selected' : '' }}>Boleta</option>
                                        <option value="factura" {{ ($pedido->comprobante_tipo ?? '') == 'factura' ? 'selected' : '' }}>Factura</option>
                                        <option value="ticket" {{ ($pedido->comprobante_tipo ?? '') == 'ticket' ? 'selected' : '' }}>Ticket</option>
                                    </select>
                                </div>
                                                                 <div class="form-group">
                                     <label for="total_ped">Total *</label>
                                     <input type="number" class="form-control" id="total_ped" name="total_ped" step="0.01" min="0" value="{{ $pedido->total_ped ?? 0 }}" readonly>
                                     <small class="form-text text-muted">El total se calcula automáticamente</small>
                                 </div>
                            </div>
                        </div>
                        
                        <!-- Dirección de Entrega -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <h5>Dirección de Entrega</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                                                                 <div class="form-group">
                                             <label for="direccion_ped">Dirección *</label>
                                             <input type="text" class="form-control" id="direccion_ped" name="direccion[direccion_ped]" value="{{ $pedido->direccion_pedido->direccion_ped ?? '' }}" required>
                                         </div>
                                    </div>
                                    <div class="col-md-3">
                                                                                 <div class="form-group">
                                             <label for="telf_ped">Teléfono *</label>
                                             <input type="text" class="form-control" id="telf_ped" name="direccion[telf_ped]" value="{{ $pedido->direccion_pedido->telf_ped ?? '' }}" required>
                                         </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="fk_IdUbigeoDireccion">Ubigeo *</label>
                                            <select class="form-control select2-ubigeo" id="fk_IdUbigeoDireccion" name="direccion[fk_IdUbigeoDireccion]" required>
                                                <option value="">Buscar distrito de LIMA...</option>
                                            </select>
                                            <small class="form-text text-muted">Escriba para buscar distritos de LIMA</small>
                                        </div>
                                    </div>
                                </div>
                                                                 <div class="form-group">
                                     <label for="comentario_ped">Comentario</label>
                                     <textarea class="form-control" id="comentario_ped" name="direccion[comentario_ped]" rows="2">{{ $pedido->direccion_pedido->comentario_ped ?? '' }}</textarea>
                                 </div>
                            </div>
                        </div>
                        
                        <!-- Productos -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <h5>Productos</h5>
                                                                                                  <div id="productos-container">
                                     <!-- Los productos se cargarán dinámicamente via JavaScript -->
                                     <div class="text-center">
                                         <i class="fas fa-spinner fa-spin"></i> Cargando productos...
                                     </div>
                                 </div>
                                <button type="button" class="btn btn-success" id="agregar-producto">
                                    <i class="fas fa-plus"></i> Agregar Producto
                                </button>
                            </div>
                        </div>
                        
                        <div class="row mt-4">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Actualizar Venta
                                </button>
                                <a href="{{ route('admin#libros.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Cancelar
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    let productoIndex = {{ isset($pedido->compras_usuario) ? count($pedido->compras_usuario) : 1 }};
    let productosData = []; // Almacenar datos de productos para acceso rápido
    let pedidoData = @json($pedido ?? null);
    
    // Cargar ubigeos
    cargarUbigeos();
    
    // Cargar productos
    cargarProductos();
    
    // Debug: Mostrar información del pedido en consola
    console.log('Datos del pedido:', pedidoData);
    if (pedidoData && pedidoData.compras_usuario) {
        console.log('Productos del pedido:', pedidoData.compras_usuario);
        console.log('Cantidad de productos:', pedidoData.compras_usuario.length);
    }
    if (pedidoData && pedidoData.direccion_pedido) {
        console.log('Dirección del pedido:', pedidoData.direccion_pedido);
        console.log('Dirección específica:', pedidoData.direccion_pedido.direccion_ped);
        console.log('Teléfono específico:', pedidoData.direccion_pedido.telf_ped);
        console.log('Comentario específico:', pedidoData.direccion_pedido.comentario_ped);
    }
    console.log('Total del pedido:', pedidoData ? pedidoData.total_ped : 'N/A');
    
    // Verificar valores de los campos de dirección después de que se cargue la página
    setTimeout(function() {
        console.log('Valor del campo dirección:', $('#direccion_ped').val());
        console.log('Valor del campo teléfono:', $('#telf_ped').val());
        console.log('Valor del campo comentario:', $('#comentario_ped').val());
        
        // Si los campos están vacíos pero tenemos datos, llenarlos
        if (pedidoData && pedidoData.direccion_pedido) {
            if (!$('#direccion_ped').val() && pedidoData.direccion_pedido.direccion_ped) {
                $('#direccion_ped').val(pedidoData.direccion_pedido.direccion_ped);
                console.log('Dirección llenada desde JavaScript');
            }
            if (!$('#telf_ped').val() && pedidoData.direccion_pedido.telf_ped) {
                $('#telf_ped').val(pedidoData.direccion_pedido.telf_ped);
                console.log('Teléfono llenado desde JavaScript');
            }
            if (!$('#comentario_ped').val() && pedidoData.direccion_pedido.comentario_ped) {
                $('#comentario_ped').val(pedidoData.direccion_pedido.comentario_ped);
                console.log('Comentario llenado desde JavaScript');
            }
        }
    }, 1000);
    
    // Calcular subtotales cuando cambien cantidad o precio
    $(document).on('input', '.cantidad-input, .precio-input', function() {
        calcularSubtotal($(this));
        calcularTotal();
    });
    
    // Cargar precio automáticamente al seleccionar producto
    $(document).on('change', '.producto-select', function() {
        cargarPrecioProducto($(this));
    });
    
    // Agregar producto
    $('#agregar-producto').click(function() {
        productoIndex++;
        agregarProducto(productoIndex);
    });
    
    // Eliminar producto
    $(document).on('click', '.eliminar-producto', function() {
        if ($('.producto-item').length > 1) {
            $(this).closest('.producto-item').remove();
            recalcularIndices();
            calcularTotal();
        }
    });
    
    // Enviar formulario
    $('#form-venta').submit(function(e) {
        e.preventDefault();
        actualizarVenta();
    });
    
    function cargarUbigeos() {
        // Inicializar Select2 para ubigeo
        $('#fk_IdUbigeoDireccion').select2({
            placeholder: 'Buscar distrito de LIMA...',
            allowClear: true,
            ajax: {
                url: '{{ route("admin#libros.ubigeos") }}',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        busqueda: params.term || '',
                        page: params.page || 1
                    };
                },
                processResults: function(data, params) {
                    params.page = params.page || 1;
                    
                    return {
                        results: data.data.map(function(ubigeo) {
                            return {
                                id: ubigeo.IDUBIGEO,
                                text: ubigeo.IDUBIGEO + ' - ' + ubigeo.DESCRIPCION
                            };
                        }),
                        pagination: {
                            more: false
                        }
                    };
                },
                cache: true
            },
            minimumInputLength: 1,
            language: {
                inputTooShort: function() {
                    return "Por favor ingrese al menos 1 carácter para buscar";
                },
                noResults: function() {
                    return "No se encontraron distritos";
                },
                searching: function() {
                    return "Buscando...";
                }
            }
        });
        
        // Si hay un ubigeo seleccionado, establecerlo
        if (pedidoData && pedidoData.direccion_pedido && pedidoData.direccion_pedido.fk_IdUbigeoDireccion) {
            // Crear la opción para el ubigeo actual
            let ubigeoActual = pedidoData.direccion_pedido.fk_IdUbigeoDireccion;
            let descripcionActual = pedidoData.direccion_pedido.ubigeo ? pedidoData.direccion_pedido.ubigeo.DESCRIPCION : 'Distrito de LIMA';
            
            let option = new Option(ubigeoActual + ' - ' + descripcionActual, ubigeoActual, true, true);
            $('#fk_IdUbigeoDireccion').append(option).trigger('change');
        }
    }
    
    function cargarProductos() {
        $.get('{{ route("admin#libros.productos") }}', function(response) {
            if (response.success) {
                productosData = response.data; // Guardar datos para acceso rápido
                let options = '<option value="">Seleccionar...</option>';
                response.data.forEach(function(producto) {
                    options += `<option value="${producto.IdProducto}" data-precio="${producto.precio_producto}">${producto.nombre_producto}</option>`;
                });
                
                // Generar HTML para todos los productos existentes si no están en el DOM
                if (pedidoData && pedidoData.compras_usuario && pedidoData.compras_usuario.length > 0) {
                    console.log('Generando HTML para productos existentes:', pedidoData.compras_usuario.length);
                    
                    // Limpiar el contenedor de productos
                    $('#productos-container').empty();
                    
                    // Generar HTML para cada producto existente
                    pedidoData.compras_usuario.forEach(function(compra, index) {
                        let html = `
                            <div class="row producto-item">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Producto *</label>
                                        <select class="form-control producto-select" name="compras[${index}][fk_IdProducto_compra]" required>
                                            ${options}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Cantidad *</label>
                                        <input type="number" class="form-control cantidad-input" name="compras[${index}][cant_producto]" min="1" value="${compra.cant_producto || 1}" required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Precio Unit. *</label>
                                        <input type="number" class="form-control precio-input" name="compras[${index}][precio_compra]" step="0.01" min="0" value="${compra.precio_compra || 0}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Subtotal</label>
                                        <input type="number" class="form-control subtotal-input" name="compras[${index}][subtotal_compra]" step="0.01" value="${compra.subtotal_compra || 0}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <button type="button" class="btn btn-danger btn-block eliminar-producto">
                                            <i class="fas fa-trash"></i> Eliminar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        `;
                        $('#productos-container').append(html);
                    });
                    
                    // Seleccionar productos existentes y calcular subtotales
                    pedidoData.compras_usuario.forEach(function(compra, index) {
                        console.log('Procesando compra:', compra, 'índice:', index);
                        let selectElement = $(`.producto-select[name="compras[${index}][fk_IdProducto_compra]"]`);
                        console.log('Elemento select encontrado:', selectElement.length);
                        
                        if (selectElement.length > 0) {
                            selectElement.val(compra.fk_IdProducto_compra);
                            
                            // Calcular subtotal para este producto
                            let row = selectElement.closest('.producto-item');
                            let cantidad = parseFloat(row.find('.cantidad-input').val()) || 0;
                            let precio = parseFloat(row.find('.precio-input').val()) || 0;
                            let subtotal = cantidad * precio;
                            row.find('.subtotal-input').val(subtotal.toFixed(2));
                            console.log('Subtotal calculado:', subtotal);
                        }
                    });
                } else {
                    // Si no hay productos existentes, crear uno vacío
                    console.log('No hay productos existentes, creando uno vacío');
                    let html = `
                        <div class="row producto-item">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Producto *</label>
                                    <select class="form-control producto-select" name="compras[0][fk_IdProducto_compra]" required>
                                        ${options}
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Cantidad *</label>
                                    <input type="number" class="form-control cantidad-input" name="compras[0][cant_producto]" min="1" value="1" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Precio Unit. *</label>
                                    <input type="number" class="form-control precio-input" name="compras[0][precio_compra]" step="0.01" min="0" readonly>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Subtotal</label>
                                    <input type="number" class="form-control subtotal-input" name="compras[0][subtotal_compra]" step="0.01" readonly>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="button" class="btn btn-danger btn-block eliminar-producto">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                    $('#productos-container').html(html);
                }
                
                // Calcular total inicial
                calcularTotal();
            }
        });
    }
    
    function cargarPrecioProducto(selectElement) {
        let selectedValue = selectElement.val();
        let precioInput = selectElement.closest('.producto-item').find('.precio-input');
        
        if (selectedValue) {
            // Buscar el producto en los datos almacenados
            let producto = productosData.find(p => p.IdProducto == selectedValue);
            if (producto) {
                precioInput.val(producto.precio_producto);
                calcularSubtotal(selectElement);
                calcularTotal();
            }
        } else {
            precioInput.val('');
            calcularSubtotal(selectElement);
            calcularTotal();
        }
    }
    
    function agregarProducto(index) {
        // Obtener las opciones del primer select (que ya tiene todos los productos)
        let options = $('.producto-select').first().html();
        
        let html = `
            <div class="row producto-item">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Producto *</label>
                        <select class="form-control producto-select" name="compras[${index}][fk_IdProducto_compra]" required>
                            ${options}
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Cantidad *</label>
                        <input type="number" class="form-control cantidad-input" name="compras[${index}][cant_producto]" min="1" value="1" required>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Precio Unit. *</label>
                        <input type="number" class="form-control precio-input" name="compras[${index}][precio_compra]" step="0.01" min="0" readonly>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Subtotal</label>
                        <input type="number" class="form-control subtotal-input" name="compras[${index}][subtotal_compra]" step="0.01" readonly>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <button type="button" class="btn btn-danger btn-block eliminar-producto">
                            <i class="fas fa-trash"></i> Eliminar
                        </button>
                    </div>
                </div>
            </div>
        `;
        $('#productos-container').append(html);
    }
    
    function calcularSubtotal(element) {
        let row = element.closest('.producto-item');
        let cantidad = parseFloat(row.find('.cantidad-input').val()) || 0;
        let precio = parseFloat(row.find('.precio-input').val()) || 0;
        let subtotal = cantidad * precio;
        row.find('.subtotal-input').val(subtotal.toFixed(2));
    }
    
    function calcularTotal() {
        let total = 0;
        $('.subtotal-input').each(function() {
            total += parseFloat($(this).val()) || 0;
        });
        $('#total_ped').val(total.toFixed(2));
    }
    
    function recalcularIndices() {
        $('.producto-item').each(function(index) {
            $(this).find('select, input').each(function() {
                let name = $(this).attr('name');
                if (name) {
                    name = name.replace(/compras\[\d+\]/, `compras[${index}]`);
                    $(this).attr('name', name);
                }
            });
        });
    }
    
    function actualizarVenta() {
        // Validar que haya al menos un producto
        if ($('.producto-select').filter(function() { return $(this).val(); }).length === 0) {
            alert('Debe seleccionar al menos un producto');
            return;
        }
        
        // Validar que el total sea mayor a 0
        let total = parseFloat($('#total_ped').val()) || 0;
        if (total <= 0) {
            alert('El total debe ser mayor a 0');
            return;
        }
        
        let formData = new FormData($('#form-venta')[0]);
        
        $.ajax({
            url: '{{ route("admin#libros.update", $pedido->IdPedido ?? 0) }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    alert('Venta actualizada exitosamente');
                    window.location.href = '{{ route("admin#libros.index") }}';
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function(xhr) {
                let error = 'Error al actualizar la venta';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    error = xhr.responseJSON.message;
                }
                alert(error);
            }
        });
    }
});
</script>
@endsection
