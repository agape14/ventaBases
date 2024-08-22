@extends('admin.layouts.app')
@section('content')
<div class="row pt-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-white d-flex align-items-center">
                <li class="breadcrumb-item"><a href="{{ URL::previous() }}" class="btn btn-dark btn-sm"><i class="fa fa-chevron-left"></i>  Back</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin#dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item active" aria-current="page">Product Stocks</li>
            </ol>
          </nav>
    </div>
</div>
<div class="row pb-4">
    <div class="col-12">
        <div class="card rounded" style="box-shadow: none !important">
            <div class="card-header">
               <div class="d-flex align-items-center justify-content-between my-1">
                    <h4 class="mb-0">All Product Stocks</h4>
                    <form class="d-flex align-items-center" action="{{ route('admin#productStockFilter') }}" method="GET">
                        @csrf
                        <p class="mb-0 text-nowrap mr-2">Product : </p>
                        <select name="productId" id="" class="custom-select mb-0 mr-2">
                            <option value="">All</option>
                            @foreach ($products as $item)
                                <option value="{{ $item->product_id }}" {{ $item->product_id == request()->productId ? 'selected' : ''}}>{{ $item->name }}</option>
                            @endforeach
                        </select>
                        <button class="btn btn-primary">Filter</button>
                    </form>
               </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="dataTable">
                        <thead class="bg-primary text-nowrap">
                          <tr>
                            <th>#</th>
                            <th style="width: 100px">Preview Image</th>
                            <th style="width: 100px">Name</th>
                            <th>Color</th>
                            <th>Size</th>
                            <th>Stock</th>
                            <th>Publish Status</th>
                            <th>Action</th>
                          </tr>
                        </thead>

                        <tbody>
                            @foreach ($data as $p)
                                @foreach ($p['product_variant'] as $v)
                                    <tr>
                                        <td>{{ $p['product_id']}}</td>
                                        <td>
                                            <img src="{{ asset('uploads/products').'/'.$p['preview_image'] }}" class="rounded shadow-sm" alt="" srcset="" style="width: 90px; height: 90px;">
                                        </td>
                                        <td>{{ $p['name'] }}</td>

                                        @if ($v['color_id'] != null)
                                            <td>{{ $v['color']['name'] }}</td>
                                        @else
                                            <td>---</td>
                                        @endif

                                        @if ($v['size_id'] != null)
                                            <td>{{ $v['size']['name'] }}</td>
                                        @else
                                            <td>---</td>
                                        @endif
                                        <td>
                                            <p class="mb-0 font-weight-bolder {{ $v['available_stock'] <= 5 ? 'text-danger' : '' }}">{{ $v['available_stock'] }}</p>
                                        </td>
                                        <td>
                                            @if ($p['publish_status'] == 1)
                                                 <div class="badge badge-success">Publish</div>
                                            @else
                                                <div class="badge badge-danger">Unpublish</div>
                                            @endif
                                        </td>
                                        <td class="text-wrap">
                                            <a href="{{ route('admin#showProduct',$p['product_id']) }}" class="btn btn-info btn-sm mb-2"><i class="fas fa-eye "></i></a>
                                            <a href="{{ route('admin#editVariant',$v['product_variant_id']) }}" class="btn btn-success btn-sm mb-2"><i class="fas fa-edit "></i></a>
                                            <a href="{{ route('admin#createVariant',$p['product_id']) }}" class="btn btn-dark btn-sm mb-2"><i class="fas fa-plus-circle"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach

                        </tbody>
                      </table>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
