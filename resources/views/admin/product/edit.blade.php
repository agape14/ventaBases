@extends('admin.layouts.app')
@section('content')
<div class="row pt-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-white d-flex align-items-center">
                <li class="breadcrumb-item"><a href="{{ URL::previous() }}" class="btn btn-dark btn-sm"><i class="fa fa-chevron-left"></i>  Back</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin#dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{ route('admin#product') }}">Products</a></li>
              <li class="breadcrumb-item active" aria-current="page">Edit </li>
            </ol>
          </nav>
    </div>
</div>
<div class="row pb-4">
    <div class="col-12">
        <div class="card shadow-none rounded">
            <div class="card-header ">
                <div class="d-flex align-items-center justify-content-between my-2">
                    <h4 class="mb-0">Edit Product</h4>
                    <a href="{{ route('admin#createVariant',$product->product_id) }}" class="btn btn-success shadow"><i class="fas fa-edit mr-2"></i>View & Edit Variants</a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin#updateProduct',$product->product_id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-3">
                            <div class="card shadow-none">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="">Brand</label>
                                        <select name="brandId" id="" class="form-control">
                                                <option value="">-----select brand-----</option>
                                            @foreach ($brands as $item)
                                                <option value="{{ $item->brand_id }}" {{ $item->brand_id == old('brandId',$product->brand_id) ? 'selected' : '' }}>{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('brandId')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="">Category</label>
                                        <select name="categoryId" class="form-control" id="category">
                                            <option value="">-----select category-----</option>
                                            @foreach ($categories as $item)
                                                <option value="{{ $item->category_id }}" {{ $item->category_id == old('categoryId',$product->category_id) ? 'selected' : '' }}>{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('categoryId')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="">SubCategory</label>
                                        <select name="subCategoryId" class="form-control" id="subCategory">
                                            <option value="">-----select subcategory-----</option>
                                            @foreach ($subCategories as $item)
                                                <option value="{{ $item->subcategory_id }}" {{ $product->subcategory_id == $item->subcategory_id ? 'selected' : '' }}>{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('subCategoryId')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="">Sub-SubCategory</label>
                                        <select name="subsubCategoryId" id="subsubCategory" class="form-control">
                                            <option value="">-----select sub-subcategory-----</option>
                                            @foreach ($subsubCategories as $item)
                                                <option value="{{ $item->subsubcategory_id }}" {{ $product->subsubcategory_id == $item->subsubcategory_id ? 'selected' : '' }}>{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('subsubCategoryId')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card shadow-none">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="">Product Name</label>
                                        <input name="name" type="text" class="form-control" placeholder="enter product name..." value="{{ old('name',$product->name) }}">
                                        @error('name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="">Short Description</label>
                                        <input name="smallDescription" type="text" class="form-control" placeholder="enter small description..." value="{{ old('smallDescription',$product->short_description) }}">
                                        @error('smallDescription')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="">Long Description</label>
                                        <textarea name="longDescription" id="" class="form-control" cols="" rows="5" placeholder="enter long description....">{{ old('longDescription',$product->long_description) }}</textarea>
                                        @error('longDescription')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="">Change Preview Image</label>
                                        <input name="previewImage" type="file" class="form-control"  value="{{ old('previewImage') }}">
                                        <img src="{{ asset('/uploads/products/'.$product->preview_image) }}" class="shadow mt-2" alt="" srcset="" style="width: 130px; height: 130px">
                                        @error('previewImage')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="">Product Multi Image</label>
                                        <input name="multiImage[]" type="file" class="form-control"  value="{{ old('multiImage') }}" multiple>
                                        <div class="d-flex">
                                            @if (!empty($multiImages))
                                            @foreach ($multiImages as $item)
                                                <div class="multiImgBox{{$item->multi_image_id}} position-relative mr-3 my-2 rouned">
                                                    <img src="{{ asset('/uploads/products/'.$item->image) }}" class="rounded shadow" alt="" srcset="" style="width: 130px; height: 130px">
                                                    <button onclick="deleteImg({{$item->multi_image_id }})" type="button" class="btn btn-sm btn-danger position-absolute shadow" style="right: -5px; bottom: -5px;" ><i class="fas fa-trash"></i></button>
                                                </div>
                                            @endforeach
                                            @endif
                                        </div>
                                        @error('multiImage')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="card shadow-none">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="">Original Price</label>
                                        <input name="originalPrice" type="text" class="form-control" placeholder="enter orginal price ..." value="{{ old('originalPrice',$product->original_price) }}">
                                        @error('originalPrice')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="">Selling Price</label>
                                        <input name="sellingPrice" type="text" class="form-control" placeholder="enter selling price ..." value="{{ old('sellingPrice',$product->selling_price) }}">
                                        @error('sellingPrice')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="">Discount Price</label>
                                        <input name="discountPrice" type="text" class="form-control" placeholder="enter discount price ..." value="{{ old('discountPrice',$product->discount_price) }}">
                                        @error('discountPrice')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class=" mb-0">Publish Status</label>
                                        <select name="publishStatus" id="" class="form-control">
                                            <option value="0" {{ old('publishStatus',$product->publish_status) == 0 ? 'selected' : '' }}>Unpublish</option>
                                            <option value="1" {{ old('publishStatus',$product->publish_status) == 1 ? 'selected' : '' }}>Publish</option>
                                        </select>
                                        @error('publishStatus')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class=" mb-0">Special Offer</label>
                                        <select name="specialOffer" id="" class="form-control">
                                            <option value="">-----select-----</option>
                                            <option value="0" {{ old('specialOffer',$product->special_offer) == 0 ? 'selected' : '' }}>Inactive</option>
                                            <option value="1" {{ old('specialOffer',$product->special_offer) == 1 ? 'selected' : '' }}>Active</option>
                                        </select>
                                        @error('specialOffer')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class=" mb-0">Featured</label>
                                        <select name="featured" id="" class="form-control">
                                            <option value="">-----select-----</option>
                                            <option value="0" {{ old('featured',$product->featured) == 0 ? 'selected' : '' }}>Inactive</option>
                                            <option value="1" {{ old('featured',$product->featured) == 1 ? 'selected' : '' }}>Active</option>
                                        </select>
                                        @error('featured')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <button class="btn btn-primary btn-lg">Update Product</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
    <script>
        function deleteImg(id){
            if(confirm('Are you sure to delete?')){
                $.ajax({
                    url: "{{ route('admin#deleteMultiImg') }}",
                    method: "post",
                    dataType: "json",
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id,
                    },
                    success:function(response){
                        $('.multiImgBox'+id).remove();
                        toastr.success(response.success);
                    }
                })
            }
        }

        $( document ).ready(function() {

            let subCatHtml = "";
            let subsubCatHtml = "";
            $('#category').on('click',function () {
                let catId = $(this).val();
                $.ajax({
                    url: "{{ route('admin#productSubCategory') }}",
                    method: "post",
                    dataType: "json",
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: catId,
                    },
                    beforeSend:function(){
                        $('#subCategory').html('<option>-----Loading-----</option>');
                    },
                    success:function(response){
                        subCatHtml = "<option>-----select subcategory-----</option>";
                        subsubCatHtml = "<option>-----select subsubcategory-----</option>";
                        for(let i= 0; i < response.subCategories.length; i++){
                            subCatHtml += `<option value="${response.subCategories[i].subcategory_id}">${response.subCategories[i].name}</option>`;
                        };
                        $('#subCategory').html(subCatHtml);
                        $('#subsubCategory').html(subsubCatHtml);
                    }
                })
            })
            $('#subCategory').on('click',function () {
                let subCatId = $(this).val();
                $.ajax({
                    url: "{{ route('admin#productSubSubCategory') }}",
                    method: "post",
                    dataType: "json",
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: subCatId,
                    },
                    beforeSend:function(){
                        $('#subsubCategory').html('<option>-----Loading-----</option>');
                    },
                    success:function(response){
                        subsubCatHtml = "";
                        for(let i= 0; i < response.subsubCategories.length; i++){
                            subsubCatHtml += `<option value="${response.subsubCategories[i].subsubcategory_id}">${response.subsubCategories[i].name}</option>`;
                        };
                        $('#subsubCategory').html(subsubCatHtml);
                    }
                })
            })

        });
    </script>
@endsection
