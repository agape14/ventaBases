@extends('frontEnd.layouts.app')

@section('content')
<section class="py-4 min-vh-100">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card bg-white border-0 rounded">
                    <div class="card-header bg-transparent">
                        <div class="d-flex justify-content-between my-1 align-items-center">
                            <h5 class="mb-0">Confirmar y Pagar</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <h5 class="mb-2 text-info">Mi compra</h5>
                        @if (session('error'))
                            <div id="paymentErrorAlert" class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="bg-info text-white text-nowrap">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Descripcion Item</th>
                                        <th scope="col">Cantidad</th>
                                        <th scope="col">Precio</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orderItems as $index => $item)
                                    <tr>
                                        <td scope="row">{{ $index + 1 }}</td>
                                        <td>{{ $item->product->name }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>S/ {{ number_format($item->unit_price, 2) }}</td>
                                    </tr>
                                    @endforeach
                                    <tr class="bg-light font-weight-bold">
                                        <td colspan="3" class="text-right">Total:</td>
                                        <td>S/ {{ number_format($order->grand_total, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <form id="frmVisaNet" action="{{ route('purchase.complete', ['id' => $order->order_id]) }}">
                            @csrf
                            <script src="{{ config('niubiz.js_url') }}"
                                data-sessiontoken="{{ $sessionKey }}"
                                data-channel="web"
                                data-merchantid="{{ config('niubiz.merchant_id') }}"
                                data-merchantlogo="{{ asset('uploads/logo/'.$companyInfo->logo) }}"
                                data-purchasenumber="{{ $order->invoice_number }}"
                                data-amount="{{ $order->grand_total }}"
                                data-expirationminutes="5"
                                data-timeouturl="{{ route('frontend#index') }}">
                            </script>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Encuentra el alert por su id
        var paymentErrorAlert = document.getElementById('paymentErrorAlert');

        // Solo si el alert existe
        if (paymentErrorAlert) {
            // Espera 5 segundos (5000 ms) antes de ocultar el alert
            setTimeout(function() {
                // Utiliza las clases de Bootstrap para ocultarlo con una animación de desvanecimiento
                paymentErrorAlert.classList.add('fade');
                paymentErrorAlert.classList.add('show');
                paymentErrorAlert.style.transition = 'opacity 50s ease';

                // Después de la animación, elimina el alert completamente del DOM
                setTimeout(function() {
                    paymentErrorAlert.remove();
                }, 500); // Coincide con la duración de la animación de desvanecimiento
            }, 5000); // Cambia el tiempo de espera aquí si lo deseas
        }
    });
</script>

@endsection
