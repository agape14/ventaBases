@extends('admin.layouts.app')
@section('content')
<div class="pt-4 row">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="bg-white breadcrumb d-flex align-items-center ">
                <li class="breadcrumb-item"><a href="{{ URL::previous() }}" class="btn btn-dark btn-sm"><i class="fa fa-chevron-left"></i>  Back</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin#dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item active" aria-current="page">Coupon</li>
            </ol>
          </nav>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="rounded card" style="box-shadow: none !important">
            <div class="card-header">
                <div class="my-1 d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">All Coupons</h4>
                    <a href="{{ route('admin#createCoupon') }}" class="mb-0 shadow btn btn-primary"><i class="mr-2 text-white fas fa-plus-circle"></i>Create Coupon</a>
               </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="dataTable">
                        <thead class="bg-primary">
                            <tr>
                            <th>#</th>
                            <th>Coupon Code</th>
                            <th>Coupon Discount</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Order Count</th>
                            <th>Status</th>
                            <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                                <tr>
                                    <th>{{ $item->coupon_id }}</th>
                                    <td>{{ $item->coupon_code }}</td>
                                    <td>{{ $item->coupon_discount }} %</td>
                                    <td>{{ Carbon\Carbon::parse($item->start_date)->format('d F Y') }}</td>
                                    <td>{{ Carbon\Carbon::parse($item->end_date)->format('d F Y') }}</td>
                                    <td>{{ $item->order_count }}</td>
                                    <td>
                                        @if ($item->start_date <= Carbon\Carbon::now() && $item->end_date >= Carbon\Carbon::now())
                                            <div class="badge bg-success">active</div>
                                        @else
                                            <div class="badge bg-danger">Inactive</div>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin#editCoupon',$item->coupon_id) }}" class="btn btn-success btn-sm"><i class="fas fa-edit"></i></a>
                                        <a href="{{ route('admin#deleteCoupon',$item->coupon_id) }}" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure to delete?')"><i class="fas fa-trash"></i></a>
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
