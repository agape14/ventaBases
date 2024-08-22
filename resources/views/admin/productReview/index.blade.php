@extends('admin.layouts.app')
@section('content')
<div class="pt-4 row">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-white d-flex align-items-center">
                <li class="breadcrumb-item"><a href="{{ URL::previous() }}" class="btn btn-dark btn-sm"><i class="fa fa-chevron-left"></i>  Back</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin#dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item active" aria-current="page">Product Review</li>
            </ol>
          </nav>
    </div>
</div>
<div class="pb-4 row">
    <div class="col-12">
        <div class="rounded card" style="box-shadow: none !important">
            <div class="card-header">
               <div class="my-1 d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">All Product Review</h4>
               </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="dataTable">
                        <thead class="bg-primary text-nowrap">
                          <tr>
                            <th>#</th>
                            <th>User Name</th>
                            <th>Title</th>
                            <th>Review</th>
                            <th>Product</th>
                            <th>Status</th>
                            <th>Created_at</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                                <tr>
                                    <td>{{ $item->product_review_id}}</td>
                                    <td>
                                        <div class="d-flex">
                                            @if (!empty($item->user->profile_photo_path))
                                                <img src="{{ asset('uploads/user/'.$item->user->profile_photo_path) }}" class="p-1 bg-white rounded-circle" alt="" srcset="" style="width: 50px !important; height: 50px !important">
                                            @else
                                                <img src="{{ asset('frontEnd/resources/image/user-default.png') }}" class="p-1 bg-white rounded-circle" alt="" srcset="" style="width: 50px !important; height: 50px !important">
                                            @endif
                                                <div class="ms-2">
                                                    <p class="mb-0">{{ $item->user->name }}</p>
                                                    <p class="mb-0 text-secondary">{{ $item->user->created_at->diffForHumans() }}</p>
                                                </div>
                                        </div>
                                    </td>
                                    <td>{{ $item->title }}</td>
                                    <td>{{ substr($item->comment,0,70) }}.....</td>
                                    <td>
                                        <div class="d-flex">
                                                <img src="{{ asset('uploads/products/'.$item->product->preview_image) }}" class="p-1 bg-white rounded-circle" alt="" srcset="" style="width: 50px !important; height: 50px !important">
                                                <div class="ml-2">
                                                    <p class="mb-0">{{ $item->product->name }}</p>
                                                    <p class="mb-0 text-secondary">{{ $item->product->created_at->diffForHumans() }}</p>
                                                </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($item->status == 1)
                                            <div class="badge badge-success">Approved</div>
                                        @else
                                            <div class="badge badge-danger">Pending</div>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $item->created_at->diffForHumans() }}
                                    </td>
                                    <td class="text-wrap">
                                        @if ($item->status == 0)
                                        <a href="{{ route('admin#approveReview',$item->product_review_id) }}" class="mb-2 btn btn-success btn-sm" onclick="return confirm('Are you sure to approve this review?')">Approve<i class="ml-2 fas fa-check"></i></a>
                                        @endif
                                        <a href="{{ route('admin#showReview',$item->product_review_id) }}" class="mb-2 btn btn-info btn-sm"><i class="fas fa-eye "></i></a>
                                        <a href="{{ route('admin#deleteReview',$item->product_review_id) }}" class="mb-2 btn btn-danger btn-sm" onclick="return confirm('Are you sure to delete?')"><i class="fas fa-trash "></i></a>
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
