@extends('admin.layouts.app')
@section('content')
<div class="row pt-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-white d-flex align-items-center">
                <li class="breadcrumb-item"><a href="{{ URL::previous() }}" class="btn btn-dark btn-sm"><i class="fa fa-chevron-left"></i>  Back</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin#dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{ route('admin#userList') }}">User Lists</a></li>
              <li class="breadcrumb-item active" aria-current="page">Edit</li>
            </ol>
          </nav>
    </div>
</div>
<div class="row">
    <div class="col-6">
        <div class="card shadow-none">
            <div class="card-header">
                <h4 class="mb-0">Edit User</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('admin#updateUser',$user->id) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="">Name</label>
                        <input name="name" type="text" class="form-control"  value="{{ old('name',$user->name) }}">
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Email</label>
                        <input name="email" type="text" class="form-control"  value="{{ old('email',$user->email) }}">
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Role</label>
                        <select name="role" id="" class="form-control">
                            <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                        @error('role')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <button class="mt-3 btn btn-primary">Update User</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
