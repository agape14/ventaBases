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
                          <li class="breadcrumb-item"><a href="#">Perfil</a></li>
                          <li class="breadcrumb-item active" aria-current="page">Mis Pedidos</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="row">
                <div class="col-3">
                    @include('frontEnd.profile/profileSidebar')

                </div>
                <div class="col-9">
                    <div class="card bg-white border-0 rounded">
                        <div class="card-header bg-transparent">
                            <div class="d-flex justify-content-between my-1 align-items-center">
                                <h5 class="mb-0">Mis Pedidos</h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover" id="dataTable">
                                    <thead class="bg-info text-white text-nowrap">
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Nro. Pedido</th>
                                            <th>Total</th>
                                            <th>Tipo Pago</th>
                                            <th>Estado</th>
                                            <th>Accion</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($orders as $item)
                                        <tr>
                                            <td scope="row">{{ $item->order_date }}</td>
                                            <td>{{ $item->invoice_number }}</td>
                                            <td>S/ {{ number_format($item->grand_total, 2) }}</td>
                                            <td>{{ $item->payment_method }}</td>
                                            <td>
                                                @if ($item->status == "pendiente")
                                                    <div class="badge bg-danger">{{ $item->status }}</div>
                                                @else
                                                    <div class="badge bg-success">{{ $item->status }}</div>
                                                @endif

                                            </td>
                                            <td>
                                                @if($item->status == "pendiente")
                                                <a href="{{ route('user#misPagos',$item->order_id) }}" class="btn btn-sm btn-success text-white "><i class="far fa-money-bill-alt me-2"></i>Pagar</a>
                                                @endif
                                                <a href="{{ route('user#orderDetail',$item->order_id) }}" class="btn btn-sm btn-info text-white "><i class="fas fa-eye me-2"></i>Ver</a>
                                                <a target="_blank" href="{{ route('user#download#downloadInvoice',$item->order_id) }}" class="btn btn-sm btn-dark text-white"><i class="fas fa-download me-2"></i>Recibo</a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
