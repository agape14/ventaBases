@extends('admin.layouts.app')
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
<div class="pt-4 row">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="bg-white breadcrumb d-flex align-items-center">
                <li class="breadcrumb-item"><a href="{{ URL::previous() }}" class="btn btn-dark btn-sm"><i class="fa fa-chevron-left"></i>  Back</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin#dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin#order') }}" class="">Orders</a></li>
              <li class="breadcrumb-item active" aria-current="page">Details</li>
            </ol>
          </nav>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="my-2 shadow-none card">
            <div class="card-body">
                <div class="track">
                    <div class="step active"> <span class="icon"> <i class="fas fa-ellipsis-h"></i> </span> <span class="text">Pending</span> </div>
                    <div class="step {{ $order->confirmed_date != null ? 'active' : ''}}"> <span class="icon"> <i class="fas fa-check-circle"></i></span> <span class="text">Confirmed</span> </div>
                    <div class="step {{ $order->processing_date != null ? 'active' : ''}}"> <span class="icon"> <i class="fas fa-spinner"></i> </span> <span class="text">Processing</span> </div>
                    <div class="step {{ $order->picked_date != null ? 'active' : ''}}"> <span class="icon"> <i class="fas fa-box"></i> </span> <span class="text">Picked</span> </div>
                    <div class="step {{ $order->shipped_date != null ? 'active' : ''}}"> <span class="icon"> <i class="fas fa-truck"></i> </span> <span class="text">Shipped</span> </div>
                    <div class="step {{ $order->delivered_date != null ? 'active' : ''}}"> <span class="icon"> <i class="fa fa-check"></i> </span> <span class="text">Delivered</span> </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="pb-4 row">
    <div class="col-6">

        <div class="my-3 shadow-none card">
            <div class="bg-transparent card-header">
                <div class="d-flex justify-content-between">
                    <div class="h5">Order Detail</div>
                    <a href="{{ route('user#download#downloadInvoice',$order->order_id) }}" class="text-white btn btn-sm btn-dark"><i class="mr-2 fas fa-download"></i> Download Invoice</a>
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
                <div class="float-right py-3 mt-3">
                    @if ($order->status == 'pending')

                        <a href="{{ route('admin#confirmOrder',$order->order_id) }}" class="shadow-lg orderStatusBtn btn btn-primary">Confirm Order</a>

                    @elseif ($order->status == 'confirmed')

                        <a href="{{ route('admin#processOrder',$order->order_id) }}" class="shadow-lg orderStatusBtn btn btn-primary">Process Order</a>

                    @elseif ($order->status == 'processing')

                        <a href="{{ route('admin#pickOrder',$order->order_id) }}" class="shadow-lg orderStatusBtn btn btn-primary">Pick Order</a>

                    @elseif ($order->status == 'picked')

                        <a href="{{ route('admin#shipOrder',$order->order_id) }}" class="shadow-lg orderStatusBtn btn btn-primary">Ship Order</a>

                    @elseif ($order->status == 'shipped')

                        <a href="{{ route('admin#deliverOrder',$order->order_id) }}" class="shadow-lg orderStatusBtn btn btn-primary">Deliver Order</a>

                    @endif

                </div>
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="my-3 shadow-none card">
            <div class="bg-transparent card-header">
                <div class="">
                    <div class="h5">Delivery Detail</div>
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


    <div class="col-12">
        <div class="shadow-none card">
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
    @if ($order->payment_method != 'cos')
    <div class="col-12">
        <div class="my-3 shadow-none card">
            <div class="bg-transparent card-header">
                <div class="d-flex justify-content-between">
                    <div class="h5">Payment Transition</div>
                    <a href="{{ route('admin#showPaymentTransition',$order->paymentTransition->id) }}" class="btn btn-primary ">View Payment Transition</a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tbody class="">
                        <tr>
                            <th>Transfer To:</th>
                            <td class="font-weight-bold">{{ $order->paymentTransition->paymentInfo->name }}-{{ $order->paymentTransition->paymentInfo->account_number }}</td>
                        </tr>
                        <tr>
                            <th>Payment Method</th>
                            <td class="font-weight-bold text-uppercase">{{ $order->paymentTransition->order->payment_method }}</td>
                        </tr>
                        <tr>
                            <th>Amount</th>
                            <td>{{ $order->paymentTransition->order->grand_total }} Ks</td>
                        </tr>
                        <tr>
                            <th>Payment Photo</th>
                            <td>
                                <div class="">

                                        <a href="{{ asset('uploads/payment/'.$order->paymentTransition->payment_screenshot) }}" data-lightbox="image-1" data-title="Payment Photo">
                                            <img src="{{ asset('uploads/payment/'.$order->paymentTransition->payment_screenshot) }}" alt="" srcset="" style="width: 200px;">
                                        </a>

                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

</div>
@endsection
@section('script')
    <script>
        $('document').ready(function(){
            $('.orderStatusBtn').on('click',function(e){
                e.preventDefault();
                let link = $(this).attr("href");
                Swal.fire({
                    title: 'Are you sure to change order status?',
                    // text: "",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Change Status!'
                    }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = link;
                        // Swal.fire(
                        // 'Updated!',
                        // 'Order Status has been changed.',
                        // 'success'
                        // )
                    }
                    })
            })
        })
    </script>
@endsection
