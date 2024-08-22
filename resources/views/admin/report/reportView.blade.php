@extends('admin.layouts.app')
@section('content')
<div class="pt-4 row">
    <div class="col-12">
        <nav aria-label="breadcrumb" class="">
            <ol class="breadcrumb bg-white d-flex align-items-center">
                <li class="breadcrumb-item"><a href="{{ URL::previous() }}" class="btn btn-dark btn-sm"><i class="fa fa-chevron-left"></i>  Back</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin#dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item active" aria-current="page">Report Orders</li>
            </ol>
          </nav>
    </div>
</div>
<div class="pb-4 row">
    <div class="col-12">
        <div class="rounded card" style="box-shadow: none !important">
            <div class="card-header">
               <div class="my-1 d-flex align-items-center ">
                @if (request()->date)
                    <div class="mr-2 shadow btn btn-primary font-weight-bolder">{{ request()->date }}</div>
                @elseif (request()->month)
                    <div class="mr-2 shadow btn btn-primary font-weight-bolder">{{ request()->month }} / {{ request()->year }}</div>
                @else
                    <div class="mr-2 shadow btn btn-primary font-weight-bolder"> {{ request()->year }}</div>
                @endif
                    <h4 class="mb-0">Orders</h4>
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
