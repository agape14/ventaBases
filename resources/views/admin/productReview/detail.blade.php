@extends('admin.layouts.app')
@section('content')
<div class="pt-4 row">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-white d-flex align-items-center">
                <li class="breadcrumb-item"><a href="{{ URL::previous() }}" class="btn btn-dark btn-sm"><i class="fa fa-chevron-left"></i>  Back</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin#dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin#productReview') }}">Product Review</a></li>
              <li class="breadcrumb-item active" aria-current="page">Details</li>
            </ol>
          </nav>
    </div>
</div>
<div class="pb-4 row">
    <div class="col-7">
        <div class="rounded card" style="box-shadow: none !important">
            <div class="card-header">
               <div class="my-1 d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">Product Review Detail</h4>
                    <a href="{{ route('admin#showProduct',$review->product->product_id) }}" class="mb-2 btn btn-primary">View Product</a>

               </div>
            </div>
            <div class="card-body">

                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th>Product</th>
                            <td>
                                <img src="{{ asset('uploads/products/'.$review->product->preview_image) }}" class="p-1 bg-white rounded-circle" alt="" srcset="" style="width: 100px !important; height: 100px !important">
                            </td>
                        </tr>
                        <tr>
                            <th>Product Name</th>
                            <td>{{ $review->product->name }}</td>
                        </tr>
                        <tr>
                            <th>Title</th>
                            <td>{{ $review->title }}</td>
                        </tr>

                        <tr>
                            <th>Status</th>
                            <td>
                                @if ($review->status == 1)
                                            <div class="badge badge-success">Approved</div>
                                        @else
                                            <div class="badge badge-danger">Pending</div>
                                        @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Review</th>
                            <td>{{ $review->comment }}</td>
                        </tr>
                        <tr>
                            <th>Date</th>
                            <td>{{ $review->created_at->diffForHumans() }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-5">
        <div class="rounded card" style="box-shadow: none !important">
            <div class="card-header">
               <div class="my-1 d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">User Information</h4>
               </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th>Photo</th>
                            <td>
                                @if (!empty($review->user->profile_photo_path))
                                                <img src="{{ asset('uploads/user/'.$review->user->profile_photo_path) }}" class="p-1 bg-white rounded-circle" alt="" srcset="" style="width: 100px !important; height: 100px !important">
                                            @else
                                                <img src="{{ asset('frontEnd/resources/image/user-default.png') }}" class="p-1 bg-white rounded-circle" alt="" srcset="" style="width: 100px !important; height: 100px !important">
                                            @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Name</th>
                            <td>{{ $review->user->name }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $review->user->email }}</td>
                        </tr>
                        <tr>
                            <th>Created At</th>
                            <td>{{ $review->user->created_at->diffForHumans() }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
