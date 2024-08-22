@extends('admin.layouts.app')
@section('content')
<div class="row pt-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-white d-flex align-items-center    ">
            <li class="breadcrumb-item"><a href="{{ URL::previous() }}" class="btn btn-dark btn-sm"><i class="fa fa-chevron-left"></i>  Back</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin#dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{ route('admin#city') }}">City</a></li>
              <li class="breadcrumb-item active" aria-current="page">Edit</li>
            </ol>
          </nav>
    </div>
</div>
    <div class="row">
        <div class="col-4">
            <div class="card shadow-none">
                <div class="card-header">
                    <h4 class="mb-0">Edit City</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin#updateCity',$city->city_id) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="">Name</label>
                            <input name="name" type="text" class="form-control" placeholder="enter state division name" value="{{ old('name',$city->name) }}">
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="">State Division</label>
                            <select name="stateDivisionId" id="" class="form-control">
                                <option value="">-----Select State Division-----</option>
                                @foreach ($stateDivisions as $item)
                                    <option value="{{ $item->state_division_id }}" {{ old('state_division_id',$city->state_division_id) == $item->state_division_id ? 'selected' : '' }}>{{ $item->name }}</option>
                                @endforeach
                            </select>
                            @error('stateDivisionId')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <button class="mt-3 btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-8">
            @include('admin.city.list')
        </div>
    </div>
@endsection
