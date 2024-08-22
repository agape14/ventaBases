@extends('frontEnd.layouts.app')
@section('content')
@php
    $paymentInfo = App\Models\PaymentInfo::where('status','1')->where('type',$data['paymentMethod'])->first();
@endphp
<section class="py-4 min-vh-100">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb d-flex align-items-center ">
                        <li class="breadcrumb-item"><a href="{{ URL::previous() }}" class="btn btn-dark btn-sm"><i class="fa fa-chevron-left"></i> Back</a></li>
                      <li class="breadcrumb-item"><a href="{{ route('frontend#index') }}">Home</a></li>
                      <li class="breadcrumb-item"><a href="{{ route('frontend#viewCarts') }}">Checkout</a></li>
                      <li class="breadcrumb-item active" aria-current="page">
                        Payment
                      </li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
            @php
                $subTotal = Session::has('subTotal') ? Session::get('subTotal') : '0';
                $couponDiscount = Session::has('coupon') ? Session::get('coupon')['couponDiscount'] : '0';
                $discountAmount = round($subTotal * $couponDiscount/100);
                $GrandTotal = $subTotal - $discountAmount;
            @endphp
            <div class="mb-3 bg-white border-0 card">
                <div class="card-body">
                    <p class="mb-0">Your purchase cost is <strong>{{ $GrandTotal }} Ks</strong>.You can pay {{$GrandTotal }} Ks to our account <p class="mb-0 d-inline-block"><strong class="text-uppercase">" {{ $paymentInfo->type }}</strong> Number - <strong>{{ $paymentInfo->account_number }}</strong> </p> " </p>
                </div>
            </div>
            <div class="bg-white border-0 card">
                <div class="bg-transparent card-header ">
                    <h5 class="my-2">Your Shopping Amount</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3 d-flex justify-content-between">
                        <h6 class="mb-0">Sub Total :</h6>
                        <h5 class="mb-0">{{$subTotal}} Ks</h5>
                    </div>
                    <div class="d-flex justify-content-between">
                        <h6 class="mb-0">Coupon Discount :</h6>
                        <h5 class="mb-0 couponDiscount">-{{ $discountAmount }} Ks</h5>
                    </div>
                    <hr>
                    <div class="mb-3 d-flex justify-content-between">
                        <h6 class="mb-0">Grand Total :</h6>
                        <h5 class="mb-0">{{ $GrandTotal }} Ks</h5>
                    </div>
                </div>
            </div>
            </div>
            <div class="col-6">
                <div class="border-0 card">

                    <div class="card-body">
                        <div class="card">
                            <div class="bg-transparent card-header ">
                                <div class="d-flex">
                                    @if ($data['paymentMethod'] == 'kpay')
                                        <img src="{{ asset('frontEnd/resources/image/kpay.png') }}" alt="" srcset="" class="rounded" style="width: 100px">
                                    @else
                                        <img src="{{ asset('frontEnd/resources/image/warvemoney.png') }}" alt="" srcset="" class="rounded" style="width: 100px">
                                    @endif
                                    <h5 class="my-2 ms-2">Transfer To:</h5>
                                </div>
                            </div>

                            <div class="card-body d-flex align-items-baseline">
                                <div class="" style="width: 80%">
                                    <p>Name - <strong>{{ $paymentInfo->name }}</strong></p>
                                    <p class="mb-0"><strong class="text-uppercase">{{ $paymentInfo->type }}</strong> Number - <strong>{{ $paymentInfo->account_number }}</strong> </p>
                                </div>
                                @if ($paymentInfo->qr_code)
                                    <div class="text-center" style="width: 20%">
                                        <img src="{{ asset('uploads/QRCode/'.$paymentInfo->qr_code) }}" class="" alt="" srcset="" style="width: 100px">

                                        <!-- Button trigger modal -->
                                        <button type="button" class="my-0 btn btn-sm btn-outline-dark w-100" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                            View
                                        </button>

                                        <!-- Modal -->
                                        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                <h5 class="modal-title text-uppercase" id="exampleModalLabel">{{ $paymentInfo->type }} QR Code</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <img src="{{ asset('uploads/QRCode/'.$paymentInfo->qr_code) }}" alt="" srcset="" style="width: 100%">
                                                </div>
                                                <div class="modal-footer">
                                                <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                            </div>
                        </div>
                        <div class="mt-3 card" style="border: 1px solid var(--bs-primary)">
                            <div class="bg-transparent card-header">
                                <h5 class="my-2">Your Payment</h5>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('user#confirmPayment') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="">
                                        <input type="hidden" name="name" value="{{ $data['name'] }}">
                                        <input type="hidden" name="email" value="{{ $data['email'] }}">
                                        <input type="hidden" name="phone" value="{{ $data['phone'] }}">
                                        <input type="hidden" name="stateDivisionId" value="{{ $data['stateDivisionId'] }}">
                                        <input type="hidden" name="cityId" value="{{ $data['cityId'] }}">
                                        <input type="hidden" name="townshipId" value="{{ $data['townshipId'] }}">
                                        <input type="hidden" name="address" value="{{ $data['address'] }}">
                                        <input type="hidden" name="note" value="{{ $data['note'] }}">
                                        <input type="hidden" name="paymentMethod" value="{{ $data['paymentMethod'] }}">
                                        <input type="hidden" name="paymentInfoId" value="{{ $paymentInfo->id }}">
                                    </div>
                                    <div class="my-3">
                                        <label for="" class="form-label">Your Payment Screenshots</label>
                                        <input name="paymentScreenshot" type="file" class="form-control" required>
                                        @error('paymentScreenshot')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                    </div>
                                    <button class="mt-4 mb-2 text-white shadow btn btn-primary float-end">Confirm Payment</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>

</section>
@endsection

