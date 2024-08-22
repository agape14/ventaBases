@extends('admin.layouts.app')
@section('content')
<div class="pt-4 row">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="bg-white breadcrumb d-flex align-items-center">
                <li class="breadcrumb-item"><a href="{{ URL::previous() }}" class="btn btn-dark btn-sm"><i class="fa fa-chevron-left"></i>  Back</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin#dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item active" aria-current="page">Stock History</li>
            </ol>
          </nav>
    </div>
</div>
<div class="pb-4 row">
    <div class="col-12">
        <div class="rounded card" style="box-shadow: none !important">
            <div class="card-header">
               <div class="my-1 d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">Stock History- <div class="badge bg-dark">{{ $data->count() }}</div></h4>
               </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="dataTable">
                        <thead class="bg-primary text-nowrap">
                          <tr>
                            <th>#</th>
                            <th>Product Image</th>
                            <th>Name</th>
                            <th>Color</th>
                            <th>Size</th>
                            <th>Note</th>
                            <th>Quantity</th>
                            <th>Type</th>
                            <th>Created At</th>
                          </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                                <tr>
                                    <td>{{ $item->id}}</td>
                                    <td>
                                        <img src="{{ asset('uploads/products/'.$item->product->preview_image) }}" width="100px" alt="" srcset="">
                                    </td>
                                    <td>{{ $item->product->name }}</td>
                                    <td>
                                        @if ($item->productVariant->color_id != null)
                                            {{ $item->productVariant->color->name }}
                                        @else
                                            ---
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->productVariant->size_id != null)
                                            {{ $item->productVariant->size->name }}
                                        @else
                                            ---
                                        @endif
                                    </td>
                                    <td>{{ $item->note }}</td>
                                    <td class="font-weight-bold">{{ $item->quantity }}</td>
                                    <td>
                                        @if ($item->type == 'in')
                                            <h5><div class="badge bg-success">{{ $item->type }}</div></h5>
                                        @else
                                            <h5><div class="badge bg-danger">{{ $item->type }}</div></h5>
                                        @endif
                                    </td>
                                    <td>{{ $item->created_at->diffForHumans() }}</td>
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
