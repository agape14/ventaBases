@extends('frontEnd.layouts.app')
@section('content')
    <section class="py-4 min-vh-100">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb d-flex align-items-center ">
                            <li class="breadcrumb-item"><a href="{{ URL::previous() }}" class="btn btn-dark btn-sm"><i class="fa fa-chevron-left"></i> Regresar</a></li>
                          <li class="breadcrumb-item"><a href="{{ route('frontend#index') }}">Inicio</a></li>
                          <li class="breadcrumb-item"><a href="#">Profile</a></li>
                          <li class="breadcrumb-item"><a href="{{ route('user#myOrder') }}">Mis Pedidos</a></li>
                          <li class="breadcrumb-item active" aria-current="page">Detalles</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="row">
                <div class="col-3">
                    @include('frontEnd.profile/profileSidebar')

                </div>
                <div class="col-9">
                    <div class="row">
                        <div class="col-12">
                            <div class="my-3 border-0 card">
                                <div class="bg-transparent card-header">
                                    <div class="d-flex justify-content-between">
                                        <div class="h5">Detalle de Pedidos</div>
                                        {{--<a href="{{ route('user#download#downloadInvoice',$order->order_id) }}" class="text-white btn btn-sm btn-dark"><i class="me-2 fas fa-download"></i> Download Invoice</a>--}}
                                    </div>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <tbody class="">
                                            <tr>
                                                <th>Numero de Pedido</th>
                                                <td>{{ $order->invoice_number }}</td>
                                            </tr>
                                            <tr>
                                                <th>Metodo Pago</th>
                                                <td>{{ $order->payment_method }}</td>
                                            </tr>
                                            <tr>
                                                <th>Sub Total</th>
                                                <td>S/ {{ number_format($order->sub_total,2)  }}</td>
                                            </tr>
                                            @if (!empty($order->coupon_id))
                                            <tr>
                                                <th>Cupon de Descuento</th>
                                                <td>{{ $order->coupon_discount }}</td>
                                            </tr>
                                            @endif
                                            <tr>
                                                <th> Total</th>
                                                <td>S/ {{ number_format($order->grand_total,2) }}</td>
                                            </tr>
                                            <tr>
                                                <th>Fecha Pedido</th>
                                                <td>{{ $order->order_date }}</td>
                                            </tr>
                                            <tr>
                                                <th>Estado</th>
                                                <td>
                                                    @if ($order->status == "pendiente")
                                                        <div class="badge bg-danger">{{ $order->status }}</div>
                                                    @else
                                                        <div class="badge bg-success">{{ $order->status }}</div>
                                                    @endif
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="bg-white border-0 card">
                                <div class="bg-transparent card-header">
                                    <div class="my-1 d-flex justify-content-between align-items-center">
                                        <h4 class="mb-0">Articulos</h4>
                                        {{-- <button class="btn btn-dark">Download Invoice</button> --}}
                                    </div>
                                </div>
                                <div class="card-body ">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead class="text-nowrap">
                                                <tr>
                                                    {{--<th>id</th>--}}
                                                    <th>Imagen</th>
                                                    <th>producto</th>
                                                    <th>Precio Unitario</th>
                                                    <th>Cantidad</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($orderItems as $item)
                                                <tr>
                                                    {{--<td scope="row">{{ $item->order_item_id }}</td>--}}
                                                    <td>
                                                        <img src="{{ asset('uploads/products/'.$item->product->preview_image) }}" class="shadow-sm" alt="" srcset="" style="width: 100px; height: 100px">
                                                    </td>
                                                    <td>{{ $item->product->name }}</td>
                                                    <td>{{ number_format($item->unit_price,2) }}</td>
                                                    <td>{{ $item->quantity }}</td>
                                                    <td>{{ number_format($item->total_price,2) }}</td>

                                                </tr>
                                                @endforeach
                                            </tbody>
                                           </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="my-3 border-0 card">
                                <div class="bg-transparent card-header">
                                    <div class="">
                                        <div class="h5">Detalle de envío</div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <tbody class="">
                                            <tr>
                                                <th>Nombre</th>
                                                <td>{{ $order->name }}</td>
                                            </tr>
                                            <tr>
                                                <th>Correo</th>
                                                <td>{{ $order->email }}</td>
                                            </tr>
                                            <tr>
                                                <th>Celular</th>
                                                <td>{{ $order->phone }}</td>
                                            </tr>
                                            <tr>
                                                <th>Departamento</th>
                                                <td>{{ $order->stateDivision->name }}</td>
                                            </tr>
                                            <tr>
                                                <th>Provincia</th>
                                                <td>{{ $order->city->name }}</td>
                                            </tr>
                                            <tr>
                                                <th>Distrito</th>
                                                <td>{{ $order->township->name }}</td>
                                            </tr>
                                            <tr>
                                                <th>Dirección</th>
                                                <td>{{ $order->address }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
