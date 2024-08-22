@extends('frontEnd.layouts.app')
@section('content')
    <section class="py-4 min-vh-100">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb d-flex align-items-center ">
                            <li class="breadcrumb-item"><a href="{{ URL::previous() }}" class="btn btn-dark btn-sm"><i class="fa fa-chevron-left"></i> Back</a></li>
                          <li class="breadcrumb-item"><a href="{{ route('frontend#index') }}">Home</a></li>
                          <li class="breadcrumb-item"><a href="#">Profile</a></li>
                          <li class="breadcrumb-item active" aria-current="page">My Review</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="row">
                <div class="col-3">
                    @include('frontEnd.profile/profileSidebar')

                </div>
                <div class="col-9">
                    <div class="bg-white border-0 rounded card">
                        <div class="bg-transparent card-header">
                            <div class="my-1 d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">My Orders</h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover" id="dataTable">
                                    <thead class="text-white bg-primary text-nowrap">
                                        <tr>
                                            <th>#</th>
                                            <th>Title</th>
                                            <th>Review</th>
                                            <th>Product</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($reviews as $item)
                                        <tr>
                                            <td>{{ $item->product_review_id}}</td>
                                            <td>{{ $item->title }}</td>
                                            <td>{{ substr($item->comment,0,150) }}.....</td>
                                            <td>
                                                <div class="">
                                                        <img src="{{ asset('uploads/products/'.$item->product->preview_image) }}" class="p-1 bg-white rounded-circle" alt="" srcset="" style="width: 50px !important; height: 50px !important">
                                                        <div class="mt-1">
                                                            <p class="mb-0">{{ $item->product->name }}</p>
                                                        </div>
                                                </div>
                                            </td>
                                            <td>
                                                @if ($item->status == 1)
                                                    <div class="badge bg-success">Approved</div>
                                                @else
                                                    <div class="badge bg-danger">Pending</div>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $item->created_at->diffForHumans() }}
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
        </div>
    </section>
@endsection
