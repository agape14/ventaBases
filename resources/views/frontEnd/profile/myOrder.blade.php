@extends('frontEnd.layouts.app')
@section('content')
    <section class="py-4 min-vh-100">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb d-flex align-items-center ">
                            <li class="breadcrumb-item"><a href="{{ URL::previous() }}" class="btn btn-dark btn-sm"><i class="fa fa-chevron-left"></i> Back</a></li>
                          <li class="breadcrumb-item"><a href="{{ route('frontend#index') }}">Home</a></li>
                          <li class="breadcrumb-item"><a href="#">Profile</a></li>
                          <li class="breadcrumb-item active" aria-current="page">My orders</li>
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
                                <h5 class="mb-0">My Orders</h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover" id="dataTable">
                                    <thead class="bg-primary text-white text-nowrap">
                                        <tr>
                                            <th>Date</th>
                                            <th>Invoice</th>
                                            <th>Total Amount</th>
                                            <th>Payment</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($orders as $item)
                                        <tr>
                                            <td scope="row">{{ $item->order_date }}</td>
                                            <td>{{ $item->invoice_number }}</td>
                                            <td>{{ $item->grand_total }} Ks</td>
                                            <td>{{ $item->payment_method }}</td>
                                            <td>
                                                <div class="badge bg-success">{{ $item->status }}</div>
                                            </td>
                                            <td>
                                                <a href="{{ route('user#orderDetail',$item->order_id) }}" class="btn btn-sm btn-info text-white "><i class="fas fa-eye me-2"></i>View</a>
                                                <a target="_blank" href="{{ route('user#download#downloadInvoice',$item->order_id) }}" class="btn btn-sm btn-dark text-white"><i class="fas fa-download me-2"></i>Invoice</a>
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
