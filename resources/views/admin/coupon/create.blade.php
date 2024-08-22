@extends('admin.layouts.app')
@section('content')
<div class="row pt-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-white d-flex align-items-center    ">
                <li class="breadcrumb-item"><a href="{{ URL::previous() }}" class="btn btn-dark btn-sm"><i class="fa fa-chevron-left"></i>  Back</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin#dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{ route('admin#coupon') }}">Coupon</a></li>
              <li class="breadcrumb-item active" aria-current="page">Create</li>
            </ol>
          </nav>
    </div>
</div>
<div class="row">
    <div class="col-6">
        <div class="card shadow-none">
            <div class="card-header">
                <h4 class="mb-0">Create Coupon</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('admin#storeCoupon') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="">Coupon Code</label>
                        <input name="couponCode" type="text" class="form-control" placeholder="Eg. NEW COUPON CODE" value="{{ old('couponCode') }}">
                        @error('couponCode')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Coupon Discount ( % )</label>
                        <input name="couponDiscount" type="number" class="form-control" placeholder="Eg. 30" value="{{ old('couponCode') }}">
                        @error('couponDiscount')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Start Date</label>
                        <input name="startDate" type="date" min="{{ Carbon\Carbon::now()->format('Y-m-d')}}" class="form-control" placeholder="" value="{{ old('couponCode') }}">
                        @error('startDate')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">End Date</label>
                        <input name="endDate" type="date" min="{{ Carbon\Carbon::now()->format('Y-m-d')}}" class="form-control" placeholder="" value="{{ old('couponCode') }}">
                        @error('endDate')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <button class="mt-3 btn btn-primary">Create Coupon</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
