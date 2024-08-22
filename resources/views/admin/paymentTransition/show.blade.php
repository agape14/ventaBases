@extends('admin.layouts.app')

@section('content')
<div class="pt-4 row">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-white d-flex align-items-center">
                <li class="breadcrumb-item"><a href="{{ URL::previous() }}" class="btn btn-dark btn-sm"><i class="fa fa-chevron-left"></i>  Back</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin#dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin#paymentTransition') }}" class="">Payment Transition</a></li>
              <li class="breadcrumb-item active" aria-current="page">Details</li>
            </ol>
          </nav>
    </div>
</div>
<div class="pb-4 row">
    <div class="col-6">
        <div class="my-3 shadow-none card">
            <div class="bg-transparent card-header">
                <div class="">
                    <div class="h5">Payment Transition</div>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tbody class="">
                        <tr>
                            <th>Invoice Number</th>
                            <td>{{ $paymentTransition->order->invoice_number }}</td>
                        </tr>
                        <tr>
                            <th>Transfer To:</th>
                            <td class="font-weight-bold">{{ $paymentTransition->paymentInfo->name }}-{{ $paymentTransition->paymentInfo->account_number }}</td>
                        </tr>
                        <tr>
                            <th>Payment Method</th>
                            <td class="font-weight-bold text-uppercase">{{ $paymentTransition->order->payment_method }}</td>
                        </tr>
                        <tr>
                            <th>Amount</th>
                            <td>{{ $paymentTransition->order->grand_total }} Ks</td>
                        </tr>
                        <tr>
                            <th>Payment Photo</th>
                            <td>
                                <div class="">

                                        <a href="{{ asset('uploads/payment/'.$paymentTransition->payment_screenshot) }}" data-lightbox="image-1" data-title="Payment Photo">
                                            <img src="{{ asset('uploads/payment/'.$paymentTransition->payment_screenshot) }}" alt="" srcset="" style="width: 200px;">
                                        </a>

                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="my-3 shadow-none card">
            <div class="bg-transparent card-header">
                <div class="d-flex justify-content-between">
                    <div class="h5 mb-0">Order Detail</div>
                    <a href="{{ route('admin#showOrder',$paymentTransition->order_id) }}" class="btn btn-primary">View Order</a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tbody class="">
                        <tr>
                            <th>Invoice Number</th>
                            <td>{{ $paymentTransition->order->invoice_number }}</td>
                        </tr>
                        <tr>
                            <th>Payment</th>
                            <td>{{ $paymentTransition->order->payment_method }}</td>
                        </tr>
                        <tr>
                            <th>Sub Total</th>
                            <td>{{ $paymentTransition->order->sub_total }}</td>
                        </tr>
                        @if (!empty($order->coupon_id))
                        <tr>
                            <th>Coupon Discount</th>
                            <td>{{ $paymentTransition->order->coupon_discount }} Ks</td>
                        </tr>
                        @endif
                        <tr>
                            <th>Grand Total</th>
                            <td>{{ $paymentTransition->order->grand_total }} Ks</td>
                        </tr>
                        <tr>
                            <th>Order Date</th>
                            <td>{{ $paymentTransition->order->order_date }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                <div class="badge bg-success">{{ $paymentTransition->order->status }}</div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="my-3 shadow-none card">
            <div class="bg-transparent card-header">
                <div class="">
                    <div class="h5">Customer Information</div>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tbody class="">
                        <tr>
                            <th>Name</th>
                            <td>{{ $paymentTransition->order->name }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $paymentTransition->order->email }}</td>
                        </tr>
                        <tr>
                            <th>Phone</th>
                            <td>{{ $paymentTransition->order->phone }}</td>
                        </tr>
                        <tr>
                            <th>Address</th>
                            <td>{{ $paymentTransition->order->address }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

