@extends('admin.layouts.app')
@section('content')
<div class="row pt-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-white d-flex align-items-center    ">
              <li class="breadcrumb-item"><a href="{{ URL::previous() }}" class="btn btn-dark btn-sm"><i class="fa fa-chevron-left"></i>  Back</a></li>
              <li class="breadcrumb-item"><a href="{{ route('admin#dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item active" aria-current="page">Payment Transition</li>
            </ol>
          </nav>
    </div>
</div>
    <div class="row">
        <div class="col-12">
            <div class="card rounded" style="box-shadow: none !important">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between my-1">
                        <h4 class="mb-0">All Payment Transition</h4>
                   </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="dataTable">
                            <thead class="bg-primary">
                              <tr>
                                <th>#</th>
                                <th>Order Invoice Number</th>
                                <th>User</th>
                                <th>Transfer To</th>
                                <th>Amount</th>
                                <th>Payment Method</th>
                                <th>Created At</th>
                                <th>Action</th>
                              </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $item)
                                    <tr>
                                        <th>{{ $item->id }}</th>
                                        <td>{{ $item->order->invoice_number }}</td>
                                        <td>{{ $item->order->user->name }}</td>
                                        <td>{{ $item->paymentInfo->name }}-{{ $item->paymentInfo->account_number }}</td>
                                        <td>{{ $item->order->grand_total}} Ks</td>
                                        <td class="font-weight-bold text-uppercase">{{ $item->order->payment_method  }}</td>
                                        <td>{{ $item->created_at->diffForHumans() }}</td>
                                        <td>
                                            <a href="{{ route('admin#showPaymentTransition',$item->id) }}" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>
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
