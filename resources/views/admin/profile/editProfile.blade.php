@extends('admin.layouts.app')
@section('content')
<div class="row pt-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-white d-flex align-items-center">
                <li class="breadcrumb-item"><a href="{{ URL::previous() }}" class="btn btn-dark btn-sm"><i class="fa fa-chevron-left"></i>  Back</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin#dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item active" aria-current="page">Edit Profile</li>
            </ol>
          </nav>
    </div>
</div>
<div class="row">
    <div class="col-4">
        <div class="card shadow-none rounded">
            <div class="card-header bg-primary">
                <h4 class="mb-0">Edit Profile</h4>
            </div>
            <div class="card-body">
                @if (!empty($data->profile_photo_path))
                    <img src="{{ asset('uploads/user/'.$data->profile_photo_path) }}" class="rounded-circle" alt="" srcset="" style="width: 100px !important; height: 100px !important">
                @else

                    <img src="{{ asset('frontEnd/resources/image/user-default.png') }}" class="rounded-circle" alt="" srcset="" style="width: 100px !important; height: 100px !important">

                @endif
                <form action="{{ route('admin#editProfile') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="">Change Profile Photo</label>
                        <input name="photo" type="file" class="form-control">
                        @error('photo')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Name</label>
                        <input name="name" type="text" class="form-control" value="{{ $data->name }}">
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Email Address</label>
                        <input name="email" type="email" class="form-control" value="{{ $data->email }}">
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <hr>
                    <button class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
