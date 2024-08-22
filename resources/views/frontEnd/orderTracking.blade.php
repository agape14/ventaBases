@extends('frontEnd.layouts.app')
@section('style')
<style>
     .track {
         position: relative;
         background-color: #ddd;
         height: 7px;
         display: -webkit-box;
         display: -ms-flexbox;
         display: flex;
         margin-bottom: 60px;
         margin-top: 50px
     }

     .track .step {
         -webkit-box-flex: 1;
         -ms-flex-positive: 1;
         flex-grow: 1;
         width: 25%;
         margin-top: -18px;
         text-align: center;
         position: relative
     }

     .track .step.active:before {
         background: #FF5722
     }

     .track .step::before {
         height: 7px;
         position: absolute;
         content: "";
         width: 100%;
         left: 0;
         top: 18px
     }

     .track .step.active .icon {
         background: #ee5435;
         color: #fff
     }

     .track .icon {
         display: inline-block;
         width: 40px;
         height: 40px;
         line-height: 40px;
         position: relative;
         border-radius: 100%;
         background: #ddd
     }

     .track .step.active .text {
         font-weight: 400;
         color: #000
     }

     .track .text {
         display: block;
         margin-top: 7px
     }

</style>

@endsection
@section('content')
    <section class="py-4">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb d-flex align-items-center ">
                            <li class="breadcrumb-item"><a href="{{ URL::previous() }}" class="btn btn-dark btn-sm"><i class="fa fa-chevron-left"></i> Back</a></li>
                          <li class="breadcrumb-item"><a href="{{ route('frontend#index') }}">Home</a></li>
                          <li class="breadcrumb-item active" aria-current="page">Order Tracking</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="mb-5 row">
                <div class="col-12">

                    <div class="bg-white border-0 card rouned">
                        <div class="bg-transparent card-header">
                            <h5 class="my-2"> My Orders / Tracking</h5>
                        </div>
                        <div class="card-body">
                            {{-- <h6 class="mb-3">Invoice Number: <strong>{{ $order->invoice_number }}</strong> </h6> --}}
                            <div class="card">
                                <div class="card-body row">
                                    <div class="col"> <strong>Invoice Number:</strong> <br>{{ $order->invoice_number }} </div>
                                    <div class="col"> <strong>Order Date:</strong> <br>{{ $order->order_date }} </div>
                                    <div class="col"> <strong>Customer:</strong> <br>{{ $order->name }} </div>
                                    <div class="col"> <strong>Address :</strong> <br> {{ $order->address }} </div>
                                    <div class="col"> <strong>Order Items :</strong> <br> {{ $order->order_item_count }} items </div>
                                    <div class="col"> <strong>Grand Total:</strong> <br> {{$order->grand_total}} Ks</div>
                                    <div class="col"> <strong>Status:</strong> <br> {{ $order->status }} </div>
                                </div>
                            </div>
                            <div class="track">
                                <div class="step active"> <span class="icon"> <i class="fas fa-ellipsis-h"></i> </span> <span class="text">Pending</span> </div>
                                <div class="step {{ $order->confirmed_date != null ? 'active' : ''}}"> <span class="icon"> <i class="fas fa-check-circle"></i></span> <span class="text">Confirmed</span> </div>
                                <div class="step {{ $order->processing_date != null ? 'active' : ''}}"> <span class="icon"> <i class="fas fa-spinner"></i> </span> <span class="text">Processing</span> </div>
                                <div class="step {{ $order->picked_date != null ? 'active' : ''}}"> <span class="icon"> <i class="fas fa-box"></i> </span> <span class="text">Picked</span> </div>
                                <div class="step {{ $order->shipped_date != null ? 'active' : ''}}"> <span class="icon"> <i class="fas fa-truck"></i> </span> <span class="text">Shipped</span> </div>
                                <div class="step {{ $order->delivered_date != null ? 'active' : ''}}"> <span class="icon"> <i class="fa fa-check"></i> </span> <span class="text">Delivered</span> </div>
                            </div>
                            <hr>

                            <a href="{{ URL::previous() }}" class="btn btn-dark"> <i class="fa fa-chevron-left"></i> Back</a>
                            <a href="{{ route('user#orderDetail',$order->order_id) }}" class="text-white btn btn-primary">View Order</a>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection

