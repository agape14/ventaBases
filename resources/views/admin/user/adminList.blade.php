@extends('admin.layouts.app')
@section('content')
<div class="pt-4 row">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-white d-flex align-items-center">
                <li class="breadcrumb-item"><a href="{{ URL::previous() }}" class="btn btn-dark btn-sm"><i class="fa fa-chevron-left"></i>  Back</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin#dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item active" aria-current="page">Admin Lists</li>
            </ol>
          </nav>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="rounded card" style="box-shadow: none !important">
            <div class="card-header">
                <div class="my-1 d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">All Admin Lists</h4>
               </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="dataTable">
                        <thead class="bg-primary">
                            <tr>
                                <th>#</th>
                                <th>Photo</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Crated At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>
                                        @if (!empty($item->profile_photo_path))
                                            <img src="{{ asset('/uploads/user/'.$item->profile_photo_path) }}" class="rounded shadow"  alt="" srcset="" style="width: 80px; height: 80px;">
                                        @else
                                            <img src="{{ asset('frontEnd/resources/image/user-default.png') }}" class="bg-white rounded shadow"  alt="" srcset="" style="width: 80px; height: 80px;">
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <p>{{ $item->name }}</p>
                                            @if (auth()->user()->id == $item->id)
                                        <p class="text-success font-weight-bold">( You )</p>
                                    @endif
                                        </div>
                                    </td>
                                    <td>{{ $item->email }}</td>
                                    <td>{{ $item->role }}</td>
                                    <td>{{ $item->created_at }}</td>
                                    <td>
                                        <a href="{{ route('admin#editUser',$item->id) }}" class="btn btn-success btn-sm"><i class="fas fa-edit"></i></a>
                                        @if (auth()->user()->id != $item->id)
                                        <a href="{{ route('admin#deleteUser',$item->id) }}" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure to delete?')"><i class="fas fa-trash"></i></a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                        </table>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
