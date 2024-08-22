@extends('admin.layouts.app')
@section('content')
<div class="row pt-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-white d-flex align-items-center    ">
              <li class="breadcrumb-item"><a href="{{ URL::previous() }}" class="btn btn-dark btn-sm"><i class="fa fa-chevron-left"></i>  Back</a></li>
              <li class="breadcrumb-item"><a href="{{ route('admin#dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{ route('admin#paymentInfo') }}">Dashboard</a></li>
              <li class="breadcrumb-item active" aria-current="page">Create</li>
            </ol>
          </nav>
    </div>
</div>
    <div class="row">
        <div class="col-5">
            <div class="card shadow-none">
                <div class="card-header">
                    <h4 class="mb-0">Add Payment Info</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin#storePaymentInfo') }}" method="POST"  enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="">Account Name</label>
                            <input name="name" type="text" class="form-control" placeholder="enter your account name" value="{{ old('name') }}">
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="">Account Number</label>
                            <input name="accountNumber" type="text" class="form-control" placeholder="enter your account name" value="{{ old('name') }}">
                            @error('accountNumber')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="">QR Code</label>
                            <input name="qrCode" type="file" class="form-control" value="{{ old('qrCode') }}">
                            @error('qrCode')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="">Payment Type</label>
                            <select name="type" id="" class="form-control">
                                <option value="kpay">Kpay</option>
                                <option value="wave">Wave</option>
                            </select>
                            @error('type')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="">Status</label>
                            <select name="status" id="" class="form-control">
                                <option value="1">Active</option>
                                <option value="0">Off</option>
                            </select>
                            @error('status')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <button class="mt-3 btn btn-primary">Add Payment</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
