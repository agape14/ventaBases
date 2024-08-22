@extends('admin.layouts.app')
@section('content')
<div class="row pt-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-white d-flex align-items-center">
                <li class="breadcrumb-item"><a href="{{ URL::previous() }}" class="btn btn-dark btn-sm"><i class="fa fa-chevron-left"></i>  Back</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin#dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="#">Township</a></li>
              <li class="breadcrumb-item active" aria-current="page">Edit</li>
            </ol>
          </nav>
    </div>
</div>
    <div class="row">
        <div class="col-4">
            <div class="card shadow-none">
                <div class="card-header">
                    <h4 class="mb-0">Edit Township</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin#updateTownship',$township->township_id) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="">Name</label>
                            <input name="name" type="text" class="form-control" placeholder="enter township name" value="{{ old('name',$township->name) }}">
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="">State Division</label>
                            <select name="stateDivisionId"  id="" class="statediviOption form-control">
                                <option value="">-----Select State Division-----</option>
                                @foreach ($stateDivisions as $item)
                                    <option value="{{ $item->state_division_id }}" {{ old('stateDivisionId',$township->state_division_id) == $item->state_division_id ? 'selected' : '' }}>{{ $item->name }}</option>
                                @endforeach
                            </select>
                            @error('stateDivisionId')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="">City</label>
                            <select name="cityId" id="" class="cityOption form-control">
                                <option value="">-----Select City-----</option>
                                @foreach ($cities as $item)
                                    <option value="{{ $item->city_id }}" {{ old('cityId',$township->city_id) == $item->city_id ? 'selected' : '' }}>{{ $item->name }}</option>
                                @endforeach
                            </select>
                            @error('cityId')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <button class="mt-3 btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-8">
            @include('admin.township.list')
        </div>
    </div>
@endsection
@section('script')
<script>
    $('document').ready(function(){
        $('.statediviOption').on('change',function(){
            let id = $(this).val();
            $.ajax({
                url: "{{ route('admin#getCity') }}",
                method: "post",
                dataType: "json",
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id,
                },
                beforeSend:function(){
                    $('.cityOption').html('<option>-----Loading-----</option>');
                },
                success:function(response){
                    let cityHtml = "";
                    for(let i= 0; i < response.cities.length; i++){
                        cityHtml += `<option value="${response.cities[i].city_id}">${response.cities[i].name}</option>`;
                    };
                    $('.cityOption').html(cityHtml);
                }

            })
        })
    })
</script>

@endsection
