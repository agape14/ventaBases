@extends('admin.layouts.app')
@section('content')
<div class="row pt-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-white d-flex align-items-center">
                <li class="breadcrumb-item"><a href="{{ URL::previous() }}" class="btn btn-dark btn-sm"><i class="fa fa-chevron-left"></i>  Back</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin#dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item active" aria-current="page">SubCategory</li>
            </ol>
          </nav>
    </div>
</div>
    <div class="row">
        <div class="col-4">
            <div class="card shadow-none">
                <div class="card-header">
                    <h4 class="mb-0">Add SubCategory</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin#createSubCategory') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="">Name</label>
                            <input name="name" type="text" class="form-control" placeholder="enter brand name" value="{{ old('name') }}">
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="">Category</label>
                            <select name="categoryId" class="form-control" id="">
                                <option value="">-----Select Category-----</option>
                                @foreach ($categories as $item)
                                    <option value="{{ $item->category_id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                            @error('categoryId')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <button class="mt-3 btn btn-primary">Add SubCategory</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-8">
            @include('admin.subCategory.list')
        </div>
    </div>
@endsection
