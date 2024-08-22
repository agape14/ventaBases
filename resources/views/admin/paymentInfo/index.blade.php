@extends('admin.layouts.app')
@section('content')
<div class="row pt-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-white d-flex align-items-center    ">
              <li class="breadcrumb-item"><a href="{{ URL::previous() }}" class="btn btn-dark btn-sm"><i class="fa fa-chevron-left"></i>  Back</a></li>
              <li class="breadcrumb-item"><a href="{{ route('admin#dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item active" aria-current="page">Payment Info</li>
            </ol>
          </nav>
    </div>
</div>
    <div class="row">
        <div class="col-12">
            <div class="card rounded" style="box-shadow: none !important">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between my-1">
                        <h4 class="mb-0">All Payment Info</h4>
                        <a href="{{ route('admin#createPaymentInfo') }}" class="btn btn-primary mb-0 shadow btn"><i class="fas fa-plus-circle text-white mr-2"></i> Add Payment Info</a>
                   </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="dataTable">
                            <thead class="bg-primary">
                              <tr>
                                <th>#</th>
                                <th>Account Name</th>
                                <th>Account Number</th>
                                <th>Type</th>
                                <th>Qr Code</th>
                                <th>Total Transition</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Action</th>
                              </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $item)
                                    <tr>
                                        <th>{{ $item->id }}</th>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->account_number }}</td>
                                        <td class="font-weight-bold text-uppercase">{{ $item->type }}</td>
                                        @if ($item->qr_code)
                                            <td>
                                                <img src="{{ asset('uploads/QRCode/'.$item->qr_code) }}" class="rounded" alt="" srcset="" style="width: 60px; height: 60px;">
                                            </td>
                                        @else
                                        <td>
                                            No QR code
                                        </td>
                                        @endif
                                        <td>
                                            {{$item->payment_transition_count}}
                                        </td>
                                        <td>
                                            @if ($item->status == '1')
                                                <div class="badge bg-success">Active</div>
                                            @else
                                                <div class="badge bg-danger">Off</div>
                                            @endif
                                        </td>
                                        <td>{{ $item->created_at }}</td>
                                        <td>
                                            <a href="{{ route('admin#editPaymentInfo',$item->id) }}" class="btn btn-success btn-sm"><i class="fas fa-edit"></i></a>
                                            <a href="{{ route('admin#deletePaymentInfo',$item->id) }}" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure to delete?')"><i class="fas fa-trash"></i></a>
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
