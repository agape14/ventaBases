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
                                <div class="col-4">
                                    <div class="border-0 card">
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label for="" class="form-label">Nombres Completos</label>
                                                <input name="name" type="text" class="form-control" value="{{ old('name') }}" placeholder="Ingrese sus Nombres Completos" required>
                                                @error('name')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label for="" class="form-label">Correo Electrónico</label>
                                                <input name="email" type="email" class="form-control" value="{{ old('email') }}" placeholder="Ingrese su Correo Electrónico" required >
                                                @error('email')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label for="" class="form-label">Celular</label>
                                                <input name="phone" type="number" class="form-control" value="{{ old('phone') }}" placeholder="Ingrese su Celular" required>
                                                @error('phone')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label for="" class="form-label">Mensaje</label>
                                                <textarea name="note" class="form-control" id="" rows="3" placeholder="Ingrese su mensaje">{{ old('note') }}</textarea>
                                                @error('note')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="border-0 card">
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label for="" class="form-label">Departamento</label>
                                                <select name="stateDivisionId" id="" class="stateDivisionsOption form-control" required>
                                                    <option value="">----Seleccione Departamento----</option>
                                                    @foreach ($stateDivisions as $item)
                                                        <option value="{{ $item->state_division_id }}">{{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('stateDivisionId')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label for="" class="form-label">Provincia</label>
                                                <select name="cityId" id="" class="cityOption form-control" disabled required>
                                                    <option value="">----Seleccione Provincia----</option>
                                                </select>
                                                @error('cityId')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label for="" class="form-label">Distrito</label>
                                                <select name="townshipId" id="" class="townshipOption form-control" disabled required>
                                                    <option value="">----Seleccione Distrito----</option>
                                                </select>
                                                @error('townshipId')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="mb-4">
                                                <label for="" class="form-label">Dirección completa</label>
                                                <input name="address" type="text" class="form-control" value="{{ old('address') }}" placeholder="Ingrese su Direccion completa" required>
                                                @error('address')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
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
                                                                    <input class="form-check-input" name="paymentMethod" value="cos" type="radio" id="flexRadioDefault3" checked>
                                                                    <label class="form-check-label" for="flexRadioDefault3">
                                                                        <img src="https://e7.pngegg.com/pngimages/510/354/png-clipart-food-indian-cuisine-bangladeshi-cuisine-devops-dubai-cash-on-delivery-text-logo.png" alt="" srcset="" class="rounded" style="width: 60px">
                                                                        <span class="ms-2">Contra reembolso</span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @error('paymentMethod')
                                                            <small class="text-danger">{{ $message }}</small>
                                                        @enderror
                                                    </div>

                                                    {{--<button type="submit"  class="mt-3 text-white shadow btn btn-primary float-end btn-lg">Place Order</button>
                                                    <button id="niubiz-button">Pagar con Niubiz</button>
                                                    <form id="niubiz-form" action="{{ route('purchase.complete') }}" method="POST" style="display: none;">
                                                        @csrf
                                                        <input type="hidden" name="transactionToken" id="transactionToken">
                                                        <input type="hidden" name="orderNumber" value="12525">
                                                    </form>--}}

                                                    <!-- Botón de pago (visible por defecto)
                                                    <button id="btnVisaNet" onclick="showVisaForm()">Pagar con Niubiz</button>-->

                                                    <!-- Formulario para el script de pago (oculto por defecto) -->
                                                    {{--<form id="frmVisaNet" style="display:none" action="{{ route('purchase.complete') }}">
                                                        <script src="{{ config('niubiz.js_url') }}"
                                                            data-sessiontoken="{{ $sessionKey }}"
                                                            data-channel="web"
                                                            data-merchantid="{{ config('niubiz.merchant_id') }}"
                                                            data-merchantlogo="{{ asset('uploads/logo/'.$companyInfo->logo) }}"
                                                            data-purchasenumber="{{ $purchaseNumber }}"
                                                            data-amount="{{ $amount }}"
                                                            data-expirationminutes="5"
                                                            data-timeouturl="{{ route('frontend#index') }}">
                                                        </script>
                                                    </form>--}}
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
                    $('.cityOpiton').html('<option>-----Loading-----</option>');
                },
                success:function(response){
                    let cityHtml = '<option  value="">-----Select City-----</option>';
                    for(let i= 0; i < response.cities.length; i++){
                        cityHtml += `<option value="${response.cities[i].city_id}">${response.cities[i].name}</option>`;
                    };
                    $('.cityOption').html(cityHtml);
                    $('.townshipOption').html("<option>-----Select Township-----</option>");

                }
            })
        })
        $('.cityOption').on('change',function(){
            let cityId = $(this).val();
            $.ajax({
                url: "{{ route('user#getTownship') }}",
                method: "post",
                dataType: "json",
                data: {
                    _token: '{{ csrf_token() }}',
                    id: cityId,
                },
                beforeSend:function(){
                    $('.townshipOption').prop("disabled", false);
                    $('.townshipOpiton').html('<option>-----Loading-----</option>');
                },
                success:function(response){
                    let townshipHtml = '<option value="">-----Select Township-----</option>';
                    for(let i= 0; i < response.townships.length; i++){
                        townshipHtml += `<option value="${response.townships[i].township_id}">${response.townships[i].name}</option>`;
                    };
                    $('.townshipOption').html(townshipHtml);
                }
            })
        })

        showVisaForm = function() {
            var frmVisa = document.getElementById('frmVisaNet');
            if (frmVisa) {
                frmVisa.style.display = 'block';
            }
        }
    })
// -----------for coupon-------------
    function applyCoupon(){
        let couponCode = $('.couponCode').val();
        if(couponCode){
            $.ajax({
                url: "{{ route('user#applyCoupon') }}",
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
