@extends('admin.layouts.app')
@section('content')
<div class="row pt-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-white d-flex align-items-center">
                <li class="breadcrumb-item"><a href="{{ URL::previous() }}" class="btn btn-dark btn-sm"><i class="fa fa-chevron-left"></i>  Back</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin#dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{ route('admin#subCategory') }}">Sub-SubCategory</a></li>
              <li class="breadcrumb-item active" aria-current="page">Edit</li>
            </ol>
          </nav>
    </div>
</div>
    <div class="row">
        <div class="col-4">
            <div class="card shadow-none">
                <div class="card-header">
                    <h4 class="mb-0">Edit Sub-SubCategory</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin#updateSubSubCat',$subsubCategory->subsubcategory_id) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="">Name</label>
                            <input name="name" type="text" class="form-control" placeholder="enter subcategory name" value="{{ old('name',$subsubCategory->name) }}">
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="">Category</label>
                            <select name="categoryId" class="form-control" id="category">
                                <option value="">-----Select Category-----</option>
                                @foreach ($categories as $item)
                                    <option value="{{ $item->category_id }}" {{ $subsubCategory->category_id == $item->category_id ? 'selected' : '' }}>{{ $item->name }}</option>
                                @endforeach
                            </select>
                            @error('categoryId')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="">SubCategory</label>
                            <select name="subCategoryId" class="form-control" id="subCategory">
                                <option value="">-----Select SubCategory-----</option>
                                @foreach ($subCategories as $item)
                                    <option value="{{ $item->subcategory_id }}" {{ $subsubCategory->subcategory_id == $item->subcategory_id ? 'selected' : '' }}>{{ $item->name }}</option>
                                @endforeach
                            </select>
                            @error('subCategoryId')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <button class="mt-3 btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-8">
            @include('admin.subsubCategory.list')
        </div>
    </div>
@endsection
@section('script')
    <script>
        $('#category').on('change',function () {
            let catId = $(this).val();
            $.ajax({
                url: "{{ route('admin#getSubCategory') }}",
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
                    let subsubCatHtml = "";
                    for(let i= 0; i < response.subCategories.length; i++){
                        subsubCatHtml += `<option value="${response.subCategories[i].subcategory_id}">${response.subCategories[i].name}</option>`;
                    };
                    $('#subCategory').html(subsubCatHtml);
                }
            })
        })
    </script>
@endsection
