@extends('admin.layouts.app')
@section('content')
<div class="pt-4 row">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="bg-white breadcrumb d-flex align-items-center">
                <li class="breadcrumb-item"><a href="{{ URL::previous() }}" class="btn btn-dark btn-sm"><i class="fa fa-chevron-left"></i>  Back</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin#dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item active" aria-current="page">Orders</li>
            </ol>
          </nav>
    </div>
</div>
<div class="pb-4 row">
    <div class="col-12">
        <div class="rounded card" style="box-shadow: none !important">
            <div class="card-header">
               <div class="my-1 d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">All Orders - <div class="badge bg-dark">{{ $data->count() }}</div></h4>
                    <form class="d-flex align-items-center" action="{{ route('admin#filterOrder') }}" method="GET">
                        @csrf
                        <p class="mb-0 mr-2 text-nowrap">Order Status :</p>
                        <select name="orderStatus" id="" class="mb-0 mr-2 custom-select">
                            <option value="">All</option>
                            <option value="pending" {{ request()->orderStatus == 'pending' ? 'selected' : ''}}>Pending</option>
                            <option value="confirmed" {{ request()->orderStatus == 'confirmed' ? 'selected' : ''}}>confirmed</option>
                            <option value="processing" {{ request()->orderStatus == 'processing' ? 'selected' : ''}}>processing</option>
                            <option value="picked" {{ request()->orderStatus == 'picked' ? 'selected' : ''}}>picked</option>
                            <option value="shipped" {{ request()->orderStatus == 'shipped' ? 'selected' : ''}}>shipped</option>
                            <option value="delivered" {{ request()->orderStatus == 'delivered' ? 'selected' : ''}}>deliverd</option>
                            <option value="completed" {{ request()->orderStatus == 'completed' ? 'selected' : ''}}>completed</option>
                        </select>
                        <button class="btn btn-primary">Filter</button>
                    </form>
               </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="dataTable">
                        <thead class="bg-primary text-nowrap">
                          <tr>
                            <th>#</th>
                            <th>Order Date</th>
                            <th>Invoice Number</th>
                            <th>User Name</th>
                            <th>Grand Total</th>
                            <th>Payment Method</th>
                            <th>Status</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                            <tr>
                                <td>{{ $item->order_id }}</td>
                                <td>{{ $item->order_date }}</td>
                                <td>{{ $item->invoice_number }}</td>
                                <td>{{ $item->user->name }}</td>
                                <td>{{ $item->grand_total }} Ks</td>
                                <td>{{ $item->payment_method }}</td>
                                <td>
                                    <div class="badge bg-success">{{ $item->status }}</div>
                                </td>
                                <td>
                                    <a href="{{ route('admin#showOrder',$item->order_id) }}" class="text-white btn btn-sm btn-info "><i class="fas fa-eye me-2"></i>View</a>
                                    <a href="{{ route('user#download#downloadInvoice',$item->order_id) }}" class="text-white btn btn-sm btn-dark"><i class="fas fa-download me-2"></i>Invoice</a>
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
