@extends('admin.layouts.app')
@section('content')
<div class="pt-4 row">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="bg-white breadcrumb d-flex align-items-center ">
                <li class="breadcrumb-item"><a href="{{ URL::previous() }}" class="btn btn-dark btn-sm"><i class="fa fa-chevron-left"></i>  Back</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin#dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item active" aria-current="page">Company Setting</li>
            </ol>
          </nav>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="rounded card" style="box-shadow: none !important">
            <div class="card-header">
                <div class="my-1 d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">All</h4>
                    <a href="{{ route('admin#createCompanySetting') }}" class="mb-0 shadow btn btn-primary"><i class="mr-2 text-white fas fa-plus-circle"></i>Add Company Info</a>
               </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="dataTable">
                        <thead class="bg-primary">
                            <tr>
                                <th>#</th>
                                <th>Company Logo</th>
                                <th>Company Name</th>
                                <th>Phone Number</th>
                                <th>Email</th>
                                <th>Address</th>
                                <th>Social</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>
                                        <img src="{{ asset('uploads/logo/'.$item->logo) }}" alt="Logo" style="width: 100px">
                                    </td>
                                    <td>{{ $item->company_name }}</td>
                                    <td>
                                        <p><i class="mr-2 fas fa-phone text-dark"></i>{{ $item->phone_one }}</p>
                                        <p><i class="mr-2 fas fa-phone text-dark"></i>{{ $item->phone_two }}</p>
                                    </td>
                                    <td>
                                        {{ $item->email }}
                                    </td>
                                    <td>
                                        {{ $item->address }}
                                    </td>
                                    <td>
                                        @if ($item->facebook)
                                            <p><i class="mr-2 fab fa-facebook text-dark"></i> {{ $item->facebook}}</p>
                                        @endif
                                        @if ($item->youtube)
                                            <p><i class="mr-2 fab fa-youtube text-dark"></i> {{ $item->youtube}}</p>
                                        @endif
                                        @if ($item->linkedin)
                                            <p><i class="mr-2 fab fa-linkedin text-dark"></i> {{ $item->linkedin}}</p>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin#editCompanySetting',$item->id) }}" class="btn btn-success btn-sm"><i class="fas fa-edit"></i></a>
                                        @if ($data->count() != 1)
                                            <a href="{{ route('admin#deleteCompanySetting',$item->id) }}" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure to delete?')"><i class="fas fa-trash"></i></a>
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
