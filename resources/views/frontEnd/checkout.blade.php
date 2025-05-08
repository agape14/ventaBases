@extends('frontEnd.layouts.app')
@section('content')
@php
    $companyInfo = App\Models\CompanySetting::orderBy('id','desc')->first();
@endphp
<section class="py-4 min-vh-100">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb d-flex align-items-center ">
                        <li class="breadcrumb-item"><a href="{{ URL::previous() }}" class="btn btn-dark btn-sm"><i class="fa fa-chevron-left"></i> Regresar</a></li>
                      <li class="breadcrumb-item"><a href="{{ route('frontend#index') }}">Inicio</a></li>
                      <li class="breadcrumb-item"><a href="{{ route('frontend#viewCarts') }}">Mi carrito</a></li>
                      <li class="breadcrumb-item active" aria-current="page">
                        Procesar el pago
                      </li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="border-0 card">
                    <div class="bg-transparent card-header ">
                        <h5 class="my-2">Informacion para Procesar el pago</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('user#createOrder') }}" method="POST">
                           @csrf
                            <div class="row">
                                <div class="col-md-8">
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
                                        <div class="col-12">
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
                                                                <input name="persona_natural[email]" type="email" class="form-control" value="{{ old('persona_natural.email', $user->email ?? '') }}" placeholder="Ingrese su Correo Electrónico" maxlength="250" >
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
                                                                <input name="persona_natural[name]" type="text" class="form-control" value="{{ old('persona_natural.name', $user->name ?? '') }}" placeholder="Ingrese sus Nombres Completos" maxlength="250">
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
                                                                <input name="representante_legal[name]" type="text" class="form-control" value="{{ old('representante_legal.name', $user->name ?? '')  }}" placeholder="Ingrese sus Nombres Completos" maxlength="250">
                                                                @error('representante_legal.name')
                                                                    <small class="text-danger">{{ $message }}</small>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="mb-3">
                                                                <label for="" class="form-label">Correo Electrónico</label>
                                                                <input name="representante_legal[email]" type="email" class="form-control" value="{{ old('representante_legal.email', $user->email ?? '')   }}" placeholder="Ingrese su Correo Electrónico" maxlength="250" >
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

                                <div class="col-4">
                                    <div class="border-0 card">
                                        <div class="card-body">
                                            {{--@if (Session::has('coupon'))
                                                <div class="p-3 mb-3 border-0 rounded applyCouponBox card bg-light">
                                                    <div class="mb-3 d-flex justify-content-between">
                                                        <p class="mb-0">Your Coupon :</p>
                                                        <h6 class="mb-0">{{ Session::get('coupon')['couponCode'] }}</h6>
                                                    </div>
                                                    <div class="d-flex justify-content-between ">
                                                        <p class="mb-0">Coupon Discount(%) :</p>
                                                        <h6 class="mb-0">-{{ Session::get('coupon')['couponDiscount'] }}%</h6>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="mb-3 border-0 rounded applyCouponBox card">
                                                    <div class="">
                                                        <h5>Discount Code</h5>
                                                        <p class="text-black-50">Enter your coupon code if you have one.....</p>
                                                    </div>
                                                    <div class="">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <input type="text" class="couponCode form-control" placeholder="enter your coupon...">
                                                            <button type="button" onclick="applyCoupon()" class="btn btn-outline-primary text-nowrap ms-2">Apply</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif--}}
                                            @php
                                                $subTotal = Session::has('subTotal') ? Session::get('subTotal') : '0';
                                                $couponDiscount = Session::has('coupon') ? Session::get('coupon')['couponDiscount'] : '0';
                                                $discountAmount = round($subTotal * $couponDiscount/100);
                                                $GrandTotal = $subTotal - $discountAmount;
                                            @endphp
                                            <div class="py-3 border-0 card bg-light">
                                                <div class="card-body">
                                                    <div class="mb-3 d-flex justify-content-between">
                                                        <h6 class="mb-0">Sub Total :</h6>
                                                        <h5 class="mb-0">S/ {{number_format($subTotal,2)}}</h5>
                                                    </div>
                                                    {{--<div class="d-flex justify-content-between">
                                                        <h6 class="mb-0">Coupon Discount :</h6>
                                                        <h5 class="mb-0 couponDiscount">-{{ $discountAmount }}</h5>
                                                    </div>--}}
                                                    <hr>
                                                    <div class="mb-3 d-flex justify-content-between">
                                                        <h6 class="mb-0">Total :</h6>
                                                        <h5 class="mb-0">S/ {{ number_format($GrandTotal,2) }}</h5>
                                                    </div>
                                                    <hr>
                                                    <div class="mb-3 card d-none">
                                                        <div class="bg-transparent card-header">
                                                            <h5>Seleccionar metodo de Pago</h5>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="form-check" style="">
                                                                <div class="p-1">
                                                                    <input class="form-check-input" name="paymentMethod" value="tarjeta" type="radio" id="flexRadioDefault3" checked>
                                                                    <label class="form-check-label" for="flexRadioDefault3">
                                                                        <img src="https://e7.pngegg.com/pngimages/510/354/png-clipart-food-indian-cuisine-bangladeshi-cuisine-devops-dubai-cash-on-delivery-text-logo.png" alt="" srcset="" class="rounded" style="width: 60px">
                                                                        <span class="ms-2">Tarjeta</span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @error('paymentMethod')
                                                            <small class="text-danger">{{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                    <button type="submit"  class="mt-3 text-white shadow btn btn-primary float-end btn-lg">Realizar el pago</button>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="my-4 row">
            <div class="col-12">
                <div class="border-0 card">
                    <div class="bg-white card-header">
                        <h5 class="my-2">Tus Items</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="text-nowrap ">
                                    <tr class="text-center">
                                        <th>#</th>
                                        <th style="">Producto</th>
                                        <th style="width:20%">Nombre</th>
                                        {{--<th style="width:10%">Color</th>
                                        <th style="width:10%">Size</th>--}}
                                        <th style="width:10%">Precio Un.</th>
                                        <th style="width:8%">Cantidad</th>
                                        <th style="width:15%" class="text-center">Total</th>
                                        <th style="">Eliminar</th>
                                    </tr>
                                </thead>
                                @if (Session::has('cart'))
                                    <tbody>
                                        @php
                                            $i = 1;
                                            $total = 0;
                                        @endphp
                                        @foreach (Session::get('cart') as $key => $item)
                                            @php
                                                $total += $item['price'] * $item['quantity']
                                            @endphp
                                            <tr class="text-center">
                                                <td class="align-middle">{{ $i++ }}</td>
                                                <td class="align-middle">
                                                    <img src="{{ asset('uploads/products/'.$item['productImage']) }}" alt="" srcset="" style="width: 100px; heigth: 100px">
                                                </td>
                                                <td class="align-middle text-start">{{ $item['productName'] }}</td>
                                                {{--<td class="align-middle">{{ empty($item['color']) ? '.....' : $item['color'] }}</td>
                                                <td class="align-middle">{{ empty($item['size']) ? '.....' : $item['size'] }}</td>--}}
                                                <td class="align-middle">{{ number_format($item['price'],2) }}</td>
                                                <td class="align-middle">
                                                    <div class="px-3 py-1 rounded bg-light d-inline-block">{{ $item['quantity'] }}</div>
                                                </td>
                                                <td class="align-middle">{{ number_format(($item['price'] * $item['quantity']),2) }}</td>
                                                <td class="align-middle">
                                                    <a href="{{ route('frontend#deleteCart',$key) }}" class="text-white btn btn-danger btn-sm"><i class="fas fa-trash"></i></a>
                                                </td>
                                            </tr>

                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="text-end">
                                            <td colspan="9">
                                                <h5>Sub Total : S/ {{number_format($total,2)}}</h5>
                                            </td>
                                        </tr>
                                    </tfoot>
                                @else
                                    <tbody>
                                        <tr class="text-center text-danger">
                                            <td colspan="9" class="py-3 ">
                                                No hay items en el carrito.
                                            </td>
                                        </tr>
                                    </tbody>
                                @endif
                            </table>
                            <div class="my-2 float-end">
                                <a href="{{ route('frontend#index') }}" class=" btn btn-dark"><i class="fa fa-chevron-left"></i> Continuar Comprando</a>
                                {{-- <a href="{{ route('user#checkout') }}" class="text-white ms-3 btn btn-primary">Proceed To Checkout</a> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</section>
@endsection
@section('script')
<script src="https://static-content.vnforapps.com/v2/js/checkout.js"></script>
<script>
    $('document').ready(function(){
        $('.stateDivisionsOption').on('change',function(){
            let stateDivisionId = $(this).val();
            // alert(stateDivisionId);
            getProvincias(stateDivisionId,1);
        })
        $('.cityOption').on('change',function(){
            let cityId = $(this).val();
            getDistritos(cityId);
        })

        function getProvincias(stateDivisionId,selected){
            //url: "{{ route('user#getCity') }}",
            $.ajax({
                //url: "{{ secure_url(route('user#getCity', [], false)) }}",
                //url: "{{ url(route('user#getCity', [], false)) }}",
                url: "{{ App::environment('local') ? url(route('user#getCity', [], false)) : secure_url(route('user#getCity', [], false)) }}",
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
            //url: "{{ route('user#getTownship') }}",
            $.ajax({
                //url: "{{ secure_url(route('user#getTownship', [], false)) }}",
                //url: "{{ url(route('user#getTownship', [], false)) }}",
                url: "{{ App::environment('local') ? url(route('user#getTownship', [], false)) : secure_url(route('user#getTownship', [], false)) }}",
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
// -----------for coupon-------------
    function applyCoupon(){
        let couponCode = $('.couponCode').val();
        //url: "{{ route('user#applyCoupon') }}",
        if(couponCode){
            $.ajax({
                //url: "{{ secure_url(route('user#applyCoupon', [], false)) }}",
                url: "{{ App::environment('local') ? url(route('user#applyCoupon', [], false)) : secure_url(route('user#applyCoupon', [], false)) }}",
                method: "post",
                dataType: "json",
                data: {
                    _token: '{{ csrf_token() }}',
                    couponCode: couponCode,
                },
                success:function(response){
                    if(response.error){
                        Swal.fire({
                            icon: 'error',
                            title: response.error,
                        });
                    }else{
                        // $('.couponDiscount').text(response.coupon.coupon_discount+'%');
                        window.location.reload();


                        Swal.fire({
                            icon: 'success',
                            title: 'Congrautions, coupon discount '+response.coupon.coupon_discount+'% added',
                        });
                    }
                }

            })
        }
    }

// -----------end for coupon-------------


</script>
@endsection
