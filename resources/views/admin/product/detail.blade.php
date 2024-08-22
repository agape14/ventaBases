@extends('admin.layouts.app')
@section('content')
<div class="row pt-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-white d-flex align-items-center">
                <li class="breadcrumb-item"><a href="{{ URL::previous() }}" class="btn btn-dark btn-sm"><i class="fa fa-chevron-left"></i>  Back</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin#dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{route('admin#product')}}">Products</a></li>
              <li class="breadcrumb-item active" aria-current="page">Details</li>
            </ol>
          </nav>
    </div>
</div>
<div class="row">
    <div class="col-2">
        <div class="card shadow-none rounded">
            <div class="card-header">
                <h5>Preview Image</h5>
            </div>
            <div class="card-body text-center">
                <img src="{{ asset('/uploads/products/'.$product->preview_image) }}" class="my-2" alt="" srcset="" style="width: 150px; height: 150px">
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card shadow-none rounded">
            <div class="card-header">
                <h5>Multi Images</h5>
            </div>
            <div class="card-body d-flex">
                @if (!count($multiImages) == 0)
                    @foreach ($multiImages as $item)
                        <img src="{{ asset('/uploads/products/'.$item->image) }}" class="rounded mr-3 my-2" alt="" srcset="" style="width: 150px; height: 150px; border: 1px solid #888">
                    @endforeach
                @else
                <div class="my-2 d-flex align-items-center" style="height: 150px">
                    {{-- <h3 class="text-black-50">No Images</h3> --}}
                    <a href="{{ route('admin#editProduct',$product->product_id) }}" class="btn btn-outline-primary py-5" style="width: 150px;">Add Images</a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-7">
        <div class="card shadow-none rounded">
            <div class="card-header">
                <div class="d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">Product Details</h4>
                    <a href="{{ route('admin#editProduct',$product->product_id) }}" class="btn btn-success shadow"><i class="fas fa-edit mr-2"></i>Edit</a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tbody>
                            <tr class="bg-primary">
                                <th class="text-nowrap h5 mb-0" style="width: 15%">Name</th>
                                <td class="h5 mb-0">{{ $product->name }}</td>
                            </tr>
                            <tr>
                                <th class="text-nowrap" style="width: 15%">brand</th>
                                <td>{{ $product->brand->name }}</td>
                            </tr>
                            <tr>
                                <th class="text-nowrap" style="width: 15%">Category</th>
                                <td>{{ $product->category->name}}</td>
                            </tr>
                            <tr>
                                <th class="text-nowrap" style="width: 15%">SubCategory</th>
                                <td>{{ $product->subcategory->name }}</td>
                            </tr>
                            <tr>
                                <th class="text-nowrap" style="width: 15%">Sub-SubCategory</th>
                                <td>{{ $product->subsubcategory->name }}</td>
                            </tr>
                            <tr>
                                <th class="text-nowrap" style="width: 15%">Original Price</th>
                                <td>{{ $product->original_price }}</td>
                            </tr>
                            <tr>
                                <th class="text-nowrap" style="width: 15%">Selling Price</th>
                                <td>{{ $product->selling_price }}</td>
                            </tr>
                            <tr>
                                <th class="text-nowrap" style="width: 15%">Discount Price</th>
                                <td>{{ $product->discount_price }}</td>
                            </tr>
                            <tr>
                                <th class="text-nowrap" style="width: 15%">Special Offer</th>
                                <td>
                                    @if ($product->special_offer == 0)
                                        No
                                    @else
                                        Yes
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="text-nowrap" style="width: 15%">Featured</th>
                                <td>
                                    @if ($product->featured == 0)
                                        No
                                    @else
                                        Yes
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="text-nowrap" style="width: 15%">Publish Status</th>
                                <td>
                                    @if ($product->publish_status == 0)
                                        Unpublish
                                    @else
                                        Publish
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="text-nowrap" style="width: 15%">Created At</th>
                                <td>{{$product->created_at}}</td>
                            </tr>
                            <tr>
                                <th class="text-nowrap" style="width: 15%">Updated At</th>
                                <td>{{$product->updated_at}}</td>
                            </tr>
                            <tr>
                                <th class="text-nowrap" style="width: 15%">Short Description</th>
                                <td>{{ $product->short_description }}</td>
                            </tr>
                            <tr>
                                <th class="text-nowrap" style="width: 15%">Long Description</th>
                                <td><textarea class="form-control bg-transparent border-0" id="" rows="10" disabled>{{ $product->long_description }}</textarea></td>
                            </tr>

                        </tbody>
                        </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-5">
        @if (!count($variants) == 0)
        <div class="card shadow-none rounded">
            <div class="card-header">
                <div class="d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">Product Variants</h4>
                    <a href="{{ route('admin#createVariant',$product->product_id) }}" class="btn btn-dark shadow"><i class="fas fa-plus-circle mr-2"></i>Add Variants</a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="dataTable">
                        <thead class="">
                          <tr>
                            <th>#</th>
                            <th>Color</th>
                            <th>Size</th>
                            <th>Stocks</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                            @foreach ($variants as $item)
                                <tr>
                                    <th>{{ $item->product_variant_id }}</th>
                                    <td>
                                        @if (!empty($item->colorName))
                                            {{ $item->colorName }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if (!empty($item->sizeName))
                                            {{ $item->sizeName }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $item->available_stock }}</td>
                                    <td>
                                        <a href="{{ route('admin#editVariant',$item->product_variant_id) }}" class="btn btn-success btn-sm"><i class="fas fa-edit"></i></a>
                                        <a href="{{ route('admin#deleteVariant',$item->product_variant_id) }}" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure to delete?')"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                      </table>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

