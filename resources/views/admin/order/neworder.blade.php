@extends('admin.layouts.app')
@section('content')
<div class="pt-4 row">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="bg-white breadcrumb d-flex align-items-center">
                <li class="breadcrumb-item"><a href="{{ URL::previous() }}" class="btn btn-dark btn-sm"><i class="fa fa-chevron-left"></i>  Regresar</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin#dashboard') }}">Panel de Control</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin#order') }}" class="">Pedidos</a></li>
              <li class="breadcrumb-item active" aria-current="page">Registro manual</li>
            </ol>
          </nav>
    </div>
</div>
<div class="pb-4 row">
    <div class="col-12">
        <div class="my-3 shadow-none card">
            <div class="bg-transparent card-header">
                <div class="d-flex justify-content-between">
                    <div class="h5">Detalle Pedido</div>
                    {{--<a href="{{ route('user#download#downloadInvoice',$order->order_id) }}" class="text-white btn btn-sm btn-dark"><i class="mr-2 fas fa-download"></i> Download Invoice</a>--}}
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('user#registerOrder') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                     <div class="row">
                        <input type="hidden" name="paymentMethod" value="transferencia" id="paymentMethod">
                         <div class="col-md-12">
                             <ul class="nav nav-tabs mb-3" id="myTab0" role="tablist">
                                 <li class="nav-item" role="presentation">
                                     <a class="nav-link active" id="home-tab0" data-toggle="tab"  href="#persona_nat" role="tab" aria-controls="persona_nat" aria-selected="true">Persona Natural</a>
                                 </li>
                                 <li class="nav-item" role="presentation">
                                     <a class="nav-link" id="profile-tab0" data-toggle="tab" href="#persona_jur" role="tab" aria-controls="persona_jur" aria-selected="false">Persona Juridica</a>
                                 </li>
                             </ul>
                             <div class="tab-content" id="myTabContent0">
                                 <input type="hidden" name="tipo_persona" id="tipo_persona">
                                 <div class="col-12 mb-4">
                                     <div class="alert alert-info  mb-0" role="alert">
                                         <label class="form-label">Tipo Comprobante: </label>
                                         <div class="form-check form-check-inline">
                                             <input class="form-check-input" type="radio" name="tipo_comprobante" id="tipo_comprobanteb" value="B" {{ old('tipo_comprobante', 'B') == 'B' ? 'checked' : '' }}>
                                             <label class="form-check-label" for="tipo_comprobanteb">
                                               Boleta Electrónica
                                             </label>
                                           </div>
                                           <div class="form-check form-check-inline">
                                             <input class="form-check-input" type="radio" name="tipo_comprobante" id="tipo_comprobantef" value="F" {{ old('tipo_comprobante', 'B') == 'F' ? 'checked' : '' }}>
                                             <label class="form-check-label" for="tipo_comprobantef">
                                               Factura Electrónica
                                             </label>
                                           </div>
                                     </div>
                                 </div>
                                 <div class="tab-pane fade show active" id="persona_nat" role="tabpanel" aria-labelledby="home-tab0">

                                     <div class="row">
                                         <div class="col-12 mb-0 {{ old('tipo_comprobante') == 'F' ? '' : 'd-none' }}" id="div_ruc_persona_natural">
                                             <div class="border-0 card">
                                                 <div class="card-body">
                                                     <div class="row">
                                                         <div class="col-1">
                                                             <label for="" class="form-label">RUC</label>
                                                         </div>
                                                         <div class="col-11">
                                                             <div>
                                                                 <input name="persona_natural[ruc]" type="text" class="form-control" value="{{ old('persona_natural.ruc') }}" placeholder="00000000000" maxlength="11" pattern="\d{1,11}" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                                                 @error('persona_natural.ruc')
                                                                     <small class="text-danger">{{ $message }}</small>
                                                                 @enderror
                                                             </div>
                                                         </div>
                                                     </div>
                                                 </div>
                                             </div>
                                         </div>
                                         <div class="col-6 mt-0">
                                             <div class="border-0 card">
                                                 <div class="card-body">
                                                     <div class="mb-3">
                                                         <label for="" class="form-label">DNI</label>
                                                         <input name="persona_natural[dni]" type="text" class="form-control" value="{{ old('persona_natural.dni') }}" placeholder="00000000" maxlength="8" pattern="\d{1,8}" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                                         @error('persona_natural.dni')
                                                             <small class="text-danger">{{ $message }}</small>
                                                         @enderror
                                                     </div>
                                                     <div class="mb-3">
                                                         <label for="" class="form-label">Correo Electrónico</label>
                                                         <input name="persona_natural[email]" type="email" class="form-control" value="{{ old('persona_natural.email') }}" placeholder="Ingrese su Correo Electrónico" maxlength="250" >
                                                         @error('persona_natural.email')
                                                             <small class="text-danger">{{ $message }}</small>
                                                         @enderror
                                                     </div>
                                                     <div class="mb-3">
                                                         <label for="" class="form-label">Celular</label>
                                                         <input name="persona_natural[phone]" type="text" class="form-control" value="{{ old('persona_natural.phone') }}" placeholder="Ingrese su Celular"  placeholder="000000000"  maxlength="9" pattern="\d{1,9}" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                                         @error('persona_natural.phone')
                                                             <small class="text-danger">{{ $message }}</small>
                                                         @enderror
                                                     </div>
                                                     <div class="mb-3 d-none">
                                                         <label for="" class="form-label">Mensaje</label>
                                                         <textarea name="note" class="form-control" id="" rows="3" placeholder="Ingrese su mensaje">{{ old('note') }}</textarea>
                                                         @error('note')
                                                             <small class="text-danger">{{ $message }}</small>
                                                         @enderror
                                                     </div>
                                                 </div>
                                             </div>
                                         </div>
                                         <div class="col-6">
                                             <div class="border-0 card">
                                                 <div class="card-body">
                                                     <div class="mb-3">
                                                         <label for="" class="form-label">Nombres Completos</label>
                                                         <input name="persona_natural[name]" type="text" class="form-control" value="{{ old('persona_natural.name') }}" placeholder="Ingrese sus Nombres Completos" maxlength="250">
                                                         @error('persona_natural.name')
                                                             <small class="text-danger">{{ $message }}</small>
                                                         @enderror
                                                     </div>
                                                     <div class="mb-4">
                                                         <label for="" class="form-label">Dirección completa</label>
                                                         <input name="persona_natural[address]" type="text" class="form-control" value="{{ old('persona_natural.address') }}" placeholder="Ingrese su Direccion completa"  maxlength="250">
                                                         @error('persona_natural.address')
                                                             <small class="text-danger">{{ $message }}</small>
                                                         @enderror
                                                     </div>
                                                     <div class="mb-3 d-none">
                                                         <label for="" class="form-label">Departamento</label>
                                                         <select name="stateDivisionId" id="cbxDepartamentoNat" class="stateDivisionsOption form-control" >
                                                             <option value="">----Seleccione Departamento----</option>
                                                             @foreach ($stateDivisions as $item)
                                                                 <option value="{{ $item->state_division_id }}">{{ $item->name }}</option>
                                                             @endforeach
                                                         </select>
                                                         @error('stateDivisionId')
                                                             <small class="text-danger">{{ $message }}</small>
                                                         @enderror
                                                     </div>
                                                     <div class="mb-3 d-none">
                                                         <label for="" class="form-label">Provincia</label>
                                                         <select name="cityId" id="cbxProvinciaNat" class="cityOption form-control" disabled >
                                                             <option value="">----Seleccione Provincia----</option>
                                                         </select>
                                                         @error('cityId')
                                                             <small class="text-danger">{{ $message }}</small>
                                                         @enderror
                                                     </div>
                                                     <div class="mb-3">
                                                         <label for="" class="form-label">Distrito</label>
                                                         <select name="persona_natural[distrito]" id="cbxDistritoNat" class="townshipOption form-control" disabled >
                                                             <option value="">----Seleccione Distrito----</option>
                                                         </select>
                                                         @error('persona_natural.distrito')
                                                             <small class="text-danger">{{ $message }}</small>
                                                         @enderror
                                                     </div>
                                                 </div>
                                             </div>
                                         </div>
                                     </div>
                                 </div>
                                 <div class="tab-pane fade" id="persona_jur" role="tabpanel" aria-labelledby="profile-tab0">
                                     <div class="row">
                                         <div class="col-6">
                                             <div class="border-0 card">
                                                 <div class="card-body">
                                                     <div class="mb-3">
                                                         <label for="" class="form-label">RUC</label>
                                                         <input name="persona_juridica[ruc]" type="text" class="form-control" value="{{ old('persona_juridica.ruc') }}" placeholder="00000000000" maxlength="11" pattern="\d{1,11}" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                                         @error('persona_juridica.ruc')
                                                             <small class="text-danger">{{ $message }}</small>
                                                         @enderror
                                                     </div>
                                                     <div class="mb-3">
                                                         <label for="" class="form-label">Correo Electrónico</label>
                                                         <input name="persona_juridica[email]" type="email" class="form-control" value="{{ old('persona_juridica.email') }}" placeholder="Ingrese su Correo Electrónico"  maxlength="250">
                                                         @error('persona_juridica.email')
                                                             <small class="text-danger">{{ $message }}</small>
                                                         @enderror
                                                     </div>
                                                     <div class="mb-3">
                                                         <label for="" class="form-label">Celular</label>
                                                         <input name="persona_juridica[phone]" type="text" class="form-control" value="{{ old('persona_juridica.phone') }}" placeholder="Ingrese su Celular"  placeholder="000000000"  maxlength="9" pattern="\d{1,9}" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                                         @error('persona_juridica.phone')
                                                             <small class="text-danger">{{ $message }}</small>
                                                         @enderror
                                                     </div>
                                                 </div>
                                             </div>
                                         </div>
                                         <div class="col-6">
                                             <div class="border-0 card">
                                                 <div class="card-body">
                                                     <div class="mb-3">
                                                         <label for="" class="form-label">Razon Social</label>
                                                         <input name="persona_juridica[razon_social]" type="text" class="form-control" value="{{ old('persona_juridica.razon_social') }}" placeholder="Ingrese sus Nombres Completos" maxlength="250">
                                                         @error('persona_juridica.razon_social')
                                                             <small class="text-danger">{{ $message }}</small>
                                                         @enderror
                                                     </div>
                                                     <div class="mb-4">
                                                         <label for="" class="form-label">Dirección completa</label>
                                                         <input name="persona_juridica[address]" type="text" class="form-control" value="{{ old('persona_juridica.address') }}" placeholder="Ingrese su Direccion completa" maxlength="250">
                                                         @error('persona_juridica.address')
                                                             <small class="text-danger">{{ $message }}</small>
                                                         @enderror
                                                     </div>
                                                     <div class="mb-3">
                                                         <label for="" class="form-label">Distrito</label>
                                                         <select name="persona_juridica[distrito]" id="cbxDistritoJuridica" class="townshipOption form-control" disabled >
                                                             <option value="">----Seleccione Distrito----</option>
                                                         </select>
                                                         @error('persona_juridica.distrito')
                                                             <small class="text-danger">{{ $message }}</small>
                                                         @enderror
                                                     </div>


                                                 </div>
                                             </div>
                                         </div>
                                         <div class="col-12">
                                             <h5>Representante Legal</h5>
                                             <div class="row">
                                                 <div class="col-3">
                                                     <div class="mb-3">
                                                         <label for="" class="form-label">DNI</label>
                                                         <input name="representante_legal[dni]" type="text" class="form-control" value="{{ old('representante_legal.dni') }}" placeholder="00000000" maxlength="8" pattern="\d{1,8}" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                                         @error('representante_legal.dni')
                                                             <small class="text-danger">{{ $message }}</small>
                                                         @enderror
                                                     </div>
                                                 </div>
                                                 <div class="col-9">
                                                     <div class="mb-3">
                                                         <label for="" class="form-label">Nombres Completos</label>
                                                         <input name="representante_legal[name]" type="text" class="form-control" value="{{ old('representante_legal.name') }}" placeholder="Ingrese sus Nombres Completos" maxlength="250">
                                                         @error('representante_legal.name')
                                                             <small class="text-danger">{{ $message }}</small>
                                                         @enderror
                                                     </div>
                                                 </div>
                                                 <div class="col-6">
                                                     <div class="mb-3">
                                                         <label for="" class="form-label">Correo Electrónico</label>
                                                         <input name="representante_legal[email]" type="email" class="form-control" value="{{ old('representante_legal.email') }}" placeholder="Ingrese su Correo Electrónico" maxlength="250" >
                                                         @error('representante_legal.email')
                                                             <small class="text-danger">{{ $message }}</small>
                                                         @enderror
                                                     </div>
                                                 </div>
                                                 <div class="col-6">
                                                     <div class="mb-4">
                                                         <label for="" class="form-label">Dirección completa</label>
                                                         <input name="representante_legal[address]" type="text" class="form-control" value="{{ old('representante_legal.address') }}" placeholder="Ingrese su Direccion completa" maxlength="250" >
                                                         @error('representante_legal.address')
                                                             <small class="text-danger">{{ $message }}</small>
                                                         @enderror
                                                     </div>
                                                 </div>
                                                 <div class="col-6">
                                                     <div class="mb-3">
                                                         <label for="" class="form-label">Celular</label>
                                                         <input name="representante_legal[phone]" type="text" class="form-control" value="{{ old('representante_legal.phone') }}" placeholder="Ingrese su Celular" placeholder="000000000"  maxlength="9" pattern="\d{1,9}" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                                         @error('representante_legal.phone')
                                                             <small class="text-danger">{{ $message }}</small>
                                                         @enderror
                                                     </div>
                                                 </div>
                                                 <div class="col-6">
                                                     <div class="mb-3">
                                                         <label for="" class="form-label">Distrito</label>
                                                         <select name="representante_legal[distrito]" id="cbxDistritoRepresentante" class="townshipOption form-control" disabled>
                                                             <option value="">----Seleccione Distrito----</option>
                                                         </select>
                                                         @error('representante_legal.distrito')
                                                             <small class="text-danger">{{ $message }}</small>
                                                         @enderror
                                                     </div>
                                                 </div>
                                             </div>
                                         </div>
                                     </div>
                                 </div>
                             </div>
                         </div>
                         <div class="col-12">
                                <div class="border-0 card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-2">
                                                <label for="" class="form-label">Nro. operacion</label>
                                                <div>
                                                    <input name="invoice_number" type="text" class="form-control" value="{{ old('invoice_number') }}" placeholder="00000000000" maxlength="50" pattern="\d{1,50}" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                                                    @error('invoice_number')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-2">
                                                <label for="" class="form-label">Fecha</label>
                                                <div>
                                                    <input name="order_date" type="date" class="form-control" value="{{ old('order_date') }}" required>
                                                    @error('order_date')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-2">
                                                <label for="" class="form-label">Monto</label>
                                                <div>
                                                    <input name="grand_total" type="text" class="form-control" value="{{ old('grand_total') }}" required>
                                                    @error('grand_total')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <label for="" class="form-label">Voucher (opcional)</label>
                                                <input name="paymentScreenshot" type="file" class="form-control" >
                                                @error('paymentScreenshot')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="mb-3">
                                                    <label for="" class="form-label">Producto</label>
                                                    <select name="product_id" id="cbxProducto" class=" form-control" required>
                                                        <option value="">----Seleccione Producto----</option>
                                                        @foreach ($productos as $itemprod)
                                                            <option value="{{ $itemprod->product_id }}">{{ $itemprod->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('product_id')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <label for="" class="form-label">Cantidad</label>
                                                <div>
                                                    <input name="quantity" type="number" class="form-control" value="{{ old('quantity') }}" min="1" max="50" required>
                                                    @error('quantity')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                         </div>
                         <div class="col-12">
                            <button type="submit"  class="mt-3 text-white shadow btn btn-primary float-end btn-lg">
                                <i class="fa fa-location-arrow" aria-hidden="true"></i> Registrar el pago
                            </button>
                         </div>
                     </div>
                 </form>
            </div>
        </div>
    </div>


    {{--<div class="col-12">
        <div class="my-3 shadow-none card">
            <div class="bg-transparent card-header">
                <div class="d-flex justify-content-between">
                    <div class="h5">Payment Transition</div>
                    <a href="{{ route('admin#showPaymentTransition',$order->paymentTransition->id) }}" class="btn btn-primary ">View Payment Transition</a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tbody class="">
                        <tr>
                            <th>Transfer To:</th>
                            <td class="font-weight-bold">{{ $order->paymentTransition->paymentInfo->name }}-{{ $order->paymentTransition->paymentInfo->account_number }}</td>
                        </tr>
                        <tr>
                            <th>Payment Method</th>
                            <td class="font-weight-bold text-uppercase">{{ $order->paymentTransition->order->payment_method }}</td>
                        </tr>
                        <tr>
                            <th>Amount</th>
                            <td>{{ $order->paymentTransition->order->grand_total }}</td>
                        </tr>
                        <tr>
                            <th>Payment Photo</th>
                            <td>
                                <div class="">

                                        <a href="{{ asset('uploads/payment/'.$order->paymentTransition->payment_screenshot) }}" data-lightbox="image-1" data-title="Payment Photo">
                                            <img src="{{ asset('uploads/payment/'.$order->paymentTransition->payment_screenshot) }}" alt="" srcset="" style="width: 200px;">
                                        </a>

                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>--}}
</div>
@endsection
@section('script')
    <script>
        $('document').ready(function(){
            /*$('.orderStatusBtn').on('click',function(e){
                e.preventDefault();
                let link = $(this).attr("href");
                Swal.fire({
                    title: '¿Estás seguro de cambiar el estado del pedido?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, cambiar estado!',
                    cancelButtonText: 'Cancelar',
                    }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = link;
                    }
                    })
            })*/

        $('.stateDivisionsOption').on('change',function(){
            let stateDivisionId = $(this).val();
            // alert(stateDivisionId);
            getProvincias(stateDivisionId,0);
        })
        $('.cityOption').on('change',function(){
            let cityId = $(this).val();
            getDistritos(cityId);
        })

        function getProvincias(stateDivisionId,selected){
            $.ajax({
                url: "{{ route('user#getCity') }}",
                method: "post",
                dataType: "json",
                data: {
                    _token: '{{ csrf_token() }}',
                    id: stateDivisionId,
                },
                beforeSend:function(){
                    $('.cityOption').prop("disabled", false);
                    $('.cityOpiton').html('<option>-----Cargando-----</option>');
                },
                success:function(response){
                    let cityHtml = '<option  value="">-----Seleccciona Provincia-----</option>';
                    for(let i= 0; i < response.cities.length; i++){
                        cityHtml += `<option value="${response.cities[i].city_id}">${response.cities[i].name}</option>`;
                    };
                    $('.cityOption').html(cityHtml);
                    $('.townshipOption').html("<option>-----Seleccciona Distrito-----</option>");
                    if(selected==1){
                        $('#cbxProvinciaNat').val('1');
                    }
                }
            })
        }

        function getDistritos(cityId,selected){
            $.ajax({
                url: "{{ route('user#getTownship') }}",
                method: "post",
                dataType: "json",
                data: {
                    _token: '{{ csrf_token() }}',
                    id: cityId,
                },
                beforeSend:function(){
                    $('#cbxDistritoNat').prop("disabled", false);
                    $('#cbxDistritoNat').html('<option>-----Cargando-----</option>');
                    $('#cbxDistritoJuridica').prop("disabled", false);
                    $('#cbxDistritoJuridica').html('<option>-----Cargando-----</option>');
                    $('#cbxDistritoRepresentante').prop("disabled", false);
                    $('#cbxDistritoRepresentante').html('<option>-----Cargando-----</option>');
                },
                success:function(response){
                    let townshipHtml = '<option value="">-----Seleccciona Distrito-----</option>';
                    for(let i= 0; i < response.townships.length; i++){
                        townshipHtml += `<option value="${response.townships[i].township_id}">${response.townships[i].name}</option>`;
                    };
                    $('#cbxDistritoNat').html(townshipHtml);
                    $('#cbxDistritoJuridica').html(townshipHtml);
                    $('#cbxDistritoRepresentante').html(townshipHtml);
                    if(selected==1){
                        $('#cbxDistritoNat').val('2');
                        $('#cbxDistritoJuridica').val('2');
                        $('#cbxDistritoRepresentante').val('2');
                    }
                }
            })
        }
        $('#cbxDepartamentoNat').val('1');
        getProvincias(1,1);
        getDistritos(1,0);
        $('#tipo_persona').val('N');
        quitarrequiere_pers_natural();
        $('#myTab0 a').on('click', function (e) {
            e.preventDefault();
            // Extrae el valor del atributo href
            var target = $(this).attr('href');

            // Usa el valor del atributo href para determinar el tipo
            var tipo = '';
            if (target === '#persona_nat') {
                $('#tipo_persona').val('N');
                quitarrequiere_pers_natural();
                $( '#tipo_comprobanteb' ).prop( 'disabled' , '' );
                $( '#tipo_comprobanteb' ).prop( 'checked' , true );
                $('#div_ruc_persona_natural').addClass('d-none');
                $('input[name="persona_natural[ruc]"]').removeAttr('required');
            } else if (target === '#persona_jur') {
                $('#tipo_persona').val('J');
                quitarrequiere_pers_juridica();
                $( '#tipo_comprobanteb' ).prop( 'checked' , false );
                $( '#tipo_comprobanteb' ). prop ( 'disabled' , 'disabled' );
                $( '#tipo_comprobantef' ). prop ( 'checked' , true );
            }

            console.log('Tipo seleccionado:', $('#tipo_persona').val());

            $(this).tab('show');
        });

        function quitarrequiere_pers_natural(){
            $('input[name="persona_natural[dni]"]').attr('required', true);
            $('input[name="persona_natural[name]"]').attr('required', true);
            $('input[name="persona_natural[email]"]').attr('required', true);
            $('input[name="persona_natural[address]"]').attr('required', true);
            $('input[name="persona_natural[phone]"]').attr('required', true);
            $('input[name="persona_natural[distrito]"]').attr('required', true);

            $('input[name="persona_juridica[ruc]"]').removeAttr('required');
            $('input[name="persona_juridica[razon_social]"]').removeAttr('required');
            $('input[name="persona_juridica[email]"]').removeAttr('required');
            $('input[name="persona_juridica[address]"]').removeAttr('required');
            $('input[name="persona_juridica[phone]"]').removeAttr('required');
            $('input[name="persona_juridica[distrito]"]').removeAttr('required');
            $('input[name="representante_legal[dni]"]').removeAttr('required');
            $('input[name="representante_legal[name]"]').removeAttr('required');
            $('input[name="representante_legal[email]"]').removeAttr('required');
            $('input[name="representante_legal[address]"]').removeAttr('required');
            $('input[name="representante_legal[phone]"]').removeAttr('required');
            $('input[name="representante_legal[distrito]"]').removeAttr('required');
        }
        function quitarrequiere_pers_juridica(){
            $('input[name="persona_juridica[ruc]"]').attr('required', true);
            $('input[name="persona_juridica[razon_social]"]').attr('required', true);
            $('input[name="persona_juridica[email]"]').attr('required', true);
            $('input[name="persona_juridica[address]"]').attr('required', true);
            $('input[name="persona_juridica[phone]"]').attr('required', true);
            $('input[name="persona_juridica[distrito]"]').attr('required', true);

            $('input[name="representante_legal[dni]"]').attr('required', true);
            $('input[name="representante_legal[name]"]').attr('required', true);
            $('input[name="representante_legal[email]"]').attr('required', true);
            $('input[name="representante_legal[address]"]').attr('required', true);
            $('input[name="representante_legal[phone]"]').attr('required', true);
            $('input[name="representante_legal[distrito]"]').attr('required', true);

            $('input[name="persona_natural[ruc]"]').removeAttr('required');
            $('input[name="persona_natural[dni]"]').removeAttr('required');
            $('input[name="persona_natural[name]"]').removeAttr('required');
            $('input[name="persona_natural[email]"]').removeAttr('required');
            $('input[name="persona_natural[address]"]').removeAttr('required');
            $('input[name="persona_natural[phone]"]').removeAttr('required');
            $('input[name="persona_natural[distrito]"]').removeAttr('required');
        }

        $('input[name="tipo_comprobante"]').change(function() {
            // Verifica si la opción seleccionada es Factura (F)
            if ($('#tipo_comprobantef').is(':checked')) {
                // Muestra el campo RUC
                $('#div_ruc_persona_natural').removeClass('d-none');
                $('input[name="persona_natural[ruc]"]').attr('required', true);
            } else {
                // Oculta el campo RUC si no es Factura
                $('#div_ruc_persona_natural').addClass('d-none');
                $('input[name="persona_natural[ruc]"]').removeAttr('required');
            }
        });
        })
    </script>
@endsection
