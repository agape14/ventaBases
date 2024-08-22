@extends('admin.layouts.app')
@section('content')
<div class="pt-4 row">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="bg-white breadcrumb d-flex align-items-center ">
                <li class="breadcrumb-item"><a href="{{ URL::previous() }}" class="btn btn-dark btn-sm"><i class="fa fa-chevron-left"></i>  Back</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin#dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{ route('admin#coupon') }}">CompanySetting</a></li>
              <li class="breadcrumb-item active" aria-current="page">Create</li>
            </ol>
          </nav>
    </div>
</div>
<div class="row">
    <div class="col-6">
        <div class="shadow-none card">
            <div class="card-header">
                <h4 class="mb-0">Create CompanySetting</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('admin#storeCompanySetting') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="">Company Name</label>
                        <input name="companyName" type="text" class="form-control" placeholder="enter company name" value="{{ old('companyName') }}">
                        @error('companyName')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Company Logo</label>
                        <input type="file" name="logo" class="form-control">
                        @error('logo')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Phone One</label>
                        <input type="text" name="phoneOne" class="form-control" placeholder="enter phone number" value="{{ old('phoneOne') }}">
                        @error('phoneOne')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Phone Two</label>
                        <input type="text" name="phoneTwo" class="form-control" placeholder="enter phone number" value="{{ old('phoneTwo') }}">
                        @error('phoneTwo')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="enter email address" value="{{ old('email') }}">
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Address</label>
                        <input type="address" name="address" class="form-control" placeholder="enter address address" value="{{ old('address') }}">
                        @error('address')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Facebook</label>
                        <input type="text" name="facebook" class="form-control" placeholder="enter facebook address" value="{{ old('facebook') }}">
                        @error('facebook')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Youtube</label>
                        <input type="text" class="form-control" name="youtube" placeholder="enter youtube address" value="{{ old('youtube') }}">
                        @error('youtube')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Linkedin</label>
                        <input type="text" class="form-control" name="linkedin" placeholder="enter linkedin address" value="{{ old('phoneOne') }}">
                        @error('linkedin')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <button class="mt-3 btn btn-primary">Add Company Setting</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
