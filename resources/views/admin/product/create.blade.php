@extends('admin.layouts.app')
@section('content')
<div class="pt-4 row">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="bg-white breadcrumb d-flex align-items-center">
                <li class="breadcrumb-item"><a href="{{ URL::previous() }}" class="btn btn-dark btn-sm"><i class="fa fa-chevron-left"></i>  Back</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin#dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{route('admin#product')}}">Products</a></li>
              <li class="breadcrumb-item active" aria-current="page">Create</li>
            </ol>
          </nav>
    </div>
</div>
<div class="pb-4 row">
    <div class="col-12">
        <div class="rounded shadow-none card">
            <div class="card-header">
                <h4>Create Product</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('admin#storeProduct') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-4">
                            <div class="shadow-none card">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="">Brand</label>
                                        <select name="brandId" id="" class="form-control">
                                                <option value="">-----select brand-----</option>
                                            @foreach ($brands as $item)
                                                <option value="{{ $item->brand_id }}" {{ $item->brand_id == old('brandId') ? 'selected' : '' }}>{{ $item->name }}</option>
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
                                                <option value="{{ $item->category_id }}" {{ $item->category_id == old('categoryId') ? 'selected' : '' }}>{{ $item->name }}</option>
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
                                        </select>
                                        @error('subCategoryId')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="">Sub-SubCategory</label>
                                        <select name="subsubCategoryId" id="subsubCategory" class="form-control">
                                            <option value="">-----select subsubcategory-----</option>
                                        </select>
                                        @error('subsubCategoryId')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="shadow-none card">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="">Product Name</label>
                                        <input name="name" type="text" class="form-control" placeholder="enter product name..." value="{{ old('name') }}">
                                        @error('name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="">Short Description</label>
                                        <input name="smallDescription" type="text" class="form-control" placeholder="enter small description..." value="{{ old('smallDescription') }}">
                                        @error('smallDescription')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="">Long Description</label>
                                        <textarea name="longDescription" id="" class="form-control" cols="" rows="5" placeholder="enter long description....">{{ old('longDescription') }}</textarea>
                                        @error('longDescription')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="">Product Preview Image</label>
                                        <input name="previewImage" type="file" class="form-control" placeholder="enter long description ..." value="{{ old('previewImage') }}">
                                        @error('previewImage')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="">Product Multi Image</label>
                                        <input name="multiImage[]" type="file" class="form-control" placeholder="enter long description ..." value="{{ old('multiImage') }}" multiple>
                                        @error('multiImage')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="">Color</label>
                                        <select name="colorId" class="form-control" id="">
                                            <option value="">----- select colors ------</option>
                                            <option value="">----- no colors ------</option>
                                            @foreach ($colors as $item)
                                                <option value="{{ $item->color_id }}" {{ old('color_id') == $item->color_id ? 'selected' : '' }}>{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('colorId')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="">Size</label>
                                        <select name="sizeId" class="form-control" id="">
                                            <option value="">----- select size ------</option>
                                            <option value="">----- no size ------</option>
                                            @foreach ($sizes as $item)
                                                <option value="{{ $item->size_id }}" {{ old('size_id') == $item->size_id ? 'selected' : '' }}>{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('sizeId')
                                                <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="">Stock</label>
                                        <input name="avaiStock" type="number" class="form-control" placeholder="available stock">
                                        @error('avaiStock')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="shadow-none card">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="">Buy Price</label>
                                        <input name="originalPrice" type="text" class="form-control" placeholder="enter buy price ..." value="{{ old('originalPrice') }}">
                                        @error('originalPrice')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="">Selling Price</label>
                                        <input name="sellingPrice" type="text" class="form-control" placeholder="enter selling price ..." value="{{ old('sellingPrice') }}">
                                        @error('sellingPrice')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="">Discount Price</label>
                                        <input name="discountPrice" type="text" class="form-control" placeholder="enter discount price ..." value="{{ old('discountPrice') }}">
                                        @error('discountPrice')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="mb-0 ">Publish Status</label>
                                        <select name="publishStatus" id="" class="form-control">
                                            <option value="1" {{ old('publishStatus') == 1 ? 'selected' : '' }}>Publish</option>
                                            <option value="0" {{ old('publishStatus') == 0 ? 'selected' : '' }}>Unpublish</option>
                                        </select>
                                        @error('publishStatus')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="mb-0 ">Special Offer</label>
                                        <select name="specialOffer" id="" class="form-control">
                                            <option value="">-----select-----</option>
                                            <option value="1" {{ old('specialOffer') == 1 ? 'selected' : '' }}>Active</option>
                                            <option value="0" {{ old('specialOffer') == 0 ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                        @error('specialOffer')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="mb-0 ">Featured</label>
                                        <select name="featured" id="" class="form-control">
                                            <option value="">-----select-----</option>
                                            <option value="1" {{ old('featured') == 1 ? 'selected' : '' }}>Active</option>
                                            <option value="0" {{ old('featured') == 0 ? 'selected' : '' }}>Inactive</option>
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
                    <button class="btn btn-primary btn-lg">Add Product</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
    <script>
        let subCatHtml = "";
        let subsubCatHtml = "";
        $('#category').on('change',function () {
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
        $('#subCategory').on('change',function () {
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
    </script>
@endsection
