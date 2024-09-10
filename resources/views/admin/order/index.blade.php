@extends('admin.layouts.app')
@section('content')
<div class="pt-4 row">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="bg-white breadcrumb d-flex align-items-center">
                <li class="breadcrumb-item"><a href="{{ URL::previous() }}" class="btn btn-dark btn-sm"><i class="fa fa-chevron-left"></i>  Regresar</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin#dashboard') }}">Dasboard</a></li>
              <li class="breadcrumb-item active" aria-current="page">Pedidos</li>
            </ol>
          </nav>
    </div>
</div>
<div class="pb-4 row">
    <div class="col-12">
        <div class="rounded card" style="box-shadow: none !important">
            <div class="card-header">
               <div class="my-1 d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">Todos los Pedidos - <div class="badge bg-dark">{{ $ordersWithBrand->count() }}</div></h4>
                    <form class="d-flex align-items-center" action="{{ route('admin#filterOrder') }}" method="GET">
                        @csrf
                        <p class="mb-0 mr-2 text-nowrap">Estado Pedidos :</p>
                        <select name="orderStatus" id="" class="mb-0 mr-2 custom-select">
                            <option value="">Todos</option>
                            <option value="pendiente" {{ request()->orderStatus == 'pendiente' ? 'selected' : ''}}>Pendiente</option>
                            <option value="pagado" {{ request()->orderStatus == 'pagado' ? 'selected' : ''}}>Pagado</option>
                            {{--<option value="procesando" {{ request()->orderStatus == 'procesando' ? 'selected' : ''}}>Procesando</option>
                            <option value="seleccionado" {{ request()->orderStatus == 'seleccionado' ? 'selected' : ''}}>Seleccionado</option>
                            <option value="enviado" {{ request()->orderStatus == 'enviado' ? 'selected' : ''}}>Enviado</option>
                            <option value="entregado" {{ request()->orderStatus == 'entregado' ? 'selected' : ''}}>Entregado</option>
                            <option value="completado" {{ request()->orderStatus == 'completado' ? 'selected' : ''}}>Completado</option>--}}
                        </select>
                        <button class="btn btn-primary">Filtrar</button>
                    </form>
               </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="tblPedidos">
                        <thead class="bg-primary text-nowrap">
                          <tr>
                            <th>#</th>
                            <th>Fecha</th>
                            <th>Numero Pedido</th>
                            <th>Cliente</th>
                            <th> Total</th>
                            <th>Metodo Pago</th>
                            <th>Estado</th>
                            <th>Accion</th>
                          </tr>
                        </thead>
                        <tbody>
                            @foreach ($ordersWithBrand as $item)
                            <tr>
                                <td>{{ $item->order_id }}</td>
                                <td>{{ $item->order_date }}</td>
                                <td>{{ $item->invoice_number }}</td>
                                <td>{{ $item->customer->name ?? ''}}</td>
                                <td>{{ $item->grand_total }}</td>
                                <td>{{ $item->brand }}</td>
                                <td>
                                    <div class="badge bg-success">{{ $item->status }}</div>
                                </td>
                                <td>
                                    <a href="{{ route('admin#showOrder',$item->order_id) }}" class="text-white btn btn-sm btn-info "><i class="fas fa-eye me-2"></i> Detalles</a>
                                    {{--<a href="{{ route('user#download#downloadInvoice',$item->order_id) }}" class="text-white btn btn-sm btn-dark"><i class="fas fa-download me-2"></i>Invoice</a>--}}
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
@endsection
