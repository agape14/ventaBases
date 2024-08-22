@extends('admin.layouts.app')
@section('content')
<div class="row pt-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-white d-flex align-items-center">
                <li class="breadcrumb-item"><a href="{{ URL::previous() }}" class="btn btn-dark btn-sm"><i class="fa fa-chevron-left"></i>  Back</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin#dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{ route('admin#cos') }}">Cash On Delivery</a></li>
              <li class="breadcrumb-item active" aria-current="page">Edit</li>
            </ol>
          </nav>
    </div>
</div>
    <div class="row">
        <div class="col-4">
            <div class="card shadow-none">
                <div class="card-header">
                    <h4 class="mb-0">Edit Locations</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin#updateCos',$cos->id) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <input name="stateDivisionId" type="hidden" class="form-control" placeholder="enter state division name" value="{{ old('name',$cos->state_division_id) }}">
                            @error('stateDivisionId')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <input name="cityId" type="hidden" class="form-control" placeholder="enter township name" value="{{ old('name',$cos->city_id) }}">
                            @error('cityId')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <input name="townshipId" type="hidden" class="form-control" placeholder="enter township name" value="{{ old('name',$cos->township_id) }}">
                            @error('townshipId')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="">Status</label>
                            <select name="status" id="" class="form-control">
                                <option value="1" {{ $cos->status == '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ $cos->status == '0' ? 'selected' : '' }}>Unactive</option>
                            </select>
                            @error('status')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>


                        <button class="mt-3 btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-8">
            @include('admin.cashOnDelivery.list')
        </div>
    </div>
@endsection

