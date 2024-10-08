@extends('admin.layouts.app')
@section('content')
    @php
        $now = Carbon\Carbon::now()->locale('es');
        $todaySales = App\Models\Order::where('order_date',$now->format('d/m/Y'))->where('status','pagado')->sum('grand_total');
        $monthlySales = App\Models\Order::where('order_month',$now->format('F'))->where('status','pagado')->sum('grand_total');
        $yearlySales = App\Models\Order::where('order_year',$now->format('Y'))->where('status','pagado')->sum('grand_total');
        $pendingOrders = App\Models\Order::where('status','pendiente')->count();

    @endphp
    <div class="pt-4 mb-3 row">
        <div class="col-3">
            <div class="shadow-none card">
                <div class="card-body d-flex align-items-center">
                    <div class="p-3 rounded">
                        <i class="fas fa-calendar-day text-primary" style="font-size: 40px"></i>
                    </div>
                    <div class="ml-3">
                        <h6 class="mb-0 text-secondary">Ventas del día</h6>
                        <h4 class="mb-0 font-weight-bolder">S/ {{ number_format($todaySales,2) }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-3">
            <div class="shadow-none card">
                <div class="card-body d-flex align-items-center">
                    <div class="p-3 rounded ">
                        <i class="fas fa-th text-primary" style="font-size: 40px"></i>
                    </div>
                    <div class="ml-3">
                        <h6 class="mb-0 text-secondary">Ventas del Mes</h6>
                        <h4 class="mb-0 font-weight-bolder">S/ {{ number_format($monthlySales,2) }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-3">
            <div class="shadow-none card">
                <div class="card-body d-flex align-items-center">
                    <div class="p-3 rounded ">
                        <i class="fas fa-calendar-alt text-primary" style="font-size: 40px"></i>
                    </div>
                    <div class="ml-3">
                        <h6 class="mb-0 text-secondary">Ventas del Año</h6>
                        <h4 class="mb-0 font-weight-bolder">S/ {{ number_format($yearlySales,2) }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-3">
            <a href="{{ route('admin#pendingOrder') }}" class="shadow-none card">
                <div class="card-body d-flex align-items-center">
                    <div class="p-3 rounded ">
                        <i class="fas fa-shopping-bag text-primary" style="font-size: 40px"></i>

                    </div>
                    <div class="ml-3">
                        <h6 class="mb-0 text-secondary">Pendiente pago</h6>
                        <h4 class="mb-0 font-weight-bolder">{{ $pendingOrders }} Pedido(s)</h4>
                    </div>
                </div>
            </a>
        </div>
    </div>
    <div class="mb-3 row">
        <div class="col-6">
            <div class="rounded shadow-none card">
                <div class="card-header">
                    <h5 class="mb-0">Pedidos por Mes( {{ date('Y')}} )</h5>
                </div>
                <div class="card-body">
                    <canvas id="myChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="rounded shadow-none card">
                <div class="card-header">
                    <h5 class="mb-0">Ventas por Mes ( {{ date('Y')}} )</h5>
                </div>
                <div class="card-body">
                    <canvas id="orderByMonth"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="pb-4 row">
        <div class="col-6">
            <div class="rounded card" style="box-shadow: none !important">
                <div class="card-header">
                   <div class="my-1 d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">Los mejores productos en venta</h5>
                   </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="bg-primary text-nowrap">
                              <tr>
                                <th>#</th>
                                <th>Imagen</th>
                                <th>Descripcion</th>
                                <th>Total Venta</th>
                              </tr>
                            </thead>
                            <tbody>
                                @foreach ($topProducts as $item)
                                    <tr>
                                        <td>{{ $item->product_id }}</td>
                                        <td>
                                            <img src="{{ asset('uploads/products').'/'.$item->preview_image }}" class="rounded shadow-sm" alt="" srcset="" style="width: 50px; height: 50px;">
                                        </td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->total_sales }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                          </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="rounded card" style="box-shadow: none !important">
                <div class="card-header">
                   <div class="my-1 d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">Principales Clientes</h5>
                   </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="bg-primary text-nowrap">
                              <tr>
                                <th>#</th>
                                <th>Imagen</th>
                                <th>Nombres Completos</th>
                                <th>Correo</th>
                                <th>Total Ventas</th>
                              </tr>
                            </thead>
                            <tbody>
                                @foreach ($topCustomers as $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <th>
                                            @if (!empty($item->profile_photo_path))
                                                <img src="{{ asset('/uploads/user/'.$item->profile_photo_path) }}" class="rounded shadow"  alt="" srcset="" style="width: 40px; height: 40px;">
                                            @else
                                                <img src="{{ asset('frontEnd/resources/image/user-default.png') }}" class="bg-white rounded shadow"  alt="" srcset="" style="width: 40px; height: 40px;">
                                            @endif
                                        </th>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->email }}</td>
                                        <td>{{ $item->total_sales }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                          </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<div class="pb-4 row">
    <div class="col-12">
        <div class="rounded card" style="box-shadow: none !important">
            <div class="card-header">
               <div class="my-1 d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Productos con menos de 5 en Stock</h5>
               </div>
            </div>
            <div class="card-body">
                @php
                    $products = App\Models\ProductVariant::query()->with(['product' => function($query){
                                    $query->select('product_id','name','publish_status');
                                },'color','size'])->where('available_stock','<=',5)->get();
                @endphp
                <div class="table-responsive">
                    <table class="table table-hover" id="dataTable">
                        <thead class="bg-primary text-nowrap">
                          <tr>
                            <th>#</th>
                            <th>Descripcion</th>
                            <th>Stock</th>
                            <th>Estado</th>
                            <th>Accion</th>
                          </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $item)
                                <tr>
                                    <th>{{ $item->product_variant_id }}</th>
                                    <td>{{ $item->product->name }}</td>
                                    <td>{{ $item->available_stock }}</td>
                                    <td>
                                        @if ($item->product->publish_status == 1)
                                            <div class="badge badge-success">Publicado</div>
                                        @else
                                            <div class="badge badge-danger">Sin Publicar</div>
                                        @endif
                                    </td>
                                    <td class="text-wrap">
                                        <a href="{{ route('admin#showProduct',$item->product_id) }}" class="mb-2 btn btn-info btn-sm"><i class="fas fa-eye "></i></a>
                                        <a href="{{ route('admin#editVariant',$item->product_variant_id) }}" class="mb-2 btn btn-success btn-sm"><i class="fas fa-edit "></i></a>
                                        <a href="{{ route('admin#createVariant',$item->product_id) }}" class="mb-2 btn btn-dark btn-sm"><i class="fas fa-plus-circle"></i></a>
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
@section('script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const ctx = document.getElementById('myChart').getContext('2d');
        var data = {!! json_encode($data) !!};
        const myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun' , 'Jul' , 'Aug' , 'Sep' , 'Oct' , 'Nov' , 'Dec'],
                datasets: [{
                    label: 'pedidos por mes {{ date('Y') }}',
                    data: data,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        '#4B445320',
                        'rgba(255, 159, 64, 0.2)',
                        '#66BEA020',
                        '#D65DB120',
                        '#926C0020',
                        '#005B4420',
                        '#6100FF20',
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        '#4B4453',
                        'rgba(255, 159, 64, 1)',
                        '#66BEA0',
                        '#D65DB1',
                        '#926C00',
                        '#005B44',
                        '#6100FF',
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        @php

        @endphp
        var salesByMonth = {!! json_encode($salesByMonth) !!};
        const orderByMonth = document.getElementById('orderByMonth').getContext('2d');
        const orderByMonthChart = new Chart(orderByMonth, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun' , 'Jul' , 'Aug' , 'Sep' , 'Oct' , 'Nov' , 'Dec'],
                datasets: [{
                    label: 'ventas por mes {{ date('Y') }}',
                    data: salesByMonth,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        '#4B445320',
                        'rgba(255, 159, 64, 0.2)',
                        '#66BEA020',
                        '#D65DB120',
                        '#926C0020',
                        '#005B4420',
                        '#6100FF20',
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        '#4B4453',
                        'rgba(255, 159, 64, 1)',
                        '#66BEA0',
                        '#D65DB1',
                        '#926C00',
                        '#005B44',
                        '#6100FF',
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });



    </script>


@endsection
