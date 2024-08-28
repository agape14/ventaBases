@extends('admin.layouts.app')
@section('content')
<div class="pt-4 row">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="bg-white breadcrumb d-flex align-items-center">
                <li class="breadcrumb-item"><a href="{{ URL::previous() }}" class="btn btn-dark btn-sm"><i class="fa fa-chevron-left"></i>  Back</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin#dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item active" aria-current="page">Products</li>
            </ol>
          </nav>
    </div>
</div>
<div class="pb-4 row">
    <div class="col-12">
        <div class="rounded card" style="box-shadow: none !important">
            <div class="card-header">
               <div class="my-1 d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">All Products- <div class="badge bg-dark">{{ $data->count() }}</div></h4>
                    <a href="{{ route('admin#createProduct') }}" class="mb-0 shadow btn btn-primary"><i class="mr-2 text-white fas fa-plus-circle"></i> Add Product</a>
               </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="dataTable">
                        <thead class="bg-primary text-nowrap">
                          <tr>
                            <th>#</th>
                            <th>Preview Image</th>
                            <th>Name</th>
                            <th>Brand</th>
                            <th>Category</th>
                            <th>SubCategory</th>
                            <th>Sub-SubCategory</th>
                            <th>Variants</th>
                            <th>Selling Price</th>
                            <th>Discount Price</th>
                            <th>Publish Status</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                                <tr>
                                    <th>{{ $item->product_id}}</th>
                                    <td>
                                        <img src="{{ asset('uploads/products').'/'.$item->preview_image }}" class="rounded shadow-sm" alt="" srcset="" style="width: 90px; height: 90px;">
                                    </td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->brand->name }}</td>
                                    <td>{{ $item->category->name??''}}</td>
                                    <td>{{ $item->subcategory->name??'' }}</td>
                                    <td>{{ $item->subsubcategory->name??'' }}</td>
                                    <td>
                                        @if (!$item->totalVariants == 0)
                                            <a href="{{ route('admin#createVariant',$item->product_id) }}" class="text-danger " style="text-decoration: underline">{{ $item->totalVariants }}</a>
                                        @else
                                            {{ $item->totalVariants }}
                                        @endif
                                    </td>
                                    <td>{{ $item->selling_price }}</td>
                                    <td>
                                        @if (!empty($item->discount_price))
                                            {{ $item->discount_price }}
                                        @else
                                            0
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->publish_status == 1)
                                            <div class="badge badge-success">Publish</div>
                                        @else
                                        <div class="badge badge-danger">Unpublish</div>
                                        @endif
                                    </td>
                                    <td class="text-wrap">
                                        <a href="{{ route('admin#showProduct',$item->product_id) }}" class="mb-2 btn btn-info btn-sm"><i class="fas fa-eye "></i></a>
                                        <a href="{{ route('admin#editProduct',$item->product_id) }}" class="mb-2 btn btn-success btn-sm"><i class="fas fa-edit "></i></a>
                                        <a href="{{ route('admin#deleteProduct',$item->product_id) }}" class="mb-2 btn btn-danger btn-sm" onclick="return confirm('Are you sure to delete?')"><i class="fas fa-trash "></i></a>
                                        <a href="{{ route('admin#createVariant',$item->product_id) }}" class="mb-2 btn btn-dark btn-sm"><i class="fas fa-plus-circle"></i></a>
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
