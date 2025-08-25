@extends('admin.layouts.app')

@section('title', 'Nueva Venta de Libros')

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
                    <h3 class="card-title">Nueva Venta de Libros</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin#libros.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Volver al Listado
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form id="form-venta">
                        @csrf
                        <input type="hidden" name="IdCarrito" value="0">
                        <input type="hidden" name="IdRepartidor" value="0">
                        
                        <!-- Información del Cliente -->
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Información del Cliente</h5>
                                <div class="form-group">
                                    <label for="nombre_cliente">Nombre *</label>
                                    <input type="text" class="form-control" id="nombre_cliente" name="nombre_cliente" value="clientes" required>
                                </div>
                                <div class="form-group">
                                    <label for="apellidos_cliente">Apellidos *</label>
                                    <input type="text" class="form-control" id="apellidos_cliente" name="apellidos_cliente" value="otros" required>
                                </div>
                                <div class="form-group">
                                    <label for="email_cliente">Email *</label>
                                    <input type="email" class="form-control" id="email_cliente" name="email_cliente" value="soporte@emilima.com.pe" required>
                                </div>
                                <div class="form-group">
                                    <label for="nro_documento">Número de Documento *</label>
                                    <input type="text" class="form-control" id="nro_documento" name="nro_documento" value="00000009" required>
                                </div>
                                <div class="form-group">
                                    <label for="IdTipoDocumento">Tipo de Documento *</label>
                                    <select class="form-control" id="IdTipoDocumento" name="IdTipoDocumento" required>
                                        <option value="">Seleccionar...</option>
                                        <option value="1" selected>DNI</option>
                                        <option value="2">CE</option>
                                        <option value="3">RUC</option>
                                        <option value="4">Pasaporte</option>
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
                                            <option value="{{ $metodo['value'] }}" {{ $metodo['value'] == 1 ? 'selected' : '' }}>{{ $metodo['text'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="estadopago_ped">Estado de Pago *</label>
                                    <select class="form-control" id="estadopago_ped" name="estadopago_ped" required>
                                        <option value="">Seleccionar...</option>
                                        @foreach(\App\Helpers\EstadosPagoHelper::getEstadosPagoForSelect() as $estado)
                                            <option value="{{ $estado['value'] }}" {{ $estado['value'] == 'pago aceptado' ? 'selected' : '' }}>{{ $estado['text'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="comprobante_tipo">Tipo de Comprobante *</label>
                                    <select class="form-control" id="comprobante_tipo" name="comprobante_tipo" required>
                                        <option value="">Seleccionar...</option>
                                        <option value="boleta" selected>Boleta</option>
                                        <option value="factura">Factura</option>
                                        <option value="ticket">Ticket</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="total_ped">Total *</label>
                                    <input type="number" class="form-control" id="total_ped" name="total_ped" step="0.01" min="0" readonly>
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
                                            <input type="text" class="form-control" id="direccion_ped" name="direccion[direccion_ped]" value="sin direccion" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="telf_ped">Teléfono *</label>
                                            <input type="text" class="form-control" id="telf_ped" name="direccion[telf_ped]" value="999999999" required>
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
                                    <textarea class="form-control" id="comentario_ped" name="direccion[comentario_ped]" rows="2"></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Productos -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <h5>Productos</h5>
                                <div id="productos-container">
                                    <div class="row producto-item">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Producto *</label>
                                                <select class="form-control producto-select" name="compras[0][fk_IdProducto_compra]" required>
                                                    <option value="">Cargando...</option>
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
                                                <input type="number" class="form-control precio-input" name="compras[0][precio_compra]" step="0.01" min="0" required>
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
                                </div>
                                <button type="button" class="btn btn-success" id="agregar-producto">
                                    <i class="fas fa-plus"></i> Agregar Producto
                                </button>
                            </div>
                        </div>
                        
                        <div class="row mt-4">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Guardar Venta
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
    let productoIndex = 0;
    let productosData = []; // Almacenar datos de productos para acceso rápido
    
    // Cargar ubigeos
    cargarUbigeos();
    
    // Cargar productos
    cargarProductos();
    
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
        guardarVenta();
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
        
        // Establecer valor por defecto para LIMA (150101)
        setTimeout(function() {
            // Crear la opción por defecto
            let option = new Option('150101 - LIMA', '150101', true, true);
            $('#fk_IdUbigeoDireccion').append(option).trigger('change');
        }, 100);
    }
    
    function cargarProductos() {
        $.get('{{ route("admin#libros.productos") }}', function(response) {
            if (response.success) {
                productosData = response.data; // Guardar datos para acceso rápido
                let options = '<option value="">Seleccionar...</option>';
                response.data.forEach(function(producto) {
                    options += `<option value="${producto.IdProducto}" data-precio="${producto.precio_producto}">${producto.nombre_producto}</option>`;
                });
                $('.producto-select').html(options);
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
        let html = `
            <div class="row producto-item">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Producto *</label>
                        <select class="form-control producto-select" name="compras[${index}][fk_IdProducto_compra]" required>
                            <option value="">Seleccionar...</option>
                            ${$('.producto-select').first().html()}
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
                        <input type="number" class="form-control precio-input" name="compras[${index}][precio_compra]" step="0.01" min="0" required>
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
    
    function guardarVenta() {
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
            url: '{{ route("admin#libros.store") }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    alert('Venta registrada exitosamente');
                    window.location.href = '{{ route("admin#libros.index") }}';
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function(xhr) {
                let error = 'Error al registrar la venta';
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
