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
                          <li class="breadcrumb-item"><a href="{{ route('user#myOrder') }}">My Order</a></li>
                          <li class="breadcrumb-item active" aria-current="page">Details</li>
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
                                        <div class="h5">Order Detail</div>
                                        <a href="{{ route('user#download#downloadInvoice',$order->order_id) }}" class="text-white btn btn-sm btn-dark"><i class="me-2 fas fa-download"></i> Download Invoice</a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <tbody class="">
                                            <tr>
                                                <th>Invoice Number</th>
                                                <td>{{ $order->invoice_number }}</td>
                                            </tr>
                                            <tr>
                                                <th>Payment</th>
                                                <td>{{ $order->payment_method }}</td>
                                            </tr>
                                            <tr>
                                                <th>Sub Total</th>
                                                <td>{{ $order->sub_total }}</td>
                                            </tr>
                                            @if (!empty($order->coupon_id))
                                            <tr>
                                                <th>Coupon Discount</th>
                                                <td>{{ $order->coupon_discount }} Ks</td>
                                            </tr>
                                            @endif
                                            <tr>
                                                <th>Grand Total</th>
                                                <td>{{ $order->grand_total }} Ks</td>
                                            </tr>
                                            <tr>
                                                <th>Order Date</th>
                                                <td>{{ $order->order_date }}</td>
                                            </tr>
                                            <tr>
                                                <th>Status</th>
                                                <td>
                                                    <div class="badge bg-success">{{ $order->status }}</div>
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
                                        <h4 class="mb-0">Order Items</h4>
                                        {{-- <button class="btn btn-dark">Download Invoice</button> --}}
                                    </div>
                                </div>
                                <div class="card-body ">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead class="text-nowrap">
                                                <tr>
                                                    <th>id</th>
                                                    <th>Image</th>
                                                    <th>product</th>
                                                    <th>color</th>
                                                    <th>size</th>
                                                    <th>unit Price</th>
                                                    <th>quantity</th>
                                                    <th>Total Price</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($orderItems as $item)
                                                <tr>
                                                    <td scope="row">{{ $item->order_item_id }}</td>
                                                    <td>
                                                        <img src="{{ asset('uploads/products/'.$item->product->preview_image) }}" class="shadow-sm" alt="" srcset="" style="width: 100px; height: 100px">
                                                    </td>
                                                    <td>{{ $item->product->name }}</td>
                                                    <td>{{ empty($item->color) ? '---' : $item->color->name}}</td>
                                                    <td>{{ empty($item->size) ? '---' : $item->size->name}}</td>
                                                    <td>{{ $item->unit_price }} Ks</td>
                                                    <td>{{ $item->quantity }}</td>
                                                    <td>{{ $item->total_price }} Ks</td>

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
                                        <div class="h5">Shipping Detail</div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <tbody class="">
                                            <tr>
                                                <th>Name</th>
                                                <td>{{ $order->name }}</td>
                                            </tr>
                                            <tr>
                                                <th>Email</th>
                                                <td>{{ $order->email }}</td>
                                            </tr>
                                            <tr>
                                                <th>Phone</th>
                                                <td>{{ $order->phone }}</td>
                                            </tr>
                                            <tr>
                                                <th>Region</th>
                                                <td>{{ $order->stateDivision->name }}</td>
                                            </tr>
                                            <tr>
                                                <th>City</th>
                                                <td>{{ $order->city->name }}</td>
                                            </tr>
                                            <tr>
                                                <th>Township</th>
                                                <td>{{ $order->township->name }}</td>
                                            </tr>
                                            <tr>
                                                <th>Address</th>
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
